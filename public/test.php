<?php

$fruits = array();//array("1.545_1231"=>"lemon", "1.345_1231"=>"orange", "b"=>"banana", "c"=>"apple");
//for ($i = 0 ; $i < 10; $i++){
    $fruits["0.03_500_1556"] = "test";
    $fruits["0.01_500_1555"] = "test";
    $fruits["0.1_500_1549"] = "test";
    $fruits["0.06_500_1548"] = "test";
    $fruits["0.04_200_1550"] = "test";
    $fruits["0.02_300_1545"] = "test";
    $fruits["0.01_500_1544"] = "test";
//    $diff . "_" . $sellVolume . "_" . $sellSide->getId()
//}

krsort($fruits);
foreach ($fruits as $key => $val) {
    echo "$key = $val</br>";
}
?>