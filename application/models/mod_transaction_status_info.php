<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mod_transaction_status_info
 *
 * @author Lenovo
 */
class Mod_transaction_status_info extends CI_Model{
    public function getAllStatus() {
        $this->db->select('*');
        $query = $this->db->get('transaction_status_setup');
        return $query->result();
    }
}
