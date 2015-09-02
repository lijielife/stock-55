<?php namespace App\Beans; // การกำหนดที่อยู่ของ Bean

class LogMapBean {
    
  private $id;
  private $map_src;
  private $map_desc;
  private $map_vol;
  private $dataLogDesc;
  
  function getId() {
      return $this->id;
  }

  function getMap_src() {
      return $this->map_src;
  }

  function getMap_desc() {
      return $this->map_desc;
  }

  function getMap_vol() {
      return $this->map_vol;
  }

  function setId($id) {
      $this->id = $id;
  }

  function setMap_src($map_src) {
      $this->map_src = $map_src;
  }

  function setMap_desc($map_desc) {
      $this->map_desc = $map_desc;
  }

  function setMap_vol($map_vol) {
      $this->map_vol = $map_vol;
  }

  function getDataLogDesc() {
      return $this->dataLogDesc;
  }

  function setDataLogDesc($dataLogDesc) {
      $this->dataLogDesc = $dataLogDesc;
  }


}