<?php

class doctor_model extends CI_Model {

    public function getDoctor($name) {
        $this->db->where('upro_uacc_fk', $name);
        $query = $this->db->get('demo_user_profiles');
        return $query->row();
    }

    public function getDoctors() {
        $this->db->select('upro_uacc_fk as id,CONCAT(upro_first_name," ", upro_first_name) As name', FALSE);
        $query = $this->db->get('demo_user_profiles');
        return $query->result();
    }

    public function getAapp($date, $doctor_id, $week_day_num) {

        $this->db->select('meda_schedules.id,
                        meda_schedules.name,
                        meda_schedules.date_from,
                        meda_schedules.date_to,
                        meda_schedule_timeblocks.doctor_address_id,
                        meda_schedule_timeblocks.week_day,
                        meda_schedule_timeblocks.time_from,
                        meda_schedule_timeblocks.time_to,
                        meda_schedule_timeblocks.time_slots');
        $this->db->from('meda_schedules');
        $this->db->join('meda_schedule_timeblocks', 'meda_schedules.id = meda_schedule_timeblocks.schedule_id');
        $this->db->where('meda_schedules.date_from <=', $date);
        $this->db->where('meda_schedules.date_to >=', $date);
        $this->db->where('meda_schedules.doctor_id', $doctor_id);
        $this->db->where('meda_schedule_timeblocks.week_day', $week_day_num);
        $this->db->order_by('meda_schedules.id asc,meda_schedule_timeblocks.time_from asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function timeoff($date, $doctor_id) {
        $where = '(
		("' . $date . '" > date_from AND "' . $date . '" < date_to) OR
		("' . $date . '" = date_from AND "' . $date . '" <= date_to) OR 
		("' . $date . '" >= date_from AND "' . $date . '" = date_to)
		)';
        $this->db->select('id, doctor_id, date_from, time_from, date_to, time_to');
        $this->db->where($where);
        $this->db->where('doctor_id', $doctor_id);
        $this->db->from('meda_timeoffs');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getBookedtiemslot($date, $doctor_id) {
        $this->db->select('id, doctor_id, patient_id, appointment_date, appointment_time, visit_duration');
        $this->db->where('appointment_date', $date);
        $this->db->where('doctor_id', $doctor_id);
        $where = '(status = 0 OR status = 1)';
        $this->db->from('meda_appointments');
        $query = $this->db->get();
        return $query->result_array();
    }

}
