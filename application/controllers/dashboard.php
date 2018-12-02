<?php

/**
 * Created by PhpStorm.
 * User: taposh
 * Date: 2015-06-01
 * Time: 4:14 PM
 */
class Dashboard extends CI_Controller
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
        $this->load->model('mod_daily_dashoard_report');
        $this->load->model('doctor_model');
    }

    public function index()
    {
        if($this->flexi_auth->get_user_group_id()==4){
            $this->data['todays_report'] = $this->mod_daily_dashoard_report->Get_dashboard_doctor();

        }else{
            $this->data['todays_report'] = $this->mod_daily_dashoard_report->get_dashboard_report_admin();
        }

        $date = new DateTime();
        $todays_info ="";
        $last_seven_days = "";
        $next_days = "";

        foreach ($this->data['todays_report'] as $areport) {
            $nowDate = new DateTime($areport->date);

            if($nowDate->format('Y-m-d')<$date->format('Y-m-d')){
                $last_seven_days[$areport->date] = $areport;
            }elseif($nowDate->format('Y-m-d')>$date->format('Y-m-d')){
                $next_days[$areport->date] = $areport;
            }else{
                $todays_info = $areport;
            }
        }
        $this->data['dashboard_report_next'] = $next_days;
        $this->data['dashboard_report_last'] = $last_seven_days;
        $this->data['today_report'] = $todays_info;
        $this->data['title'] = 'Dashboard';
        $this->data['container'] = 'dashboard/abc';
        $this->load->view('main_page', $this->data);
    }

    /*public function search()
    {
        if($this->flexi_auth->get_user_group() == 'Doctor'){
            redirect('dashboard/drowSearchResult');
        }
        if($this->flexi_auth->get_user_group() == 'Master Admin' && $this->flexi_auth->get_user_group() == 'nurse'){
            redirect('dashboard/get_all_doctors');
        }else{
            redirect('dashboard/get_all_doctors');
        }
    }

    public function get_all_doctors(){
        $sql_select = array('*');
        $sql_where = 'user_groups.ugrp_id = 4';
        $this->data['doctorList'] = $this->flexi_auth->get_users_group($sql_select, $sql_where)->result();
        $this->data['title'] = 'Search Schedule';
        $this->data['container'] = 'dashboard/search_result';
        $this->load->view('main_page', $this->data);
    }*/

    public function drawSearchResult()
    {
        if($this->flexi_auth->get_user_group() == 'Doctor'){
            $doctorId = $this->flexi_auth->get_user_id();
            $this->setSearchResult($doctorId);
            $this->data['title'] = 'Search Schedule';
            $this->data['container'] = 'dashboard/search_result';
            $this->load->view('main_page', $this->data);
        }
        elseif($this->flexi_auth->get_user_group() == 'Master Admin' || $this->flexi_auth->get_user_group() == 'nurse'){
            $doctorId = $this->input->post('doctorId');
            if($doctorId){
                $this->setSearchResult($doctorId);
                $this->data['title'] = 'Search Schedule';
                $this->data['container'] = 'dashboard/search_result';
                $this->load->view('main_page', $this->data);
            }
            else{
                $sql_select = array('*');
                $sql_where = 'user_groups.ugrp_id = 4';
                $this->data['doctorList'] = $this->flexi_auth->get_users_group($sql_select, $sql_where)->result();
                $this->data['title'] = 'Search Schedule';
                $this->data['container'] = 'dashboard/search_result';
                $this->load->view('main_page', $this->data);
            }
        }
        else{
            echo 'error';
        }
    }

    private function setSearchResult($userId){
        $result = $this->doctor_model->getDoctor($userId);
        $this->data["dccId"] = $userId;
        $myResult = $result;
        $startDate = date('Y-m-d');
        $current_day = strtotime($startDate);
        $arr_dates = array();
        for ($i = 0; $i < 10; $i++) {
            $current_date = ($i == 0) ? $current_day : strtotime('+' . $i . ' day', $current_day);
            $arr_dates[] = array(
                'date' => date('Y-m-d', $current_date),
                'date_view' => date('M d,Y', $current_date),
                'week_day_num' => (date('w', $current_date) + 1),
                'week_day' => $this->get_weekday_local(date('w', $current_date) + 1)
            );
        }
        $output = '<thead><tr>';
        $days_count = 0;
        foreach ($arr_dates as $dkey => $dval) {
            $background_color = ($days_count++ % 2 == 0) ? '#f9f4ed' : '#ffffff';
            $output .= '<th style="background-color:' . $background_color . '">' . $dval['week_day'] . '<br>' . $dval['date_view'] . '</th>';
        }

        $output .= '</tr>';
        $output .= '</thead>';


        $output .= '<tbody><tr>';
        $days_count = 0;
        foreach ($arr_dates as $dkey => $dval) {
            $background_color = ($days_count++ % 2 == 0) ? '#f9f4ed' : '#ffffff';
            $time_slots = $this->GetTimeSlotsForDay($result->upro_uacc_fk, $dval['date'], $dval['week_day_num']);
            $slots = 0;
            $output .= '<td style="width:50px;background-color:' . $background_color . '" align="center" valign="top">';
            $token = 0;
            foreach ($time_slots as $ts_key => $ts_val) {
                $token++;
                if ($ts_val['status'] == 0) {
                    $output .= '<strong style="color:red;">' . $ts_val['time_view'] . "</strong><br>";
                } else {


                    $param = 'docid=' . $myResult->upro_uacc_fk . '&date=' . $dval['date'] . '&start_time=' . $ts_val['time_real'] . '&duration=' . $ts_val['duration'] . '&tokon=' . $token;
                    $mmmm = urlencode($param);
                    $output .= '<a href="' . base_url() . 'dashboard/booking/' . $mmmm . '">' . $ts_val['time_view'] . "</a><br>";
                }
            }

            $output .= '</td>';
        }
        $output .= '<td width="12px" nowrap></td>';
        $output .= '</tr></tbody>';
        $this->data['output'] = $output;
        $this->data['doctorInfo'] = $myResult;
        $sql_select = array('*');
        $sql_where = 'user_groups.ugrp_id = 4';
        $this->data['doctorList'] = $this->flexi_auth->get_users_group($sql_select, $sql_where)->result();
    }

    private function get_weekday_local($wday)
    {
        $weekdays = array(
            '1' => 'SUNDAY',
            '2' => 'MONDAY',
            '3' => 'TUESDAY',
            '4' => 'WEDNESDAY',
            '5' => 'THURSDAY',
            '6' => 'FRIDAY',
            '7' => 'SATURDAY'
        );
        return $weekdays[$wday];
    }

    private function GetTimeSlotsForDay($doctor_id, $date, $week_day_num)
    {
        $result = array();
        $today = date('Y-m-d');

        $time_format = 'g:i';
        $minimum_time_slots = 0;
        // prepare real timeslots

        $res = $this->doctor_model->getAapp($date, $doctor_id, $week_day_num);

        for ($i = 0; $i < count($res); $i++) {
            $time_slot = (int)$res[$i]['time_slots'];
            $schedile_id = $res[$i]['id'];
            $doctor_address_id = $res[$i]['doctor_address_id'];
            $start_time = strtotime($res[$i]['time_from']);

            if ($date == $today) {
                $actual_time = date('H:i:s', strtotime('+' . ($time_slot * $minimum_time_slots) . ' minute'));
            } else {
                $actual_time = $res[$i]['time_from'];
            }

            $current_time = $res[$i]['time_from'];
            $end_time = $res[$i]['time_to'];
            $counter = 0;
            while ($current_time < $end_time) {
                $current_time_shift = strtotime('+' . ($counter * $time_slot) . ' minute', $start_time);
                $current_time = date('H:i:s', $current_time_shift);
                $current_time_1 = date('H-i', $current_time_shift);
                $current_time_2 = date('g:i a', $current_time_shift);

                $counter++;
                if ($counter > 300)
                    break;

                if ($current_time < $actual_time) {
                    continue;
                } else if ($current_time < $end_time) {
                    $result[] = array('date' => $date, 'schedule_id' => $schedile_id, 'doctor_address_id' => $doctor_address_id, 'time_real' => $current_time, 'time' => $current_time_1, 'time_view' => $current_time_2, 'duration' => $time_slot, 'status' => 1);
                }
            }
        }
        $result_total = count($result);
        $res = $this->doctor_model->getBookedtiemslot($date, $doctor_id);
        if (count($res) > 0) {
            for ($i = 0; $i < count($res); $i++) {
                for ($j = 0; $j < $result_total; $j++) {
                    $time_real = isset($result[$j]['time_real']) ? $result[$j]['time_real'] : false;
                    if ($time_real == $res[$i]['appointment_time']) {
                        $result[$j]['status'] = 0;
                        break;
                    }
                }
            }
        }
        $result_total = count($result);

        $res = $this->doctor_model->timeoff($date, $doctor_id);
        if (count($res) > 0) {

            if ($date == $res[0]['date_from'] && $date == $res[0]['date_to']) {
                // this day doctor works partially
                for ($j = 0; $j < $result_total; $j++) {
                    $time_real = isset($result[$j]['time_real']) ? $result[$j]['time_real'] : false;
                    if ($time_real >= $res[0]['time_from'] && $time_real < $res[0]['time_to']) {
                        unset($result[$j]);
                    }
                }
            } else if ($date == $res[0]['date_to']) {
                // this day doctor works partially
                for ($j = 0; $j < $result_total; $j++) {
                    $time_real = isset($result[$j]['time_real']) ? $result[$j]['time_real'] : false;
                    if ($time_real < $res[0]['time_to']) {
                        unset($result[$j]);
                    }
                }
            } else if ($date == $res[0]['date_from']) {
                // this day doctor works partially
                for ($j = 0; $j < $result_total; $j++) {
                    $time_real = isset($result[$j]['time_real']) ? $result[$j]['time_real'] : false;
                    if ($time_real >= $res[0]['time_from']) {
                        unset($result[$j]);
                    }
                }
            } else {
                // this day doctor doesn't work at all
                $result = array();
            }
        }
        return $result;
    }

    public function booking()
    {
        $ee = $this->uri->segment(3);
        $plaintext_string = urldecode($ee);
        parse_str($plaintext_string, $output);
        $this->data['doctors'] = $this->doctor_model->getDoctor($output['docid']);
        /*        echo '<pre>';
                print_r($data['doctors']);
                echo '</pre>';
                exit();*/
        $this->data['output'] = $output;
        $this->data['title'] = 'Booking';
        $this->data['container'] = 'dashboard/booking';
        $this->load->view('main_page', $this->data);
    }


}