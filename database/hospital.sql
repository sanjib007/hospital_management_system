-- phpMyAdmin SQL Dump
-- version 4.4.7
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2016 at 10:45 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hospital`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `dashboardDataGenerate`()
BEGIN
	-- SELECT curDate;
  	SET @startDate = Date(DATE_SUB(NOW(),INTERVAL 7 DAY));
	SET @endDate = Date(DATE_ADD(NOW(),INTERVAL 7 DAY));
	-- SELECT CONCAT(@startDate,'-',@endDate);
	DELETE FROM `dashboard_report` WHERE Date BETWEEN @startDate AND @endDate;
	SET @indexDate = @startDate;
	label1: LOOP
    -- SELECT CONCAT(@indexDate);
	
	/*2nd Loop START*/
	BEGIN	
		DECLARE bCurso CURSOR FOR
		SELECT uacc_id FROM `user_accounts` where uacc_group_fk = 4;
		
		OPEN bCurso;

		BEGIN
			DECLARE exit_loop_two BOOLEAN;
			DECLARE  docID INT;
			
			DECLARE CONTINUE HANDLER FOR NOT FOUND SET exit_loop_two = TRUE;
			InsertShiftAccessLog: LOOP
				FETCH  bCurso INTO docID;
				 IF exit_loop_two THEN
					 CLOSE bCurso;
					 LEAVE InsertShiftAccessLog;
				 END IF;
				 -- select concat('DOC ID : ',docID,' Date  : ',@indexDate);
				 SET @doctor_id = docID;
				 SET @Appointments = (SELECT count(*) as TotalAppointments FROM `meda_appointments` where doctor_id = docID and appointment_date = @indexDate);
				SET @Present = (SELECT COUNT(*) FROM `meda_appointments` WHERE doctor_id = docID AND appointment_date = @indexDate AND status = 1);
				SET @Serving = (SELECT COUNT(*) FROM `meda_appointments` WHERE doctor_id = docID AND appointment_date = @indexDate AND status = 2);
				SET @Completed = (SELECT COUNT(*) FROM `meda_appointments` WHERE doctor_id = docID AND appointment_date = @indexDate AND status = 3);
				
				IF (SELECT SUM(amount) FROM `payment` WHERE appoinment_id in (SELECT id FROM `meda_appointments` WHERE doctor_id = docID AND appointment_date = @indexDate)) IS NULL THEN
					SET @Earned = 0;
				ELSE
					SET @Earned = (SELECT SUM(amount) FROM `payment` WHERE appoinment_id in (SELECT id FROM `meda_appointments` WHERE doctor_id = docID AND appointment_date = @indexDate));
				END IF;
				-- SELECT CONCAT("INSERT INTO `dashboard_report`( `doctor_id`, `Date`, `Appointments`, `Present`, `Serving`, `Completed`, `Earned`) VALUES (",docID,",'",@indexDate,"',",@Appointments,",",@Present,",",@Serving,",",@Completed,",",@Earned,");");
				INSERT INTO `dashboard_report`( `doctor_id`, `Date`, `Appointments`, `Present`, `Serving`, `Completed`, `Earned`) VALUES (docID,@indexDate,@Appointments,@Present,@Serving,@Completed,@Earned);
			END LOOP InsertShiftAccessLog;
			
		END;
	END;
	/*2nd Loop END*/
	SET @indexDate = DATE_ADD(@indexDate,INTERVAL 1 DAY);
    IF @indexDate <= @endDate THEN
      ITERATE label1;
    END IF;
    LEAVE label1;
  END LOOP label1;
  
	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `INSER_Into_Queue`(IN `Patient_Slot_No` INT, IN `doctor_id` INT, IN `patient_name` VARCHAR(100), IN `nowdate` DATE)
BEGIN
DECLARE Current_Slot, Insert_Point, Next_Insert_Point INT;
SET Current_Slot = IFNULL((SELECT Slot_No FROM current_slot where doctor_id=doctor_id and dates=nowdate LIMIT 1),0);

SET Next_Insert_Point = (CEIL(Current_Slot/10)) * 10;

IF(Patient_Slot_No > Current_Slot) THEN 
BEGIN
	SET Insert_Point = IFNULL((
			select Queue_Id
			from slot_queue
			Where Slot_No = (
				select MIN(Slot_No)
				from slot_queue
				WHERE Slot_No > Patient_Slot_No and doctor_id= doctor_id and dates = nowdate
			)
		),(SELECT IFNULL(MAX(Queue_Id),0)+1
			from slot_queue where doctor_id= doctor_id and dates = nowdate));
END;
ELSEIF (Patient_Slot_No <= Current_Slot) THEN 
BEGIN
	SET Insert_Point = IFNULL((
			select Queue_Id
			from slot_queue
			Where Slot_No = (
				SELECT MIN(Slot_No) 
				FROM slot_queue
				WHERE Slot_No > Next_Insert_Point and doctor_id= doctor_id and dates = nowdate
			)
		),
		(select IFNULL(MAX(Queue_Id), Current_Slot+1)
			from slot_queue
		Where Slot_No < Next_Insert_Point and Slot_No > Current_Slot and doctor_id= doctor_id and dates = nowdate
		)	);
END;
END IF;

UPDATE slot_queue
SET
	Queue_Id = IFNULL(Queue_Id,0) + 1
WHERE Queue_Id >= Insert_Point;
INSERT INTO `slot_queue`
(
	`Queue_Id`, `Slot_No`, `Priority`, `doctor_id`, `dates`, `patient_name`, `status`
)
VALUES
(
	Insert_Point, Patient_Slot_No, 0,doctor_id,nowdate,patient_name,0
);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Next_Slot`(OUT `Current_Slot` INT, IN `doctor_id` INT, IN `days` DATE)
BEGIN
	DECLARE num_column int;
IF EXISTS(SELECT * FROM slot_queue where doctor_id= doctor_id and dates = days) THEN
BEGIN
	if not exists(SELECT * 
	FROM `current_slot` 
	WHERE `doctor_id` = doctor_id and `dates`= days) then
		INSERT INTO `current_slot`(`Slot_No`, `Push_Count`, `doctor_id`, `dates`) VALUES (0,0,doctor_id,days);
    end if;
    
	UPDATE `current_slot`
	SET
	`Slot_No` = `Slot_No` + 1 where `doctor_id` = doctor_id and `dates`= days;
	SET Current_Slot = (
			SELECT Slot_No 
			FROM slot_queue 
			WHERE Queue_Id = 1 and doctor_id= doctor_id and dates = days
		);
	DELETE FROM `slot_queue`
	WHERE Queue_Id = 1 and doctor_id= doctor_id and dates = days;
	

	UPDATE `slot_queue`
	SET
	`Queue_Id` = `Queue_Id` - 1 where doctor_id= doctor_id and dates = days;
	
	DELETE FROM `doctor_ongoig` WHERE `doctor_id` = doctor_id and `dates` = days;
	INSERT INTO `doctor_ongoig`(`nowtoken`, `doctor_id`, `dates`) VALUES (Current_Slot,doctor_id,days);
