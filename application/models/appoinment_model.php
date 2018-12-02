<?php

/**
 * Created by PhpStorm.
 * User: taposh
 * Date: 2015-06-15
 * Time: 6:28 PM
 */
class Appoinment_model extends CI_Model
{
    public function getTodayApp($did, $ddate)
    {
        $this->db->select('meda_appointments.id as apid,meda_appointments.visit_price,meda_appointments.doctor_id as adoctor_id,meda_appointments.appointment_time as mtime,meda_appointments.first_visit as visit,meda_appointments.status,meda_appointments.payment_status,meda_appointments.ticket_number,patient.client_name,patient.client_gen_id');
        $this->db->from('meda_appointments');
        $this->db->join('patient', 'patient.client_gen_id = meda_appointments.patient_id');
        $this->db->where('meda_appointments.doctor_id', $did);
        $this->db->where('meda_appointments.appointment_date', $ddate);
        $query = $this->db->get();
        return $query->result();
    }

    public function updateStatus($info,$status)
    {

        $data = array(
            'status' => $status,
            'date_created' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $info);
        $this->db->update('meda_appointments', $data);

    //   $this->db->query("call INSER_Into_Queue(" . $info->ticket_number . "," . $info->adoctor_id . ",'" . $info->client_name . "','" . $info->today_date . "')");


    }

    public function getPaymentList($info)
    {
        $query = $this->db->get_where('payment', array('appoinment_id' => $info->apid));
        return $query->result();
    }

    public function getamount($info)
    {
        $data = array(
            'title' => 'Full payment',
            'doctor_id' => $info->doctorId,
            'patient_id' => $info->client_gen_id,
            'appoinment_id' => $info->appid,
            'amount' => $info->remain
        );
        $this->db->insert('payment', $data);
        $data = array(
            'payment_status' => 1
        );

        $this->db->where('id', $info->appid);
        $this->db->update('meda_appointments', $data);
    }

    public function GetAppInfo($appID)
    {
        $query = $this->db->get_where('meda_appointments', array('id' => $appID));
        return $query->row();
    }

    public function getNowServing($dates, $doctor_id)
    {
        $query = $this->db->get_where('doctor_ongoig', array('doctor_id' => $doctor_id, 'dates' => $dates));
        if ($query->num_rows() > 0) {
            $info = $query->row();
            return $info->nowtoken;
        }
        return 0;
    }

    public function getNextPatient($info)
    {
        $doctor_id = $this->flexi_auth->get_user_id();

        $info = array($doctor_id,$info->dates);

        $this->db->query("CALL Next_Slot(@Current_Slot,?,?)", $info);

        // get the stored procedure returned output
       $query = $this->db->query(' SELECT @Current_Slot AS `Current_Slot`');
        $row = $query->row();

        return $row->Current_Slot;
    }


}