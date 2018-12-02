<?php

class Mod_queue_management extends CI_Model {  
    public function get_ongoing($doctor_id) {


        $dates = date("Y-m-d");
        $query = $this->db->get_where('doctor_ongoig', array('doctor_id' => $doctor_id,'dates'=>$dates));
        return $query->row();
    }

    public function get_queue($doctor_id) {

        $dates = date("Y-m-d");
        $txt = sprintf("SELECT * FROM `slot_queue` where doctor_id=%u and dates='%s'  order by queue_id asc limit 5",$doctor_id,$dates);
		$query = $this->db->query($txt);
        return $query->result();
    }
}
