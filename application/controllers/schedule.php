<?php

/**
 * Created by PhpStorm.
 * User: taposh
 * Date: 2015-06-08
 * Time: 12:49 PM
 */
class Schedule extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $this->auth = new stdClass;
        $this->load->library('flexi_auth');
        $this->data = null;
        if (!($this->flexi_auth->is_logged_in())) {
            redirect('auth', 'refresh');
        } else {
            $this->data['infoResult'] = $this->flexi_auth->get_user_by_id()->row();
        }
        $this->load->model('schedule_model');
    }

    public function index()
    {
        $this->data['title'] = 'Create Schedule';
        $this->data['container'] = 'schedule/index';
        $this->load->view('main_page', $this->data);
    }

    public function GetAllInfo()
    {
        $sql_select = array('*');
        $sql_where = 'user_groups.ugrp_id = 4';
        $doctorList = $this->flexi_auth->get_users_group($sql_select, $sql_where)->result();
        $scheduleList = $this->schedule_model->get_schedule_list();
        $msg = array("doctors" => $doctorList, "schedule" => $scheduleList);
        echo json_encode($msg);
    }

    public function insertSchedule()
    {
        $postData = file_get_contents("php://input");
        $request = json_decode($postData);
        $msg = $this->schedule_model->insertSchedule($request);
        echo json_encode($msg);
    }

    public function deleteSchedule()
    {
        $postData = file_get_contents("php://input");
        $request = json_decode($postData);
        $msg = $this->schedule_model->deleteSchedule($request);
        echo json_encode(true);
    }

    public function updateSchedule()
    {
        $postData = file_get_contents("php://input");
        $request = json_decode($postData);
        $msg = $this->schedule_model->updateSchedule($request);
        echo json_encode(true);
    }

    public function GetTimeBlock()
    {
        $postData = file_get_contents("php://input");
        $request = json_decode($postData);
        $msg = $this->schedule_model->GetTimeBlock($request);
        echo json_encode($msg);
    }


    public function InsertTimeBlock()
    {
        $postData = file_get_contents("php://input");
        $request = json_decode($postData);
        $msg = $this->schedule_model->InsertTimeBlock($request);
        echo json_encode($msg);
    }


    public function deleteTimeBlock()
    {
        $postData = file_get_contents("php://input");
        $request = json_decode($postData);
        $msg = $this->schedule_model->deleteTimeBlock($request);
        echo json_encode(true);
    }

    public function  updateTimeBlock()
    {
        $postData = file_get_contents("php://input");
        $request = json_decode($postData);
        $msg = $this->schedule_model->updateTimeBlock($request);
        echo json_encode(true);
    }

    public function view_queue()
    {
        if ($this->flexi_auth->get_user_group_id() != 4) {
            redirect('/schedule/view_queue_others/', 'refresh');
        }
        $this->load->model('mod_queue_management');
        $doctor_id = $this->flexi_auth->get_user_id();
        $this->data['now_serving'] = $this->mod_queue_management->get_ongoing($doctor_id);
        $this->data['queue_list'] = $this->mod_queue_management->get_queue($doctor_id);

        $this->data['title'] = 'Queue';
        $this->data['container'] = 'schedule/queue_view';
        $this->load->view('main_page', $this->data);

    }

    public function view_queue_others()
    {
        $sql_select = array('*');
        $sql_where = 'user_groups.ugrp_id = 4';
        $this->data['doctorList'] = $this->flexi_auth->get_users_group($sql_select, $sql_where)->result();
        $this->load->model('mod_queue_management');
        if($this->input->post()){
            $doctor_id = $this->input->post('doctorId');
            $info = $this->flexi_auth->get_user_by_id($doctor_id);
            $this->data['doctorInfo'] = $info->row();
/*            $this->data['dccId'] = $doctor_id;*/
            $this->data['now_serving'] = $this->mod_queue_management->get_ongoing($doctor_id);
            $this->data['queue_list'] = $this->mod_queue_management->get_queue($doctor_id);
        }
        $this->data['title'] = 'Queue';
        $this->data['container'] = 'schedule/queue_view1';
        $this->load->view('main_page', $this->data);
    }


}