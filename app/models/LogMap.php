<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LogMap extends Model{
	protected $table = 'log_map';
        
        protected $fillable = array('ID', 'MAP_SRC', 'MAP_DESC', 'MAP_VOL');
 
        
}