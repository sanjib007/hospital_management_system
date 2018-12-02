<?php

class Schedule_model extends  CI_Model{

    public function get_schedule_list() {
        $this->db->select('meda_schedules.id as id, meda_schedules.doctor_id, demo_user_profiles.upro_first_name as docName, meda_schedules.date_from as date1, meda_schedules.date_to as date2');
        $this->db->from('meda_schedules');
        $this->db->join('demo_user_profiles', 'meda_schedules.doctor_id = demo_user_profiles.upro_uacc_fk', 'INNER');
        $this->db->order_by('demo_user_profiles.upro_first_name');
        $query = $this->db->get();
        return $query->result();
    }

    public function insertSchedule($info){
        $data = array(
            'doctor_id' => $info->doctorID ,
            'date_from' => $info->fromDate ,
            'date_to' => $info->toDate
        );

        $this->db->insert('meda_schedules', $data);
        return ($this->db->affected_rows() != 1) ? false : $this->db->insert_id();
    }
    public function deleteSchedule($info){
        $this->db->delete('meda_schedules', array('id' => $info->id));
    }

    public function updateSchedule($info){
        $data = array(
            'date_from' => $info->fromtime ,
            'date_to' => $info->totime
        );
        $this->db->where('id', $info->id);
        $this->db->update('meda_schedules', $data);
    }

    public function GetTimeBlock($info){
        $this->db->where('schedule_id', $info->id);
        $query = $this->db->get('meda_schedule_timeblocks');
        return $query->result();
    }

    public function InsertTimeBlock($info){
        $data = array(
            'schedule_id' => $info->scheduleid ,
            'week_day' => $info->day ,
            'time_from' => $info->fromtime,
            'time_to' => $info->totime ,
            'time_slots' => $info->slot
        );

        $this->db->insert('meda_schedule_timeblocks', $data);
        return ($this->db->affected_rows() != 1) ? false : $this->db->insert_id();
    }

    public function deleteTimeBlock($info){
        $this->db->delete('meda_schedule_timeblocks', array('id' => $info->id));
    }


    public function updateTimeBlock($info){
        $data = array(
            'week_day' => $info->day ,
            'time_from' => $info->fromtime,
            'time_to' => $info->totime ,
            'time_slots' => $info->slot
        );
        $this->db->where('id', $info->id);
        $this->db->update('meda_schedule_timeblocks', $data);
    }


}