<?php
/**
 * Created by PhpStorm.
 * User: taposh
 * Date: 2015-06-28
 * Time: 12:00 PM
 */

class Clinic_info extends CI_Controller {
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
        $this->load->model('clinic_model');
    }

    public function SetUp()
    {
        $this->data['title'] = 'Set up';
        $this->data['container'] = 'clinic/setup';

        if($this->input->post()){

            $this->clinic_model->insertDate($this->input->post());
        }
        $this->data['information'] =  $this->clinic_model->getallInfo();
        $this->data['container'] = "clinic/setup";
        $this->load->view('main_page', $this->data);
    }
}