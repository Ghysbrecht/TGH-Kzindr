<?php

namespace Ghysbrecht\Checkmein\Models;


class Checkin
{
    public $id;
    public $start_time;
    public $end_time;
    public $user_id;
    public $message;
    public $api_info;

    private $defaut_time = '+1 hours';

    public function __construct(\PDO $db = null)
    {
        $this->db = $db;
    }

    public function create(Array $values, $user_id)
    {
        if(isset($values['add_time'])){
            $this->defaut_time = $values['add_time'];
        }
        if(isset($values['start_time'])){
            $this->start_time = $values['start_time'];
        } else {
            $this->start_time = date('Y-m-d H:i:s');
        }
        if(isset($values['end_time'])){
            $this->start_time = $values['end_time'];
        } else {
            $this->end_time = date('Y-m-d H:i:s', strtotime($this->defaut_time));
        }
        $this->message = $values['message'];
        $this->api_info = $values['api_info'];
        $this->user_id = $user_id;
        return $this;
    }

    public function save()
    {
        $query = "INSERT INTO check_in (start_time, end_time, user_id, message, api_info) VALUES (:start_time, :end_time, :user_id, :message, :api_info)";
        $statement = $this->db->prepare($query);
        $statement->execute([
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'user_id' => $this->user_id,
            'message' => $this->message,
            'api_info' => $this->api_info
        ]);
        $this->id = $this->db->lastInsertId();
    }

    public function getStartTime(){
        return $this->start_time;
    }

    public function getEndTime(){
        return $this->end_time;
    }
    public function getMessage(){
        return $this->message;
    }


}
