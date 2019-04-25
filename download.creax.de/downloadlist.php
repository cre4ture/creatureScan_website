<?php

include_once("download.php");

$downloadclass = $_GET["class"];
if (!preg_match("/^[a-zA-Z0-9_]+$/", $downloadclass))
{
  $downloadclass = "";
}

?>
