<?php

/**
 * Created by PhpStorm.
 * User: taposh
 * Date: 2015-06-15
 * Time: 1:10 PM
 */
class Patient extends CI_Controller
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
        $this->load->model('patient_model');
    }

    public function search_client_details_by_id()
    {
        $input = file_get_contents("php://input");
        $input = json_decode($input);
        $searchId = $this->patient_model->search_client_details_by_id($input);
        if ($searchId) {
            echo json_encode($searchId);
        } else {
            echo json_encode(1);
        }
    }

    public function LastVisitedDate()
    {
        $input = file_get_contents("php://input");
        $input = json_decode($input);
        $clientInfo = $this->patient_model->LastVisitedDate($input);
        if ($clientInfo) {
            echo json_encode($clientInfo);
        } else {
            echo json_encode(1);
        }

    }

    public function create_booking()
    {
        $input = file_get_contents("php://input");
        $input = json_decode($input);

        if (!(isset($input->client_gen_id))) {
            $clicentID = $this->create_patient($input);

        } else {
            $clicentID = $input->client_gen_id;
        }

        $this->patient_model->insertBooking($input,$clicentID);

        $msg = array("msg" => "Patient Id:" . $clicentID . " token number: " . $input->token, " booking complete");
        echo json_encode($msg);
    }

    private function create_patient($input)
    {
        $getId = $this->patient_model->create_patient($input);
        $clientId = date('m') . "-" . date("y") . "-" . $getId;
        $this->patient_model->update_patient($getId, $clientId);
        return $clientId;
    }

}