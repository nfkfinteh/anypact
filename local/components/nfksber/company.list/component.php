<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$items = GetIBlockElementList(4, 0, Array("SORT"=>"ASC"), 10);

print_r($items);

?>