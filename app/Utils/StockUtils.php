<?php namespace App\Utils;

class StockUtils{

    public static function getIndex($cmv, $bmv) {
        return ($cmv / $bmv) * 100;
    }
    
    public static function getMarketValue($stocks) {
        if(!is_array($stocks)){
            $stocks = array((array)$stocks);
        }
        $marketValue = 0;
        foreach ($stocks as $stock) {
            $price = $stock["PRICE"];
            $vol = $stock["VOLUME"];
            $marketValue += $price * $vol;
        }
        return $marketValue;
    }
    
    public static function getMarketValueNew($cmvn, $cmvo, $bmvo) {
        return ($cmvn / $cmvo) * $bmvo;
    }
    
    public static function isCheckMVNew($cmvn, $cmvo, $bmvn, $bmvo) {
        return  ($cmvn / $bmvn) == ($cmvo / $bmvo);
    }
    
    
    public static function getMVBuy($cmvn, $bmvo, $netAmount) {
        return $bmvo * ($cmvn / ($cmvn - $netAmount));
    }
    
    public static function getMVSell($cmvn, $cmvo, $bmvo, $netAmount) {
        return $bmvo * (($cmvn - $netAmount) / $cmvo);
    }
    
}