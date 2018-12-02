<?php

class Mod_daily_dashoard_report extends CI_Model {

    
    public function get_dashboard_report_admin() {
        $this->callSP();
        $query = $this->db->query("SELECT date,sum(Appointments) as Appointments,sum(Present) as present,sum(Serving) as Serving,sum(Completed) as Completed,sum(Earned) as Earned FROM `dashboard_report` where Date>= date(DATE_SUB(NOW(), INTERVAL 7 DAY)) && Date<= date(DATE_ADD(NOW(), INTERVAL 7 DAY)) GROUP BY date order by Date asc");
        return $query->result();
    }

    public function Get_dashboard_doctor()
    {
        $doctor_id =$this->flexi_auth->get_user_id();
        $this->callSP();
        $query = $this->db->query("SELECT date,Appointments, Present as present,Serving,Completed,Earned FROM `dashboard_report` where doctor_id = ".$doctor_id." and Date>= date(DATE_SUB(NOW(), INTERVAL 7 DAY)) && Date<= date(DATE_ADD(NOW(), INTERVAL 7 DAY)) group by Date ORDER BY Date ASC");
        return $query->result();
    }
	

	
	public function callSP(){
		$query = $this->db->query("call dashboardDataGenerate()");
		return $query->result();
	}


}
