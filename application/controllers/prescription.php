<?php

class Prescription extends CI_Controller
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
        $this->load->model('medicine_model');
        $this->load->model('clinic_model');
    }

    public function index()
    {
        $this->data['title'] = 'Create Prescription';
        $this->data['container'] = 'prescription/about';
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
        echo json_encode($info, JSON_NUMERIC_CHECK);
    }

    public function chageStatus()
    {
        $input = file_get_contents("php://input");
        $input = json_decode($input);
        $app_id = $input->app_id;
        $status = $input->status;
        $info = $this->appoinment_model->updateStatus($app_id, $status);
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
            $this->prescription_model->addPrescription($info, $appoinmentId, $doctorId);

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
        if ($info) {
            echo json_encode($info);
        } else {
            echo json_encode(1);
        }

    }

    public function getamount()
    {
        $input = file_get_contents("php://input");
        $input = json_decode($input);
        $info = $this->appoinment_model->getamount($input);
        echo json_encode(true);
    }

    public function prescribe()
    {
        $patient_id = $this->uri->segment(3);
        $appoinmentId = $this->uri->segment(4);
        $doctorId = $this->uri->segment(5);
        if ($this->input->post()) {
            $info = $this->input->post();

            $this->patient_model->updatePatient($info);
            $this->prescription_model->addPrescription($info, $appoinmentId, $doctorId,true);
            $this->medicine_model->addMedicine($info['medicineName']);
            $this->medicine_model->addUpdatePatientMedicine($info,$appoinmentId);


            $this->appoinment_model->updateStatus($appoinmentId,3);

        };
        $this->data['patientInfo'] = $this->patient_model->gatPatientInfo($patient_id);

        $this->data['pescriptionInfo'] = $this->prescription_model->checkAlreadyPescribe($appoinmentId);
        $this->data['allMedicineInfo']= $this->prescription_model->GetallPatientMadicine($appoinmentId);
        $this->data['title'] = 'Patient Information';
        $this->data['container'] = 'prescription/doctors_prescribe';
        $this->load->view('main_page', $this->data);
    }


    public function getPrescriptionInfo(){
        $input = file_get_contents("php://input");
        $input = json_decode($input);

        $hospital_info = $this->clinic_model->getallInfo();
        $doctors_info =$this->flexi_auth->get_user_by_id($input->adoctor_id)->row();
        $patient_info = $this->patient_model->gatPatientInfo($input->client_gen_id);
        $apponment_info = $this->appoinment_model->GetAppInfo($input->apid);
        $prescription_info =$this->prescription_model->GetPrescriptionInfo($input->apid);
        $medicine_info = $this->medicine_model->GetPrescriptionMedicione($input->apid);
        $doctor_schedule = $this->clinic_model->getSchedule($input->adoctor_id);
        $msg = array("schedule"=>$doctor_schedule,"hospital_info"=>$hospital_info,"doctor_info"=>$doctors_info,"appoinment"=>$apponment_info,"patient"=>$patient_info,"prescription"=>$prescription_info,"medicine"=>$medicine_info);
        echo json_encode($msg);
    }



}