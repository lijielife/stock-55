<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DataLog extends Model{
	protected $table = 'data_log';
        
        protected $fillable = array('ID', 'SIDE_ID', 'SYMBOL_ID', 'VOLUME', 'PRICE', 'AMOUNT', 'VAT', 'at_pay'
            , 'NET_AMOUNT', 'DATE', 'BROKER_ID', 'CREATED_AT', 'CREATED_BY'
            , 'UPDATED_AT', 'UPDATED_BY', 'USER_ID');
    
}