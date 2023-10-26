<?php
$people = array(
array("name"=>"Bob","age"=>8,"colour"=>"red"),
array("name"=>"Greg","age"=>12,"colour"=>"blue"),
array("name"=>"Andy","age"=>5,"colour"=>"purple"));

var_dump($people);

$sortArray = array();

foreach($people as $person){
    foreach($person as $key=>$value){
        if(!isset($sortArray[$key])){
            $sortArray[$key] = array();
        }
        $sortArray[$key][] = $value;
    }
}

$orderby = "name"; //change this to whatever key you want from the array

array_multisort($sortArray[$orderby],SORT_DESC,$people);

var_dump($people);
?>