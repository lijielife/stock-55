<?php namespace App\Utils;

class SystemUtils{

    public static function getTableName($masSymbol, $origin = null) {

        $prefix = "";
        if ($origin !== null) {
            $prefix = ($origin == "ruayhoon" ? "RH" : "IN") . "_";
        }

        return "HISTORY_" . $prefix .
                str_replace("-", "", str_replace("&", "", str_replace("*", "", $masSymbol)));
    }
    
}