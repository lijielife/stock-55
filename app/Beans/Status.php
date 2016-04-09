<?php

namespace App\Beans; // การกำหนดที่อยู่ของ Model

class Status {

//    public $id;
    public $session_id;
    public $symbol;
    public $status_desc;
    public $create_date;
    public $create_time;

//    public function getId() {
//        return $this->id;
//    }
    public function getSession_id() {
        return $this->session_id;
    }

    public function getSymbol() {
        return $this->symbol;
    }

    public function setSession_id($session_id) {
        $this->session_id = $session_id;
    }

    public function setSymbol($symbol) {
        $this->symbol = $symbol;
    }

//    public function setId($id) {
//        $this->id = $id;
//    }

    public function setStatus_code($status_code) {
        $this->status_code = $status_code;
    }

    public function setStatus_desc($status_desc) {
        $this->status_desc = $status_desc;
    }
    public function getCreate_date() {
        return $this->create_date;
    }

    public function setCreate_date($create_date) {
        $this->create_date = $create_date;
    }
    public function getCreate_time() {
        return $this->create_time;
    }

    public function setCreate_time($create_time) {
        $this->create_time = $create_time;
    }


}
