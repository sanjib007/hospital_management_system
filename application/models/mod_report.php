<?php

class Mod_report extends CI_Model
{


    public function daily_accounts_detail($startDate, $endDate,$doctor=false)
    {
        if($doctor){
            $sql = "SELECT pat.client_name as Name, app.id,app.appointment_date,app.visit_price,app.discount,app.payment_status,ifnull(sum(pp.amount),0) as total,app.STATUS FROM `meda_appointments` as app JOIN patient as pat on pat.client_gen_id= app.patient_id LEFT JOIN payment as pp on app.id =pp.appoinment_id WHERE app.appointment_date BETWEEN '" . $startDate . "' and '" . $endDate . "' and app.doctor_id = ".$doctor." GROUP BY app.id ORDER by app.ticket_number";
        }else{

        }
        $sql = "SELECT pat.client_name as Name, app.id,app.appointment_date,app.visit_price,app.discount,app.payment_status,ifnull(sum(pp.amount),0) as total,app.STATUS FROM `meda_appointments` as app JOIN patient as pat on pat.client_gen_id= app.patient_id LEFT JOIN payment as pp on app.id =pp.appoinment_id WHERE app.appointment_date BETWEEN '" . $startDate . "' and '" . $endDate . "' GROUP BY app.id ORDER by app.ticket_number";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function accounts_summary($startDate, $endDate,$doctor= false)
    {
        if($doctor){
            $sql = "SELECT
	temp.app_date as date,
	sum(temp.vprice) as visit,
	sum(temp.total) as total,
	sum(temp.adis) as discount,
	count(temp.id) as TotalApp,
	(sum(temp.vprice) - (sum(temp.total) - sum(temp.adis)))  as Dues
	from
(SELECT
pat.client_name as Name,
app.id as id,
app.appointment_date as app_date,
app.visit_price as vprice,
app.discount as adis,
app.payment_status,
ifnull(sum(pp.amount),0) as total,
app.STATUS
FROM `meda_appointments` as app
JOIN patient as pat
on pat.client_gen_id= app.patient_id
	LEFT JOIN payment as pp
	on app.id =pp.appoinment_id
	WHERE app.appointment_date
	BETWEEN '" . $startDate . "' and '" . $endDate . "' and app.doctor_id = ".$doctor."
	GROUP BY app.id
	ORDER by app.ticket_number) temp group by temp.app_date";
        }else {


            $sql = "SELECT
	temp.app_date as date,
	sum(temp.vprice) as visit,
	sum(temp.total) as total,
	sum(temp.adis) as discount,
	count(temp.id) as TotalApp,
	(sum(temp.vprice) - (sum(temp.total) - sum(temp.adis)))  as Dues
	from
(SELECT
pat.client_name as Name,
app.id as id,
app.appointment_date as app_date,
app.visit_price as vprice,
app.discount as adis,
app.payment_status,
ifnull(sum(pp.amount),0) as total,
app.STATUS
FROM `meda_appointments` as app
JOIN patient as pat
on pat.client_gen_id= app.patient_id
	LEFT JOIN payment as pp
	on app.id =pp.appoinment_id
	WHERE app.appointment_date
	BETWEEN '" . $startDate . "' and '" . $endDate . "'
	GROUP BY app.id
	ORDER by app.ticket_number) temp group by temp.app_date";
        }
        $query = $this->db->query($sql);
        return $query->result();
    }


    public function trend_alalysis($startDate, $endDate)
    {
        $sql = " SELECT appointment_date as AppointmentDate, COUNT(ticket_number) AS AppoinmentTaken, (Select COUNT(ticket_number) FROM `meda_appointments` where appointment_date = AppointmentDate and Status = 3) as PatientVisited, SUM(visit_price) as AmountEarned, 0 as AmountReceived, discount as DiscountGiven FROM `meda_appointments` Group By `appointment_date`";

        $query = $this->db->query($sql);
        return $query->result_array();
    }


}
