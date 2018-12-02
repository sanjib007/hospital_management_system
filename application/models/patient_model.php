<?php
/**
 * Created by PhpStorm.
 * User: taposh
 * Date: 2015-06-15
 * Time: 1:38 PM
 */

class Patient_model extends  CI_Model {
    public function search_client_details_by_id($info) {
        $this->db->select('*');
        $this->db->from('patient');
        $this->db->where('mobile', $info->mobile);
        $this->db->or_where('client_gen_id', $info->mobile);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function LastVisitedDate($info) {
        $query = $this->db->query("SELECT appointment_date, DATEDIFF('".$info->todayDate."',appointment_date) AS DiffDate from meda_appointments where patient_id='".$info->client_gen_id."' and doctor_id=".$info->doctorId." order by appointment_date desc limit 1");
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        else{
            return false;
        }
    }


    //Insert Create Client Data to Database
    public  function create_patient($info) {
        $data = array(
            'client_name' => $info->client_name,
            'mobile' => $info->mobile,
            'client_parents_name' => $info->client_parents_name,
            'Gender' => $info->sex
        );

        $this->db->insert('patient', $data);
        return $this->db->insert_id();
    }

    public function update_patient($id,$newGenerateValue){
        $data = array(
            'client_gen_id' => $newGenerateValue
        );

        $this->db->where('id', $id);
        $this->db->update('patient', $data);
    }


    public function insertBooking($info,$client_id) {

        $data = array(
            'doctor_id' => $info->patientDoctorID,
            'patient_id' => $client_id,
            'appointment_date' => $info->bookingDate,
            'appointment_time' => $info->time,
            'visit_duration' => $info->VisitDuration,
            'visit_price' => $info->visitPrice,
            'first_visit' => $info->FirstTime,
            'ticket_number' => $info->token
        );
        if($info->payment==$info->visitPrice){
            $data['payment_status'] = 1;
        }elseif($info->payment==0){
            $data['payment_status'] = 0;
        }else{
            $data['payment_status'] = 2;
        }

        $this->db->insert('meda_appointments', $data);
        $appoinmentId = $this->db->insert_id();

        $data2 = array(

            'title' => "basic initial payment",
            'doctor_id' => $info->patientDoctorID,
            'patient_id' => $client_id,
            'appoinment_id' => $appoinmentId,
            'amount' => $info->payment
        );
        $this->db->insert('payment', $data2);
    }

    public function gatPatientInfo($generatedID){
        $query = $this->db->get_where('patient', array('client_gen_id' => $generatedID));
        return $query->row();
    }

    public function updatePatient($info){
        $data = array(
            'client_name' => $info['client_name'],
            'client_parents_name' => $info['client_parent'],
            'dateOfBirth' => $info['client_date_of_birth'],
            'Gender' => $info['client_gender'],
            'bloodGroup' => $info['blood'],
            'mobile' => $info['client_mobile'],
            'permanent_address' => $info['client_permanent'],
            'present_address' => $info['client_present']
        );
        $this->db->where('client_gen_id', $info['client_id']);
        $this->db->update('patient', $data);
    }




}