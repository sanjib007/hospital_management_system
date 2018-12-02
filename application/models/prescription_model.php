<?php

/**
 * Created by PhpStorm.
 * User: taposh
 * Date: 2015-06-17
 * Time: 3:26 PM
 */
class Prescription_model extends CI_Model
{
    public function checkAlreadyPescribe($info)
    {
        $array = array('appoinment_id' => $info);
        $this->db->where($array);
        $query = $this->db->get('prescription');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function addPrescription($info, $appID, $doctorId,$doctorprescribe=false)
    {
        $data = array(
            'patient_id' => $info['client_id'],
            'doctor_id' => $doctorId,
            'appoinment_id' => $appID,
            'temperature' => $info['client_temperature'],
            'blood_pressure' => $info['client_blood'],
            'height' => $info['client_height'],
            'weight' => $info['client_weight'],
            'complains' => $info['client_complain'],
            'diagonosis' => $info['client_Diagnosis'],
            'symtoms' => $info['client_symtoms']
        );
        if($doctorprescribe){
            $data['advice'] = $info['client_advice'];
            $data['next_meeting_date'] = $info['client_nextappoinment'];
        }
        if ($info['presecription_id'] == "") {
            $this->db->insert('prescription', $data);

        } else {
            $this->db->where('patient_id', $info['client_id']);
            $this->db->where('doctor_id', $doctorId);
            $this->db->where('appoinment_id', $appID);
            $this->db->update('prescription', $data);

        }
        if($doctorprescribe){
            return true;
        }
        $this->session->set_flashdata('msg', 'your information update successfully');
        redirect(current_url(), 'refresh');


    }
    public function GetallPatientMadicine($appId) {
        $this->db->where('app_id', $appId);
        $query = $this->db->get('prescription_medicine');
        if ($query->num_rows() > 0) {
            return $query->result();
        }else{
            return false;
        }
    }

    public function GetPrescriptionInfo($appId){
        $query = $this->db->get_where('prescription', array('appoinment_id' => $appId));
        return $query->row();
    }


}