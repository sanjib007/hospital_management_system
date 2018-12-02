<?php

class Clients_account extends CI_Controller
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
        $this->load->helper('date');
        $this->load->model('mod_client_detail');
        $this->load->model('mod_clients_account');
        $this->load->model('mod_bank_detail');
        $this->load->model('mod_transaction_status_info');
    }

    public function view()
    {
        if ($this->input->post()) {
            $fPost_date = $this->input->post('stratDate');
            $ePost_date = $this->input->post('endDate');            
            $start_date = date('Y-m-d', strtotime($fPost_date));
            $end_date = date('Y-m-d', strtotime($ePost_date));
            $year = date('Y', strtotime($this->input->post('stratDate')));
        } else {
            $month = date("m", now());
            $start_date = date("Y") . '-' . $month . "-01";
            $end_date = date("Y-m-d");
            $year = date("Y", now());
        }
        $data['fDate'] = $start_date;
        $data['tDate'] = $end_date;
        $data['accInfo'] = $this->mod_clients_account->getInfoByDate($start_date,$end_date);
        $data['client_info'] = $this->mod_client_detail->getAllClient();
        $data['bank_info'] = $this->mod_bank_detail->getAllBankInfo();
        $data['transaction_status_info'] = $this->mod_transaction_status_info->getAllStatus();
        $data['title'] = 'Client Transaction Info.';
        $data['container'] = 'accounts/account_view';
        $this->load->view('main_page', $data);
    }
    
    public function get_company_info() {
        $comid = $this->input->post('ID');
        $Result = $this->mod_client_detail->get_company_info_by_id($comid);
        echo json_encode($Result);
    }
    
    public function set_post_data() {
        $this->mod_clients_account->ClientName = $this->input->post('ClientName');
        $this->mod_clients_account->BankName = $this->input->post('BankName');
        $this->mod_clients_account->Reference = $this->input->post('Reference');
        $this->mod_clients_account->Amount = $this->input->post('Amount');
        $this->mod_clients_account->Status = $this->input->post('Status');
        $this->mod_clients_account->Date = $this->input->post('Date');
        $this->mod_clients_account->TransactionCharge = $this->input->post('TransactionCharge');
        $this->mod_clients_account->ConversionCharge = $this->input->post('ConversionCharge');
        $this->mod_clients_account->insert();
        $info = array("message" => "success");
        echo json_encode($info);
    }
    
    public function edit() {
        $this->mod_clients_account->ID = $this->input->post('ID');
        $value = $this->mod_clients_account->getTransactionInfoByID();
        echo json_encode($value);
    }
    
    public function update_transactions() {
        $this->mod_clients_account->ID = $this->input->post('ID');
        $this->mod_clients_account->ClientName = $this->input->post('ClientName');
        $this->mod_clients_account->BankName = $this->input->post('BankName');
        $this->mod_clients_account->Reference = $this->input->post('Reference');
        $this->mod_clients_account->Amount = $this->input->post('Amount');
        $this->mod_clients_account->Status = $this->input->post('Status');
        $this->mod_clients_account->Date = $this->input->post('Date');
        $this->mod_clients_account->TransactionCharge = $this->input->post('TransactionCharge');
        $this->mod_clients_account->ConversionCharge = $this->input->post('ConversionCharge');
        $this->mod_clients_account->update();
        $info = array("message" => "success");
        echo json_encode($info);
    }
    
    public function delete() {
        $this->mod_clients_account->ID = $this->input->post('ID');
        $this->mod_clients_account->deleteTransaction();
        $info = array("message" => "success");
        echo json_encode($info);
    }

}