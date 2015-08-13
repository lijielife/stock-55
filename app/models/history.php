<?php

namespace App\Models; // การกำหนดที่อยู่ของ Model

use Illuminate\Database\Eloquent\Model; // การเรียกใช้งาน Eloquent ใน laravel

class History extends Model {

    protected $table = 'history'; // กำหนดชื่อของตารางที่ต้องการเรียกใช้
    protected $fillable = array('ID', 'SYMBOL', 'RESOLUTION', 'TIME', 'OPEN', 'CLOSE', 'HIGH', 'LOW', 'VOLUME');

}
