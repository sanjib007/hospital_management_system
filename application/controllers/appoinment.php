<?php

/**
 * Created by PhpStorm.
 * User: taposh
 * Date: 2015-06-15
 * Time: 5:35 PM
 */
class Appoinment extends CI_Controller
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
        $this->load->model('appoinment_model');
        $this->load->model('patient_model');
        $this->load->model('prescription_model');
    }

    public function index()
    {
        $sql_select = array('*');
        $sql_where = 'user_groups.ugrp_id = 4';
        $this->data['doctorList'] = $this->flexi_auth->get_users_group($sql_select, $sql_where)->result();
        $this->data['title'] = 'Create Schedule';
        $this->data['container'] = 'appoinment/about';
        $this->load->view('main_page', $this->data);
    }

    public function GetTodayList()
    {
        $input = file_get_contents("php://input");
        $input = json_decode($input);
        $todayDate = $input->todayDate;
        $doctorId = $input->doctor_id;
        $mysqldate = date('Y-m-d', strtotime($todayDate));
        $info = $this->appoinment_model->getTodayApp($doctorId, $mysqldate);
        $serving = $this->appoinment_model->getNowServing($mysqldate,$doctorId);
        $information = array("info"=>$info,"serve"=>strval($serving));
        echo json_encode($information, JSON_NUMERIC_CHECK);
    }

    public function chageStatus()
    {

        $input = file_get_contents("php://input");
        $input = json_decode($input);
        $input->today_date = $date = date('Y-m-d', strtotime(str_replace('-', '/', $input->today_date)));


        $info = $this->appoinment_model->updateStatus($input);
        $msg = array("info" => true);
        echo json_encode($msg);
    }

    public function getInfo()
    {

        $patient_id = $this->uri->segment(3);
        $appoinmentId = $this->uri->segment(4);
        $doctorId = $this->uri->segment(5);
        if ($this->input->post()) {
            $info = $this->input->post();
            $this->patient_model->updatePatient($info);
            $this->prescription_model->addPrescription($info,$appoinmentId,$doctorId);

        };
        $this->data['patientInfo'] = $this->patient_model->gatPatientInfo($patient_id);
        $this->data['pescriptionInfo'] = $this->prescription_model->checkAlreadyPescribe($appoinmentId);
        $this->data['title'] = 'Patient Information';
        $this->data['container'] = 'appoinment/partient_basic_information';
        $this->load->view('main_page', $this->data);
    }

    public function getPaymentList()
    {
        $input = file_get_contents("php://input");
        $input = json_decode($input);
        $info = $this->appoinment_model->getPaymentList($input);
        if($info){
            echo json_encode($info);
        }else{
            echo json_encode(1);
        }

    }

    public function getamount(){
        $input = file_get_contents("php://input");
        $input = json_decode($input);
        $info = $this->appoinment_model->getamount($input);
        echo json_encode(true);
    }

    public function getNextPatient()
    {
        $input = file_get_contents("php://input");
        $input = json_decode($input);
        $info = $this->appoinment_model->getNextPatient($input);
        echo json_encode($info,JSON_NUMERIC_CHECK);
    }

}