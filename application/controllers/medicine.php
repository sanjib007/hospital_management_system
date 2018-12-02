<?php

/**
 * Created by PhpStorm.
 * User: taposh
 * Date: 2015-06-16
 * Time: 5:38 PM
 */
class Medicine extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->auth = new stdClass;
        $this->load->library('flexi_auth');
        $this->data = null;
        if (!($this->flexi_auth->is_logged_in())) {
            redirect('auth', 'refresh');
        } else {
            $this->data['infoResult'] = $this->flexi_auth->get_user_by_id()->row();
        }
        $this->load->model('medicine_model');
    }

    public function GetAllMedicine()
    {
        $medicine = $this->medicine_model->GetAllMedicine();
        echo json_encode($medicine);
    }

    public function medicine_dose()
    {
        $medicine = $this->medicine_model->medicine_dose();
        echo json_encode($medicine);
    }
}