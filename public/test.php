<?php
$str = 'In My Cart : 11 12 items';
preg_match_all('!\d+!', $str, $matches);
print_r($matches);