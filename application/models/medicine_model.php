<?php

/**
 * Created by PhpStorm.
 * User: taposh
 * Date: 2015-06-16
 * Time: 6:01 PM
 */
class Medicine_model extends CI_Model
{
    public function GetAllMedicine()
    {
        $this->db->select('medicine_name');
        $query = $this->db->get('medicine');
        return $query->result();
    }

    public function medicine_dose()
    {
        $this->db->select('title');
        $query = $this->db->get('medicine_dose');
        return $query->result();
    }

    public function addMedicine($medicines) {
       foreach($medicines as $amedicine){

           $info = array("medicine_name" => $amedicine);
           $insert_query = $this->db->insert_string('medicine', $info);
           $insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
           $this->db->query($insert_query);
       }

    }

    public function addUpdatePatientMedicine($information,$appId) {
        $appoinmentID = $appId;
        $data = array();
        $this->db->where('app_id', $appoinmentID);
        $this->db->delete('prescription_medicine');
        for ($i = 0; $i < count($information['medicineName']); $i++) {
            $data[] = array('app_id' => $appoinmentID, 'medicine_name' => $information['medicineName'][$i], 'dose' => $information['medicineDose'][$i], 'procedure' => $information['medicineProcedure'][$i], 'number_days' => $information['medicinedays'][$i]);
        }
        $this->db->insert_batch('prescription_medicine', $data);
    }

    public function GetPrescriptionMedicione($app_id){
        $query = $this->db->get_where('prescription_medicine', array('app_id' => $app_id));
        return $query->result();
    }
}