END;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Reset_Slot_Queue`()
BEGIN
	DELETE FROM slot_queue;
	UPDATE `current_slot`
	SET
	`Slot_No` = 0
	LIMIT 1;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) DEFAULT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('29235844601b36c625d3410f0d697a74', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36', 1444803846, 'a:2:{s:9:"user_data";s:0:"";s:10:"flexi_auth";a:7:{s:15:"user_identifier";s:17:"doctor1@r-cis.com";s:7:"user_id";s:2:"20";s:5:"admin";b:0;s:5:"group";a:1:{i:4;s:6:"Doctor";}s:10:"privileges";a:4:{i:16;s:12:"prescription";i:17;s:20:"daily account detail";i:18;s:15:"account summary";i:14;s:14:"set appoinment";}s:22:"logged_in_via_password";b:1;s:19:"login_session_token";s:40:"c8874b307874e53c1f055278a2ed5608d221e7fd";}}'),
('50823c7802a9b36f586cc60ccf8cf28c', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36', 1456220636, 'a:3:{s:9:"user_data";s:0:"";s:10:"flexi_auth";a:7:{s:15:"user_identifier";s:17:"doctor1@r-cis.com";s:7:"user_id";s:2:"20";s:5:"admin";b:0;s:5:"group";a:1:{i:4;s:6:"Doctor";}s:10:"privileges";a:4:{i:16;s:12:"prescription";i:17;s:20:"daily account detail";i:18;s:15:"account summary";i:14;s:14:"set appoinment";}s:22:"logged_in_via_password";b:1;s:19:"login_session_token";s:40:"b7b1e82670cabadb4f86942221f86c57f7c3dce4";}s:17:"flash:old:message";s:63:"<p class="status_msg">You have been successfully logged in.</p>";}');

-- --------------------------------------------------------

--
-- Table structure for table `current_slot`
--

CREATE TABLE IF NOT EXISTS `current_slot` (
  `id` int(10) unsigned NOT NULL,
  `Slot_No` int(11) NOT NULL,
  `Push_Count` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `dates` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `current_slot`
--

INSERT INTO `current_slot` (`id`, `Slot_No`, `Push_Count`, `doctor_id`, `dates`) VALUES
(1, 4, 0, 21, '2015-08-19');

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_report`
--

CREATE TABLE IF NOT EXISTS `dashboard_report` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Appointments` int(11) NOT NULL,
  `Present` int(11) NOT NULL,
  `Serving` int(11) NOT NULL,
  `Completed` int(11) NOT NULL,
  `Earned` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5326 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dashboard_report`
--

