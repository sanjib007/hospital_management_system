<?php

/**
 * Created by PhpStorm.
 * User: taposh
 * Date: 2015-06-28
 * Time: 12:40 PM
 */
class clinic_model extends CI_Model
{
    public function getallInfo()
    {
        $query = $this->db->get('setup');
        return $query->row();
    }

    public function insertDate($info)
    {
        $data = array(
            'name' => $info['name'],
            'address' => $info['address'],
            'phone' => $info['phone'],
            'mobile' => $info['mobile']
        );

        if (!file_exists($_FILES['userfile']['tmp_name']) || !is_uploaded_file($_FILES['userfile']['tmp_name'])) {

        } else {
            if ($this->now_upload('userfile')) {
                $profile_data['photo'] = $this->upload_data['file_name'];
                $imageInfo = realpath(APPPATH . '../img/');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_data['full_path'];
                $config['new_image'] = $imageInfo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 177;
                $config['height'] = 54;

                $this->load->library('image_lib', $config);

                if (!$this->image_lib->resize()) {
                    $error = array('error' => $this->upload->display_errors());

                    $this->load->view('clinic/setup/' . $this->uri->segment(3), $error);
                } else {
                    $data['image'] = $this->upload_data['file_name'];
                }
            }
        }

        $this->db->where('id', $info['id']);
        $this->db->update('setup', $data);
    }

    private function now_upload($photo) {
        $setConfig['upload_path'] = 'img/';
        $setConfig['allowed_types'] = 'BMP|GIF|JPG|PNG|JPEG|gif|jpg|png|jpeg|bmp';
        $setConfig['encrypt_name'] = TRUE;
        $setConfig['max_size'] = '';
        $setConfig['max_width'] = '';
        $setConfig['max_height'] = '';
        $this->load->library('upload');
        $this->upload->initialize($setConfig);
        if (!$this->upload->do_upload($photo)) {
            $info = $this->upload->display_errors("<p style='color:#FF0000; font-weight:bold;'>", "</p>");
            return FALSE;
        } else {
            $this->upload_data = $this->upload->data();
            return TRUE;
        }
    }

    public function getSchedule($docID)
    {
        $query = $this->db->query("SELECT med.week_day,med.time_from,med.time_to FROM `meda_schedule_timeblocks` as med join meda_schedules as t on t.id = med.schedule_id where t.doctor_id =".$docID);
        $query = $query->result();
        return $query;
    }
}