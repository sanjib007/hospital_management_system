<?php

class Mod_bank_detail extends CI_Model{
    public function getAllBankInfo() {
        $this->db->select('*');
        $query = $this->db->get('bank_account_setup');
        return $query->result();
    }
}