INSERT INTO `dashboard_report` (`id`, `doctor_id`, `Date`, `Appointments`, `Present`, `Serving`, `Completed`, `Earned`) VALUES
(3699, 21, '2015-09-03', 0, 0, 0, 0, 0),
(3698, 20, '2015-09-03', 0, 0, 0, 0, 0),
(3697, 21, '2015-09-02', 0, 0, 0, 0, 0),
(3696, 20, '2015-09-02', 0, 0, 0, 0, 0),
(3695, 21, '2015-09-01', 0, 0, 0, 0, 0),
(3694, 20, '2015-09-01', 0, 0, 0, 0, 0),
(3693, 21, '2015-08-31', 0, 0, 0, 0, 0),
(3692, 20, '2015-08-31', 0, 0, 0, 0, 0),
(3691, 21, '2015-08-30', 0, 0, 0, 0, 0),
(3690, 20, '2015-08-30', 0, 0, 0, 0, 0),
(3689, 21, '2015-08-29', 0, 0, 0, 0, 0),
(3688, 20, '2015-08-29', 0, 0, 0, 0, 0),
(3687, 21, '2015-08-28', 0, 0, 0, 0, 0),
(3686, 20, '2015-08-28', 0, 0, 0, 0, 0),
(1122, 24, '2015-08-09', 0, 0, 0, 0, 0),
(1121, 21, '2015-08-09', 0, 0, 0, 0, 0),
(1120, 20, '2015-08-09', 0, 0, 0, 0, 0),
(1119, 24, '2015-08-08', 0, 0, 0, 0, 0),
(212, 21, '2015-08-01', 0, 0, 0, 0, 0),
(211, 20, '2015-08-01', 0, 0, 0, 0, 0),
(186, 21, '2015-07-31', 0, 0, 0, 0, 0),
(185, 20, '2015-07-31', 0, 0, 0, 0, 0),
(184, 21, '2015-07-30', 0, 0, 0, 0, 0),
(183, 20, '2015-07-30', 0, 0, 0, 0, 0),
(182, 21, '2015-07-29', 0, 0, 0, 0, 0),
(181, 20, '2015-07-29', 0, 0, 0, 0, 0),
(1118, 21, '2015-08-08', 0, 0, 0, 0, 0),
(1117, 20, '2015-08-08', 0, 0, 0, 0, 0),
(1116, 24, '2015-08-07', 0, 0, 0, 0, 0),
(1115, 21, '2015-08-07', 0, 0, 0, 0, 0),
(242, 21, '2015-08-02', 0, 0, 0, 0, 0),
(241, 20, '2015-08-02', 0, 0, 0, 0, 0),
(1114, 20, '2015-08-07', 0, 0, 0, 0, 0),
(1113, 24, '2015-08-06', 0, 0, 0, 0, 0),
(814, 21, '2015-08-04', 0, 0, 0, 0, 0),
(813, 20, '2015-08-04', 0, 0, 0, 0, 0),
(812, 21, '2015-08-03', 0, 0, 0, 0, 0),
(811, 20, '2015-08-03', 0, 0, 0, 0, 0),
(1022, 21, '2015-08-05', 0, 0, 0, 0, 0),
(1021, 20, '2015-08-05', 0, 0, 0, 0, 0),
(1112, 21, '2015-08-06', 1, 0, 0, 1, 500),
(1111, 20, '2015-08-06', 0, 0, 0, 0, 0),
(3685, 21, '2015-08-27', 0, 0, 0, 0, 0),
(3684, 20, '2015-08-27', 0, 0, 0, 0, 0),
(3683, 21, '2015-08-26', 0, 0, 0, 0, 0),
(3682, 20, '2015-08-26', 0, 0, 0, 0, 0),
(3681, 21, '2015-08-25', 0, 0, 0, 0, 0),
(3680, 20, '2015-08-25', 0, 0, 0, 0, 0),
(3359, 21, '2015-08-19', 9, 7, 0, 1, 3440),
(3358, 20, '2015-08-19', 0, 0, 0, 0, 0),
(3357, 21, '2015-08-18', 0, 0, 0, 0, 0),
(3356, 20, '2015-08-18', 0, 0, 0, 0, 0),
(3355, 21, '2015-08-17', 0, 0, 0, 0, 0),
(3354, 20, '2015-08-17', 0, 0, 0, 0, 0),
(3353, 21, '2015-08-16', 0, 0, 0, 0, 0),
(3352, 20, '2015-08-16', 0, 0, 0, 0, 0),
(3351, 21, '2015-08-15', 0, 0, 0, 0, 0),
(3350, 20, '2015-08-15', 0, 0, 0, 0, 0),
(3349, 21, '2015-08-14', 0, 0, 0, 0, 0),
(3348, 20, '2015-08-14', 0, 0, 0, 0, 0),
(3347, 21, '2015-08-13', 0, 0, 0, 0, 0),
(3346, 20, '2015-08-13', 0, 0, 0, 0, 0),
(3287, 21, '2015-08-12', 0, 0, 0, 0, 0),
(3286, 20, '2015-08-12', 0, 0, 0, 0, 0),
(2297, 21, '2015-08-11', 0, 0, 0, 0, 0),
(2296, 20, '2015-08-11', 0, 0, 0, 0, 0),
(2103, 24, '2015-08-10', 0, 0, 0, 0, 0),
(2102, 21, '2015-08-10', 0, 0, 0, 0, 0),
(2101, 20, '2015-08-10', 0, 0, 0, 0, 0),
(3679, 21, '2015-08-24', 0, 0, 0, 0, 0),
(3678, 20, '2015-08-24', 0, 0, 0, 0, 0),
(3677, 21, '2015-08-23', 0, 0, 0, 0, 0),
(3676, 20, '2015-08-23', 0, 0, 0, 0, 0),
(3651, 21, '2015-08-22', 0, 0, 0, 0, 0),
(3650, 20, '2015-08-22', 0, 0, 0, 0, 0),
(3649, 21, '2015-08-21', 0, 0, 0, 0, 0),
(3648, 20, '2015-08-21', 0, 0, 0, 0, 0),
(3647, 21, '2015-08-20', 0, 0, 0, 0, 0),
(3646, 20, '2015-08-20', 0, 0, 0, 0, 0),
(3700, 20, '2015-09-04', 0, 0, 0, 0, 0),
(3701, 21, '2015-09-04', 0, 0, 0, 0, 0),
(3702, 20, '2015-09-05', 0, 0, 0, 0, 0),
(3703, 21, '2015-09-05', 0, 0, 0, 0, 0),
(3704, 20, '2015-09-06', 0, 0, 0, 0, 0),
(3705, 21, '2015-09-06', 0, 0, 0, 0, 0),
(5295, 21, '2015-10-21', 0, 0, 0, 0, 0),
(5294, 20, '2015-10-21', 0, 0, 0, 0, 0),
(5293, 21, '2015-10-20', 0, 0, 0, 0, 0),
(5292, 20, '2015-10-20', 0, 0, 0, 0, 0),
(5291, 21, '2015-10-19', 0, 0, 0, 0, 0),
(5290, 20, '2015-10-19', 0, 0, 0, 0, 0),
(5289, 21, '2015-10-18', 0, 0, 0, 0, 0),
(5288, 20, '2015-10-18', 0, 0, 0, 0, 0),
(5287, 21, '2015-10-17', 0, 0, 0, 0, 0),
(5286, 20, '2015-10-17', 0, 0, 0, 0, 0),
(5285, 21, '2015-10-16', 0, 0, 0, 0, 0),
(5284, 20, '2015-10-16', 0, 0, 0, 0, 0),
(4383, 21, '2015-10-05', 0, 0, 0, 0, 0),
(4382, 20, '2015-10-05', 0, 0, 0, 0, 0),
(4381, 21, '2015-10-04', 0, 0, 0, 0, 0),
(4380, 20, '2015-10-04', 1, 0, 0, 0, 100),
(4379, 21, '2015-10-03', 0, 0, 0, 0, 0),
(4378, 20, '2015-10-03', 0, 0, 0, 0, 0),
(4377, 21, '2015-10-02', 0, 0, 0, 0, 0),
(4376, 20, '2015-10-02', 0, 0, 0, 0, 0),
(4375, 21, '2015-10-01', 0, 0, 0, 0, 0),
(4374, 20, '2015-10-01', 2, 0, 0, 1, 600),
(4373, 21, '2015-09-30', 0, 0, 0, 0, 0),
(4372, 20, '2015-09-30', 0, 0, 0, 0, 0),
(4371, 21, '2015-09-29', 0, 0, 0, 0, 0),
(4370, 20, '2015-09-29', 0, 0, 0, 0, 0),
(3889, 21, '2015-09-25', 0, 0, 0, 0, 0),
(3888, 20, '2015-09-25', 0, 0, 0, 0, 0),
(3887, 21, '2015-09-24', 0, 0, 0, 0, 0),
(3886, 20, '2015-09-24', 0, 0, 0, 0, 0),
(4369, 21, '2015-09-28', 0, 0, 0, 0, 0),
(4368, 20, '2015-09-28', 0, 0, 0, 0, 0),
(4157, 21, '2015-09-26', 0, 0, 0, 0, 0),
(4156, 20, '2015-09-26', 0, 0, 0, 0, 0),
(4367, 21, '2015-09-27', 0, 0, 0, 0, 0),
(4366, 20, '2015-09-27', 0, 0, 0, 0, 0),
(5283, 21, '2015-10-15', 0, 0, 0, 0, 0),
(5282, 20, '2015-10-15', 0, 0, 0, 0, 0),
(5281, 21, '2015-10-14', 0, 0, 0, 0, 0),
(5280, 20, '2015-10-14', 0, 0, 0, 0, 0),
(5279, 21, '2015-10-13', 0, 0, 0, 0, 0),
(5278, 20, '2015-10-13', 0, 0, 0, 0, 0),
(5277, 21, '2015-10-12', 0, 0, 0, 0, 0),
(5276, 20, '2015-10-12', 0, 0, 0, 0, 0),
(5275, 21, '2015-10-11', 0, 0, 0, 0, 0),
(5274, 20, '2015-10-11', 0, 0, 0, 0, 0),
(5273, 21, '2015-10-10', 0, 0, 0, 0, 0),
(5272, 20, '2015-10-10', 0, 0, 0, 0, 0),
(5271, 21, '2015-10-09', 0, 0, 0, 0, 0),
(5270, 20, '2015-10-09', 0, 0, 0, 0, 0),
(5269, 21, '2015-10-08', 0, 0, 0, 0, 0),
(5268, 20, '2015-10-08', 0, 0, 0, 0, 0),
(4967, 21, '2015-10-06', 0, 0, 0, 0, 0),
(4966, 20, '2015-10-06', 0, 0, 0, 0, 0),
(5267, 21, '2015-10-07', 0, 0, 0, 0, 0),
(5266, 20, '2015-10-07', 0, 0, 0, 0, 0),
(5296, 20, '2016-02-16', 0, 0, 0, 0, 0),
(5297, 21, '2016-02-16', 0, 0, 0, 0, 0),
(5298, 20, '2016-02-17', 0, 0, 0, 0, 0),
(5299, 21, '2016-02-17', 0, 0, 0, 0, 0),
(5300, 20, '2016-02-18', 0, 0, 0, 0, 0),
(5301, 21, '2016-02-18', 0, 0, 0, 0, 0),
(5302, 20, '2016-02-19', 0, 0, 0, 0, 0),
(5303, 21, '2016-02-19', 0, 0, 0, 0, 0),
(5304, 20, '2016-02-20', 0, 0, 0, 0, 0),
(5305, 21, '2016-02-20', 0, 0, 0, 0, 0),
(5306, 20, '2016-02-21', 0, 0, 0, 0, 0),
(5307, 21, '2016-02-21', 0, 0, 0, 0, 0),
(5308, 20, '2016-02-22', 0, 0, 0, 0, 0),
(5309, 21, '2016-02-22', 0, 0, 0, 0, 0),
(5310, 20, '2016-02-23', 0, 0, 0, 0, 0),
(5311, 21, '2016-02-23', 0, 0, 0, 0, 0),
(5312, 20, '2016-02-24', 0, 0, 0, 0, 0),
(5313, 21, '2016-02-24', 0, 0, 0, 0, 0),
(5314, 20, '2016-02-25', 0, 0, 0, 0, 0),
(5315, 21, '2016-02-25', 0, 0, 0, 0, 0),
(5316, 20, '2016-02-26', 0, 0, 0, 0, 0),
(5317, 21, '2016-02-26', 0, 0, 0, 0, 0),
(5318, 20, '2016-02-27', 0, 0, 0, 0, 0),
(5319, 21, '2016-02-27', 0, 0, 0, 0, 0),
(5320, 20, '2016-02-28', 0, 0, 0, 0, 0),
(5321, 21, '2016-02-28', 0, 0, 0, 0, 0),
(5322, 20, '2016-02-29', 0, 0, 0, 0, 0),
(5323, 21, '2016-02-29', 0, 0, 0, 0, 0),
(5324, 20, '2016-03-01', 0, 0, 0, 0, 0),
(5325, 21, '2016-03-01', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `demo_user_address`
--

CREATE TABLE IF NOT EXISTS `demo_user_address` (
  `uadd_id` int(11) NOT NULL,
  `uadd_uacc_fk` int(11) NOT NULL DEFAULT '0',
  `uadd_alias` varchar(50) NOT NULL DEFAULT '',
  `uadd_recipient` varchar(100) NOT NULL DEFAULT '',
  `uadd_phone` varchar(25) NOT NULL DEFAULT '',
  `uadd_company` varchar(75) NOT NULL DEFAULT '',
  `uadd_address_01` varchar(100) NOT NULL DEFAULT '',
  `uadd_address_02` varchar(100) NOT NULL DEFAULT '',
  `uadd_city` varchar(50) NOT NULL DEFAULT '',
  `uadd_county` varchar(50) NOT NULL DEFAULT '',
  `uadd_post_code` varchar(25) NOT NULL DEFAULT '',
  `uadd_country` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `demo_user_address`
--

INSERT INTO `demo_user_address` (`uadd_id`, `uadd_uacc_fk`, `uadd_alias`, `uadd_recipient`, `uadd_phone`, `uadd_company`, `uadd_address_01`, `uadd_address_02`, `uadd_city`, `uadd_county`, `uadd_post_code`, `uadd_country`) VALUES
(1, 4, 'Home', 'Joe Public', '0123456789', '', '123', '', 'My City', 'My County', 'My Post Code', 'My Country'),
(2, 4, 'Work', 'Joe Public', '0123456789', 'Flexi', '321', '', 'My Work City', 'My Work County', 'My Work Post Code', 'My Work Country');

-- --------------------------------------------------------

--
-- Table structure for table `demo_user_profiles`
--

CREATE TABLE IF NOT EXISTS `demo_user_profiles` (
  `upro_id` int(11) NOT NULL,
  `upro_uacc_fk` int(11) NOT NULL DEFAULT '0',
  `upro_company` varchar(50) DEFAULT '',
  `upro_first_name` varchar(50) NOT NULL DEFAULT '',
  `upro_last_name` varchar(50) NOT NULL DEFAULT '',
  `upro_phone` varchar(25) NOT NULL DEFAULT '',
  `upro_newsletter` tinyint(1) DEFAULT '0',
  `photo_avater` text,
  `photo` text,
  `gender` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `blood_group` varchar(50) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `default_visit_price` decimal(10,2) DEFAULT NULL,
  `default_visit_duration` tinyint(3) DEFAULT NULL,
  `previous_visit_price` float DEFAULT NULL,
  `previous_visit_day` int(11) DEFAULT NULL,
  `present_address` varchar(200) DEFAULT NULL,
  `permanent_address` varchar(200) DEFAULT NULL,
  `doctor_prescribe_info` text
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `demo_user_profiles`
--

INSERT INTO `demo_user_profiles` (`upro_id`, `upro_uacc_fk`, `upro_company`, `upro_first_name`, `upro_last_name`, `upro_phone`, `upro_newsletter`, `photo_avater`, `photo`, `gender`, `date_of_birth`, `blood_group`, `occupation`, `default_visit_price`, `default_visit_duration`, `previous_visit_price`, `previous_visit_day`, `present_address`, `permanent_address`, `doctor_prescribe_info`) VALUES
(1, 1, '', 'Johnd', 'Admind', '0123456789', 0, '83874034330e2ec84316ec3dfc577017.jpg', '83874034330e2ec84316ec3dfc577017.jpg', 'male', '1923-12-02', 'A+', 'Sldkjfdlkjdf '''' jlkjlsdfd "" ,,.. ''''''', NULL, NULL, NULL, NULL, 'dhaka', 'khulna', NULL),
(17, 20, '', 'arif', 'hossain', '01714116111', 0, 'f5809a488e2d688c87bae1ba837ef3a9.jpg', 'f5809a488e2d688c87bae1ba837ef3a9.jpg', 'male', '1990-05-14', 'O+', 'doctors', '500.00', 20, 300, 30, 'dhaka', 'dhaka', '<p><strong>Md Arif Hossain</strong></p>\n\n<p>M.B.B.S, F.C.P.S</p>\n\n<p>44 Dreamland Tower, Suite 566</p>\n\n<p>ABC , Dreamland 1230</p>\n\n<p>tel: +12(012) 345-67-89</p>\n'),
(18, 21, '', 'tanvir', 'ahamed', '01717589746', 0, 'd128b1be873244bead43b636c3fbd1f0.jpg', 'd128b1be873244bead43b636c3fbd1f0.jpg', 'male', '1980-05-19', 'B+', 'doctor', '500.00', 20, 300, 30, 'dhaka', 'dhaka', '<p><strong>Md Arif Hossain</strong></p>\r\n\r\n<p>M.B.B.S, F.C.P.S</p>\r\n\r\n<p>44 Dreamland Tower, Suite 566</p>\r\n\r\n<p>ABC , Dreamland 1230</p>\r\n\r\n<p>tel: +12(012) 345-67-89</p>'),
(19, 22, '', 'sanjibini', 'dhar', '01716559875', 0, '6e8307e3ffd3f130041218843bfe0aad.jpg', '6e8307e3ffd3f130041218843bfe0aad.jpg', 'male', '1984-05-14', 'AB-', 'she is nurse', NULL, NULL, NULL, NULL, 'dhaka', 'dhaka', NULL),
(20, 23, '', 'shorifa', 'hossain', '01746598745', 0, '307f6cd47c1d29f5e2175f55a8beb22f.png', '307f6cd47c1d29f5e2175f55a8beb22f.png', 'female', '1990-05-14', 'O+', 'nurse', NULL, NULL, NULL, NULL, 'dhaka', 'dhaka', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_ongoig`
--

CREATE TABLE IF NOT EXISTS `doctor_ongoig` (
  `nowtoken` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `dates` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctor_ongoig`
--

INSERT INTO `doctor_ongoig` (`nowtoken`, `doctor_id`, `dates`) VALUES
(6, 21, '2015-08-19');

-- --------------------------------------------------------

--
-- Table structure for table `meda_appointments`
--

CREATE TABLE IF NOT EXISTS `meda_appointments` (
  `id` int(11) NOT NULL,
  `appointment_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctor_id` int(10) unsigned NOT NULL DEFAULT '0',
  `patient_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `appointment_date` date NOT NULL DEFAULT '0000-00-00',
  `appointment_time` time NOT NULL DEFAULT '00:00:00',
  `visit_duration` tinyint(3) unsigned NOT NULL DEFAULT '15',
  `visit_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `first_visit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - booked, 1 - present, 2 - doctor room, 3 -complete',
  `ticket_number` decimal(4,2) NOT NULL,
  `payment_status` int(11) NOT NULL DEFAULT '0',
  `discount` float NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `meda_appointments`
--

INSERT INTO `meda_appointments` (`id`, `appointment_description`, `doctor_id`, `patient_id`, `date_created`, `appointment_date`, `appointment_time`, `visit_duration`, `visit_price`, `first_visit`, `status`, `ticket_number`, `payment_status`, `discount`) VALUES
(57, NULL, 21, '08-15-163', '2015-08-19 11:07:20', '2015-08-19', '21:40:00', 20, '500.00', 1, 1, '12.00', 1, 0),
(58, NULL, 21, '08-15-164', '2015-08-19 11:12:12', '2015-08-19', '20:40:00', 20, '500.00', 1, 1, '9.00', 1, 0),
(56, NULL, 21, '08-15-162', '2015-08-19 11:10:59', '2015-08-19', '21:00:00', 20, '500.00', 1, 1, '10.00', 1, 0),
(55, NULL, 21, '08-15-161', '2015-08-19 11:53:40', '2015-08-19', '19:40:00', 20, '500.00', 1, 3, '6.00', 1, 0),
(54, NULL, 21, '08-15-160', '2015-08-19 11:02:11', '2015-08-19', '19:20:00', 20, '500.00', 1, 0, '5.00', 1, 0),
(53, NULL, 21, '08-15-159', '2015-08-19 11:08:03', '2015-08-19', '19:00:00', 20, '500.00', 1, 1, '4.00', 1, 0),
(52, NULL, 21, '08-15-158', '2015-08-19 11:13:47', '2015-08-19', '18:40:00', 20, '500.00', 1, 1, '3.00', 2, 0),
(51, NULL, 21, '08-15-157', '2015-08-19 11:09:02', '2015-08-19', '18:20:00', 20, '500.00', 1, 1, '2.00', 2, 0),
(50, NULL, 21, '08-15-156', '2015-08-19 11:09:17', '2015-08-19', '18:00:00', 20, '500.00', 1, 1, '1.00', 2, 0),
(59, NULL, 20, '10-15-165', '2015-10-01 07:53:49', '2015-10-01', '18:00:00', 15, '500.00', 1, 0, '5.00', 1, 0),
(60, NULL, 20, '10-15-166', '2015-10-01 19:53:17', '2015-10-01', '17:00:00', 15, '500.00', 1, 3, '1.00', 2, 0),
(61, NULL, 20, '10-15-167', '2015-10-03 11:59:02', '2015-10-04', '17:15:00', 15, '500.00', 1, 0, '10.00', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `meda_schedules`
--

CREATE TABLE IF NOT EXISTS `meda_schedules` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doctor_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date_from` date NOT NULL,
  `date_to` date NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `meda_schedules`
--

INSERT INTO `meda_schedules` (`id`, `name`, `doctor_id`, `date_from`, `date_to`) VALUES
(29, NULL, 21, '2015-01-01', '2016-07-01'),
(28, NULL, 20, '2015-01-01', '2016-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `meda_schedule_timeblocks`
--

CREATE TABLE IF NOT EXISTS `meda_schedule_timeblocks` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL DEFAULT '0',
  `week_day` enum('1','2','3','4','5','6','7') CHARACTER SET latin1 NOT NULL DEFAULT '1',
  `time_from` time NOT NULL DEFAULT '00:00:00',
  `time_to` time NOT NULL DEFAULT '00:00:00',
  `time_slots` varchar(3) CHARACTER SET latin1 NOT NULL DEFAULT '15',
  `doctor_address_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `meda_schedule_timeblocks`
--

INSERT INTO `meda_schedule_timeblocks` (`id`, `schedule_id`, `week_day`, `time_from`, `time_to`, `time_slots`, `doctor_address_id`) VALUES
(39, 29, '4', '18:00:00', '22:00:00', '20', NULL),
(38, 29, '2', '17:00:00', '21:00:00', '10', NULL),
(37, 29, '7', '16:00:00', '20:00:00', '15', NULL),
(36, 28, '5', '17:00:00', '23:00:00', '15', NULL),
(35, 28, '3', '16:00:00', '22:00:00', '20', NULL),
(34, 28, '1', '15:00:00', '21:00:00', '15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `meda_timeoffs`
--

CREATE TABLE IF NOT EXISTS `meda_timeoffs` (
  `id` int(11) NOT NULL,
  `doctor_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date_from` date NOT NULL DEFAULT '0000-00-00',
  `time_from` time NOT NULL DEFAULT '00:00:00',
  `date_to` date NOT NULL DEFAULT '0000-00-00',
  `time_to` time NOT NULL DEFAULT '00:00:00',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE IF NOT EXISTS `medicine` (
  `medicine_name` varchar(255) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`medicine_name`, `description`) VALUES
('', NULL),
('napa', NULL),
('paracitamol', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `medicine_dose`
--

CREATE TABLE IF NOT EXISTS `medicine_dose` (
  `medicine_dose_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text CHARACTER SET latin1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `medicine_dose`
--

INSERT INTO `medicine_dose` (`medicine_dose_id`, `title`, `description`) VALUES
(18, '0+0+1', NULL),
(19, '1+0+1', NULL),
(20, '1/2+ 0 + 1/2', NULL),
(21, '২ ফোটা করে ৪ বার', NULL),
(22, '৪ ফোটা করে ২ বার', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE IF NOT EXISTS `patient` (
  `id` int(11) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `client_parents_name` varchar(100) NOT NULL,
  `client_name_gen` varchar(20) DEFAULT '0',
  `client_gen_id` varchar(20) DEFAULT '0',
  `email` varchar(50) DEFAULT NULL,
  `dateOfBirth` date DEFAULT NULL,
  `bloodGroup` varchar(20) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `permanent_address` varchar(200) DEFAULT NULL,
  `present_address` varchar(150) DEFAULT NULL,
  `image` text,
  `occupation` varchar(100) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=168 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `client_name`, `client_parents_name`, `client_name_gen`, `client_gen_id`, `email`, `dateOfBirth`, `bloodGroup`, `Gender`, `mobile`, `permanent_address`, `present_address`, `image`, `occupation`) VALUES
(164, 'hghghg', 'ghghghg', '0', '08-15-164', NULL, NULL, NULL, 'male', '78963', NULL, NULL, NULL, NULL),
(163, 'fdfdfd', 'fgfgfgfg', '0', '08-15-163', NULL, NULL, NULL, 'male', '888', NULL, NULL, NULL, NULL),
(162, 'huhuy', 'ghghjjh', '0', '08-15-162', NULL, NULL, NULL, 'male', '777', NULL, NULL, NULL, NULL),
(161, 'hghjg', 'ghjghjg', '0', '08-15-161', '', '2015-08-19', 'A+', 'male', '666', '', '', NULL, NULL),
(160, 'yuyyyy', 'fdfdfd', '0', '08-15-160', NULL, NULL, NULL, 'male', '555', NULL, NULL, NULL, NULL),
(159, 'eeee', 'errr', '0', '08-15-159', NULL, NULL, NULL, 'male', '444', NULL, NULL, NULL, NULL),
(158, 'ghi', 'ghi', '0', '08-15-158', NULL, NULL, NULL, 'male', '333', NULL, NULL, NULL, NULL),
(157, 'def', 'def', '0', '08-15-157', NULL, NULL, NULL, 'male', '222', NULL, NULL, NULL, NULL),
(156, 'abc', 'abc', '0', '08-15-156', '', '2015-08-22', 'AB+', 'male', '111', '', '', NULL, NULL),
(165, 'Imtiaz Ali', 'Mohammad Ali', '0', '10-15-165', NULL, NULL, NULL, 'male', '01842255603', NULL, NULL, NULL, NULL),
(166, 'Russel Ahmed Rasi', 'Mr. Ahmed', '0', '10-15-166', NULL, '2000-02-03', 'O+', 'male', '01232343213', 'Banasri', 'Banasri', NULL, NULL),
(167, 'Russel Rasi', 'Syed Jalal Ahmed', '0', '10-15-167', NULL, NULL, NULL, 'male', '01833755102', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patient_priority`
--

CREATE TABLE IF NOT EXISTS `patient_priority` (
  `Priority_Id` int(11) NOT NULL,
  `Priority_Name` varchar(100) NOT NULL,
  `Priority_Value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` varchar(100) NOT NULL,
  `appoinment_id` int(11) NOT NULL,
  `payment_date` datetime NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `title`, `doctor_id`, `patient_id`, `appoinment_id`, `payment_date`, `amount`) VALUES
(1, 'basic initial payment', 20, '08-15-144', 38, '2015-08-18 12:05:46', 300),
(2, 'basic initial payment', 20, '08-15-145', 39, '2015-08-18 16:01:36', 500),
(3, 'basic initial payment', 20, '08-15-146', 40, '2015-08-18 16:02:12', 500),
(4, 'basic initial payment', 20, '08-15-147', 41, '2015-08-18 16:03:40', 300),
(5, 'basic initial payment', 20, '08-15-148', 42, '2015-08-18 16:05:02', 300),
(6, 'basic initial payment', 21, '08-15-149', 43, '2015-08-19 14:42:08', 200),
(7, 'basic initial payment', 21, '08-15-150', 44, '2015-08-19 14:42:41', 200),
(8, 'basic initial payment', 21, '08-15-151', 45, '2015-08-19 14:42:59', 300),
(9, 'basic initial payment', 21, '08-15-152', 46, '2015-08-19 14:43:20', 100),
(10, 'basic initial payment', 21, '08-15-153', 47, '2015-08-19 14:43:38', 500),
(11, 'basic initial payment', 21, '08-15-154', 48, '2015-08-19 14:44:09', 50),
(12, 'basic initial payment', 21, '08-15-155', 49, '2015-08-19 15:08:26', 300),
(13, 'basic initial payment', 21, '08-15-156', 50, '2015-08-19 16:53:34', 20),
(14, 'basic initial payment', 21, '08-15-157', 51, '2015-08-19 16:59:02', 20),
(15, 'basic initial payment', 21, '08-15-158', 52, '2015-08-19 16:59:28', 400),
(16, 'basic initial payment', 21, '08-15-159', 53, '2015-08-19 16:59:45', 500),
(17, 'basic initial payment', 21, '08-15-160', 54, '2015-08-19 17:02:11', 500),
(18, 'basic initial payment', 21, '08-15-161', 55, '2015-08-19 17:02:24', 500),
(19, 'basic initial payment', 21, '08-15-162', 56, '2015-08-19 17:02:40', 500),
(20, 'basic initial payment', 21, '08-15-163', 57, '2015-08-19 17:02:55', 500),
(21, 'basic initial payment', 21, '08-15-164', 58, '2015-08-19 17:10:46', 500),
(22, 'basic initial payment', 20, '10-15-165', 59, '0000-00-00 00:00:00', 500),
(23, 'basic initial payment', 20, '10-15-166', 60, '0000-00-00 00:00:00', 100),
(24, 'basic initial payment', 20, '10-15-167', 61, '0000-00-00 00:00:00', 100);

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE IF NOT EXISTS `prescription` (
  `id` int(11) NOT NULL,
  `patient_id` varchar(255) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appoinment_id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `temperature` varchar(255) DEFAULT NULL,
  `blood_pressure` varchar(255) DEFAULT NULL,
  `height` varchar(255) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `complains` text,
  `symtoms` text,
  `diagonosis` text,
  `advice` text,
  `next_meeting_date` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`id`, `patient_id`, `doctor_id`, `appoinment_id`, `created_on`, `temperature`, `blood_pressure`, `height`, `weight`, `complains`, `symtoms`, `diagonosis`, `advice`, `next_meeting_date`) VALUES
(1, '08-15-161', 21, 55, '2015-08-19 11:24:29', '98.4', 'normal', '5.11', '100', 'this is complain', 'this is symtoms', 'this is diagonosis', 'this is good advice', '0000-00-00'),
(2, '08-15-156', 21, 50, '2015-08-19 11:31:24', '98.4', 'normal', '5.11', '100', 'this is complain', 'this is symtoms', 'this is diagonosis', 'good  advice', '0000-00-00'),
(3, '10-15-166', 20, 60, '2015-10-01 09:51:44', '53', '60/120', '23', '45', 'Fever\nHeadache', 'Red', '', '', '2015-11-10');

-- --------------------------------------------------------

--
-- Table structure for table `prescription_medicine`
--

CREATE TABLE IF NOT EXISTS `prescription_medicine` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `medicine_name` text NOT NULL,
  `dose` text NOT NULL,
  `procedure` text NOT NULL,
  `number_days` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prescription_medicine`
--

INSERT INTO `prescription_medicine` (`id`, `app_id`, `medicine_name`, `dose`, `procedure`, `number_days`) VALUES
(10, 50, '', '', '', ''),
(11, 50, '', '', '', ''),
(16, 55, 'napa', '1+0+1', 'after meat', '10 days'),
(17, 55, 'paracitamol', '1+0+1', 'before meat', '15 days'),
(19, 60, 'paracitamol', '1+0+1', 'after Meal', '3 Day');

-- --------------------------------------------------------

--
-- Table structure for table `setup`
--

CREATE TABLE IF NOT EXISTS `setup` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `phone` text,
  `mobile` text,
  `image` text
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `setup`
--

INSERT INTO `setup` (`id`, `name`, `address`, `phone`, `mobile`, `image`) VALUES
(1, 'Recursion Hospital Limited dgfdg', 'H/33 Zakir Hossain Road , Lalmatia , Dhaka-1100', '029101822d', '+8801714116111', '151488d96dde4ab1b8423e95339f5db3.png');

-- --------------------------------------------------------

--
-- Table structure for table `slot_queue`
--

CREATE TABLE IF NOT EXISTS `slot_queue` (
  `Queue_Id` int(11) NOT NULL,
  `Slot_No` int(11) NOT NULL,
  `Priority` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `dates` date NOT NULL,
  `patient_name` varchar(100) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `slot_queue`
--

INSERT INTO `slot_queue` (`Queue_Id`, `Slot_No`, `Priority`, `doctor_id`, `dates`, `patient_name`, `status`) VALUES
(4, 12, 0, 21, '2015-08-19', 'fdfdfd', 0),
(2, 10, 0, 21, '2015-08-19', 'huhuy', 0),
(1, 9, 0, 21, '2015-08-19', 'hghghg', 0),
(3, 3, 0, 21, '2015-08-19', 'ghi', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE IF NOT EXISTS `user_accounts` (
  `uacc_id` int(11) unsigned NOT NULL,
  `uacc_group_fk` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uacc_email` varchar(100) NOT NULL DEFAULT '',
  `uacc_username` varchar(15) NOT NULL DEFAULT '',
  `uacc_password` varchar(60) NOT NULL DEFAULT '',
  `uacc_ip_address` varchar(40) NOT NULL DEFAULT '',
  `uacc_salt` varchar(40) NOT NULL DEFAULT '',
  `uacc_activation_token` varchar(40) NOT NULL DEFAULT '',
  `uacc_forgotten_password_token` varchar(40) NOT NULL DEFAULT '',
  `uacc_forgotten_password_expire` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uacc_update_email_token` varchar(40) NOT NULL DEFAULT '',
  `uacc_update_email` varchar(100) NOT NULL DEFAULT '',
  `uacc_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `uacc_suspend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `uacc_fail_login_attempts` smallint(5) NOT NULL DEFAULT '0',
  `uacc_fail_login_ip_address` varchar(40) NOT NULL DEFAULT '',
  `uacc_date_fail_login_ban` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Time user is banned until due to repeated failed logins',
  `uacc_date_last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uacc_date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`uacc_id`, `uacc_group_fk`, `uacc_email`, `uacc_username`, `uacc_password`, `uacc_ip_address`, `uacc_salt`, `uacc_activation_token`, `uacc_forgotten_password_token`, `uacc_forgotten_password_expire`, `uacc_update_email_token`, `uacc_update_email`, `uacc_active`, `uacc_suspend`, `uacc_fail_login_attempts`, `uacc_fail_login_ip_address`, `uacc_date_fail_login_ban`, `uacc_date_last_login`, `uacc_date_added`) VALUES
(1, 3, 'admin@admin.com', 'admin', '$2a$08$y5zr4V3vqp35HdU5t.eBsOMe.DekUwKIqOadrd.1WKURPwtR6yim6', '::1', 'XKVT29q2Jr', '', '', '0000-00-00 00:00:00', '', '', 1, 0, 0, '', '0000-00-00 00:00:00', '2015-10-14 08:19:57', '2011-01-01 00:00:00'),
(20, 4, 'doctor1@r-cis.com', 'doctor1', '$2a$08$/we6VegU8xcKgGXkhCsPrepTvb1y98dDHGCvk0zMJ0ynTnv/RTFdC', '::1', 'dHBFpXzDYK', '', '', '0000-00-00 00:00:00', '', '', 1, 0, 0, '', '0000-00-00 00:00:00', '2016-02-23 10:44:05', '2015-07-01 12:14:58'),
(21, 4, 'doctor2@r-cis.com', 'doctor2', '$2a$08$7m4aGiMageuTKeMUjgglROT7rhS2QRLwTBqswBI02xkVy3OK9zjYq', '127.0.0.1', 'vjGQZvSR7Z', '', '', '0000-00-00 00:00:00', '', '', 1, 0, 0, '', '0000-00-00 00:00:00', '2015-08-19 17:05:08', '2015-07-01 12:16:51'),
(22, 6, 'nurse1@r-cis.com', 'nurse1', '$2a$08$JAG1fqOiUYJcy.a7T.4SleULAqq18FHWwghpRGehaRTEFJKdDtQYu', '::1', '9Jkc5gJNDj', '', '', '0000-00-00 00:00:00', '', '', 1, 0, 0, '', '0000-00-00 00:00:00', '2015-10-13 14:46:08', '2015-07-01 12:18:49'),
(23, 6, 'nurse2@r-cis.com', 'nurse2', '$2a$08$FR7S3EPw18F566SQezNcVuoojqQTfcKf4ztj65vVe5fghf/9RErHe', '182.48.83.74', 'JMyzYGhcYK', '', '', '0000-00-00 00:00:00', '', '', 1, 0, 0, '', '0000-00-00 00:00:00', '2015-07-04 22:16:19', '2015-07-01 12:20:04');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE IF NOT EXISTS `user_groups` (
  `ugrp_id` smallint(5) NOT NULL,
  `ugrp_name` varchar(20) NOT NULL DEFAULT '',
  `ugrp_desc` varchar(100) NOT NULL DEFAULT '',
  `ugrp_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`ugrp_id`, `ugrp_name`, `ugrp_desc`, `ugrp_admin`) VALUES
(3, 'Master Admin', 'Master Admin : has full admin access rights.', 1),
(4, 'Doctor', '  this is a doctor                ', 0),
(5, 'compounder', 'this is compounder                 ', 0),
(6, 'nurse', 'this is nurse group                 ', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_login_sessions`
--

CREATE TABLE IF NOT EXISTS `user_login_sessions` (
  `usess_uacc_fk` int(11) NOT NULL DEFAULT '0',
  `usess_series` varchar(40) NOT NULL DEFAULT '',
  `usess_token` varchar(40) NOT NULL DEFAULT '',
  `usess_login_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_login_sessions`
--

INSERT INTO `user_login_sessions` (`usess_uacc_fk`, `usess_series`, `usess_token`, `usess_login_date`) VALUES
(20, '', 'b7b1e82670cabadb4f86942221f86c57f7c3dce4', '2016-02-23 10:44:06'),
(20, '', 'c8874b307874e53c1f055278a2ed5608d221e7fd', '2015-10-14 08:24:13');

-- --------------------------------------------------------

--
-- Table structure for table `user_privileges`
--

CREATE TABLE IF NOT EXISTS `user_privileges` (
  `upriv_id` smallint(5) NOT NULL,
  `upriv_name` varchar(20) NOT NULL DEFAULT '',
  `upriv_desc` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_privileges`
--

INSERT INTO `user_privileges` (`upriv_id`, `upriv_name`, `upriv_desc`) VALUES
(1, 'View Users', 'User can view user account details.'),
(2, 'View User Groups', 'User can view user groups.'),
(3, 'View Privileges', 'User can view privileges.'),
(4, 'Insert User Groups', 'User can insert new user groups.'),
(5, 'Insert Privileges', 'User can insert privileges.'),
(6, 'Update Users', 'User can update user account details.'),
(7, 'Update User Groups', 'User can update user groups.'),
(8, 'Update Privileges', 'User can update user privileges.'),
(9, 'Delete Users', 'User can delete user accounts.'),
(10, 'Delete User Groups', 'User can delete user groups.'),
(11, 'Delete Privileges', 'User can delete user privileges.'),
(12, 'Manage Schedule', 'this is for managing schedule               '),
(13, 'queue display', 'displaying queue               '),
(14, 'set appoinment', 'this is for appoinment               '),
(15, 'patient management', 'this is for patient management               '),
(16, 'prescription', 'this is for prescription               '),
(17, 'daily account detail', 'for account details               '),
(18, 'account summary', 'for account summary               '),
(19, 'setup hospital infor', 'for hospital setup               ');

-- --------------------------------------------------------

--
-- Table structure for table `user_privilege_groups`
--

CREATE TABLE IF NOT EXISTS `user_privilege_groups` (
  `upriv_groups_id` smallint(5) unsigned NOT NULL,
  `upriv_groups_ugrp_fk` smallint(5) unsigned NOT NULL DEFAULT '0',
  `upriv_groups_upriv_fk` smallint(5) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_privilege_groups`
--

INSERT INTO `user_privilege_groups` (`upriv_groups_id`, `upriv_groups_ugrp_fk`, `upriv_groups_upriv_fk`) VALUES
(1, 3, 1),
(3, 3, 3),
(4, 3, 4),
(5, 3, 5),
(6, 3, 6),
(7, 3, 7),
(8, 3, 8),
(9, 3, 9),
(10, 3, 10),
(11, 3, 11),
(12, 2, 2),
(13, 2, 4),
(14, 2, 5),
(15, 3, 2),
(16, 3, 12),
(17, 3, 13),
(18, 3, 14),
(19, 3, 15),
(20, 3, 17),
(21, 3, 18),
(22, 4, 16),
(23, 4, 17),
(24, 4, 18),
(25, 6, 13),
(26, 6, 14),
(27, 6, 15),
(28, 3, 19),
(29, 4, 14);

-- --------------------------------------------------------

--
-- Table structure for table `user_privilege_users`
--

CREATE TABLE IF NOT EXISTS `user_privilege_users` (
  `upriv_users_id` smallint(5) NOT NULL,
  `upriv_users_uacc_fk` int(11) NOT NULL DEFAULT '0',
  `upriv_users_upriv_fk` smallint(5) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_privilege_users`
--

INSERT INTO `user_privilege_users` (`upriv_users_id`, `upriv_users_uacc_fk`, `upriv_users_upriv_fk`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity` (`last_activity`);

--
-- Indexes for table `current_slot`
--
ALTER TABLE `current_slot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dashboard_report`
--
ALTER TABLE `dashboard_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `demo_user_address`
--
ALTER TABLE `demo_user_address`
  ADD PRIMARY KEY (`uadd_id`),
  ADD UNIQUE KEY `uadd_id` (`uadd_id`),
  ADD KEY `uadd_uacc_fk` (`uadd_uacc_fk`);

--
-- Indexes for table `demo_user_profiles`
--
ALTER TABLE `demo_user_profiles`
  ADD PRIMARY KEY (`upro_id`),
  ADD UNIQUE KEY `upro_id` (`upro_id`),
  ADD KEY `upro_uacc_fk` (`upro_uacc_fk`) USING BTREE;

--
-- Indexes for table `meda_appointments`
--
ALTER TABLE `meda_appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `appointment_date` (`appointment_date`,`ticket_number`),
  ADD UNIQUE KEY `appointment_date_2` (`appointment_date`,`ticket_number`),
  ADD KEY `status` (`status`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `meda_schedules`
--
ALTER TABLE `meda_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meda_schedule_timeblocks`
--
ALTER TABLE `meda_schedule_timeblocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`medicine_name`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prescription_medicine`
--
ALTER TABLE `prescription_medicine`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setup`
--
ALTER TABLE `setup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`uacc_id`),
  ADD UNIQUE KEY `uacc_id` (`uacc_id`),
  ADD KEY `uacc_group_fk` (`uacc_group_fk`),
  ADD KEY `uacc_email` (`uacc_email`),
  ADD KEY `uacc_username` (`uacc_username`),
  ADD KEY `uacc_fail_login_ip_address` (`uacc_fail_login_ip_address`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`ugrp_id`),
  ADD UNIQUE KEY `ugrp_id` (`ugrp_id`) USING BTREE;

--
-- Indexes for table `user_login_sessions`
--
ALTER TABLE `user_login_sessions`
  ADD PRIMARY KEY (`usess_token`),
  ADD UNIQUE KEY `usess_token` (`usess_token`);

--
-- Indexes for table `user_privileges`
--
ALTER TABLE `user_privileges`
  ADD PRIMARY KEY (`upriv_id`),
  ADD UNIQUE KEY `upriv_id` (`upriv_id`) USING BTREE;

--
-- Indexes for table `user_privilege_groups`
--
ALTER TABLE `user_privilege_groups`
  ADD PRIMARY KEY (`upriv_groups_id`),
  ADD UNIQUE KEY `upriv_groups_id` (`upriv_groups_id`) USING BTREE,
  ADD KEY `upriv_groups_ugrp_fk` (`upriv_groups_ugrp_fk`),
  ADD KEY `upriv_groups_upriv_fk` (`upriv_groups_upriv_fk`);

--
-- Indexes for table `user_privilege_users`
--
ALTER TABLE `user_privilege_users`
  ADD PRIMARY KEY (`upriv_users_id`),
  ADD UNIQUE KEY `upriv_users_id` (`upriv_users_id`) USING BTREE,
  ADD KEY `upriv_users_uacc_fk` (`upriv_users_uacc_fk`),
  ADD KEY `upriv_users_upriv_fk` (`upriv_users_upriv_fk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `current_slot`
--
ALTER TABLE `current_slot`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `dashboard_report`
--
ALTER TABLE `dashboard_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5326;
--
-- AUTO_INCREMENT for table `demo_user_address`
--
ALTER TABLE `demo_user_address`
  MODIFY `uadd_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `demo_user_profiles`
--
ALTER TABLE `demo_user_profiles`
  MODIFY `upro_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `meda_appointments`
--
ALTER TABLE `meda_appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT for table `meda_schedules`
--
ALTER TABLE `meda_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `meda_schedule_timeblocks`
--
ALTER TABLE `meda_schedule_timeblocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=168;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `prescription_medicine`
--
ALTER TABLE `prescription_medicine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `setup`
--
ALTER TABLE `setup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `uacc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `ugrp_id` smallint(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `user_privileges`
--
ALTER TABLE `user_privileges`
  MODIFY `upriv_id` smallint(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `user_privilege_groups`
--
ALTER TABLE `user_privilege_groups`
  MODIFY `upriv_groups_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `user_privilege_users`
--
ALTER TABLE `user_privilege_users`
  MODIFY `upriv_users_id` smallint(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
