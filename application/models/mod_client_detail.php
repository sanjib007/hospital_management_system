<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mod_client_detail
 *
 * @author Lenovo
 */
class Mod_client_detail extends CI_Model{
    public function getAllClient() {
        $this->db->select('*');
        $query = $this->db->get('company_setup');
        return $query->result();
    }
    
    public function get_company_info_by_id($cid) {
        $this->db->select('*');
        $this->db->where('ID', $cid);
        $query = $this->db->get('company_setup');
        return $query->row();
    }
}
