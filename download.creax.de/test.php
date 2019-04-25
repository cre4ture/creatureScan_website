<?php

include "sys/templates.php";

$liste = array("list" => 
         array(1 => array("name" => "eintrag1"),
               2 => array("name" => "2ter eintrag"),
               3 => array("name" => "eintrag nr.3"))
        );
$tpldata = "list1:<br/>%%[]list%%<a>%%name%%</a><br/>%%list[]%%";
echo SimpleTemplate_a($tpldata, $liste);

?>

