<?php

class report extends CI_Controller{

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
        $this->load->model('mod_report');
    }


	public function daily_accounts_detail(){
        if ($this->input->post()) {
            $startDate = $this->input->post('startDate');
            $endDate = $this->input->post('endDate');
        } 
		else{
            $startDate = date("Y-m-d");
            $endDate = date("Y-m-d");
        }
        $this->data['startDate'] = $startDate;
        $this->data['endDate'] = $endDate;
        if($this->flexi_auth->get_user_group_id()==4){
            $doctor = $this->flexi_auth->get_user_id();
            $this->data['report_data'] = $this->mod_report->daily_accounts_detail($startDate, $endDate,$doctor);
        }else{
            $this->data['report_data'] = $this->mod_report->daily_accounts_detail($startDate, $endDate);
        }

		$this->data['message'] = $this->session->flashdata('message');
        $this->data['container'] = "report/daily_accounts_detail";
        $this->load->view('main_page', $this->data);
    }
	
	
	public function accounts_summary(){
        if ($this->input->post()) {
            $startDate = $this->input->post('startDate');
            $endDate = $this->input->post('endDate');
        }
        else{
            $startDate = date("Y-m-d");
            $endDate = date("Y-m-d");
        }
        $this->data['startDate'] = $startDate;
        $this->data['endDate'] = $endDate;
        if($this->flexi_auth->get_user_group_id()==4){
            $doctor = $this->flexi_auth->get_user_id();
            $this->data['report_data'] = $this->mod_report->accounts_summary($startDate, $endDate,$doctor);
        }else{
            $this->data['report_data'] = $this->mod_report->accounts_summary($startDate, $endDate);
        }

		$this->data['message'] = $this->session->flashdata('message');
        $this->data['container'] = "report/accounts_summary";
        $this->load->view('main_page', $this->data);

    }
	
	/*public function trend_alalysis(){


        if ($this->input->post()) {
            $startDate = $this->input->post('startDate');
            $endDate = $this->input->post('endDate');
        }
        else{
            $startDate = date("Y-m-d");
            $endDate = date("Y-m-d");
        }
        $this->data['startDate'] = $startDate;
        $this->data['endDate'] = $endDate;

        $this->data['report_data'] =$this->mod_report->trend_alalysis($startDate, $endDate);
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['container'] = "report/trend_alalysis";
        $this->load->view('main_page', $this->data);

    }*/
}
