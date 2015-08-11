<?php namespace App\Http\Controllers; //กำหนดที่อยู่ ของ Controller ที่เรียกใช้งาน
use App\Models\History as History;// กำหนดชื่อ ของ Model จากที่อยู่ของ Model ที่เราเรียกใช้งาน

class HistoryController extends Controller {
	public function getIndex(){
		header('content-type:text/html; charset=utf-8');
		$history = History::get();
		return $history ? 'Model History Connect Yes!' : 'Error! Model History Connect False!!!';
	}
}
