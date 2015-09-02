<?php namespace App\Beans; // การกำหนดที่อยู่ของ Bean

class DataLogBean {

  private $id;
  private $side_id;
  private $symbol_id;
  private $volume;
  private $price;
  private $amount;
  private $vat;
  private $net_amount;
  private $date;
  private $broker_id;
  private $is_dw;
  private $created_at;
  private $created_by;
  private $updated_at;
  private $updated_by;
  private $user_id; 
  private $logMap = array();

  function getId() {
      return $this->id;
  }

  function getSide_id() {
      return $this->side_id;
  }

  function getSymbol_id() {
      return $this->symbol_id;
  }

  function getVolume() {
      return $this->volume;
  }

  function getPrice() {
      return $this->price;
  }

  function getAmount() {
      return $this->amount;
  }

  function getVat() {
      return $this->vat;
  }

  function getNet_amount() {
      return $this->net_amount;
  }

  function getDate() {
      return $this->date;
  }

  function getBroker_id() {
      return $this->broker_id;
  }

  function getIs_dw() {
      return $this->is_dw;
  }

  function getCreated_at() {
      return $this->created_at;
  }

  function getCreated_by() {
      return $this->created_by;
  }

  function getUpdated_at() {
      return $this->updated_at;
  }

  function getUpdated_by() {
      return $this->updated_by;
  }

  function getUser_id() {
      return $this->user_id;
  }

  function setId($id) {
      $this->id = $id;
  }

  function setSide_id($side_id) {
      $this->side_id = $side_id;
  }

  function setSymbol_id($symbol_id) {
      $this->symbol_id = $symbol_id;
  }

  function setVolume($volume) {
      $this->volume = $volume;
  }

  function setPrice($price) {
      $this->price = $price;
  }

  function setAmount($amount) {
      $this->amount = $amount;
  }

  function setVat($vat) {
      $this->vat = $vat;
  }

  function setNet_amount($net_amount) {
      $this->net_amount = $net_amount;
  }

  function setDate($date) {
      $this->date = $date;
  }

  function setBroker_id($broker_id) {
      $this->broker_id = $broker_id;
  }

  function setIs_dw($is_dw) {
      $this->is_dw = $is_dw;
  }

  function setCreated_at($created_at) {
      $this->created_at = $created_at;
  }

  function setCreated_by($created_by) {
      $this->created_by = $created_by;
  }

  function setUpdated_at($updated_at) {
      $this->updated_at = $updated_at;
  }

  function setUpdated_by($updated_by) {
      $this->updated_by = $updated_by;
  }

  function setUser_id($user_id) {
      $this->user_id = $user_id;
  }

  function getLogMap() {
      return $this->logMap;
  }

  function setLogMap($logMap) {
      $this->logMap = $logMap;
  }

  function pullLogMap($key) {
      return array_pull($this->logMap, $key);
  }

  function pushLogMap($logMap) {
      array_push($this->logMap, $logMap);
  }
  
}
