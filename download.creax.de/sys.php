<?php

function throwException($msg, $type = "exception")
{
  $report = "Exception('$type'): $msg";
}

/*function throw($msg)
{
  throwException($msg);
}*/

include_once("./sys/templates.php");

function makelist_custom($headerdata, $starttemplate, $endtemplate,
  $itemdatalist, $itemtemplate)
{
  $r = SimpleTemplate_a($starttemplate, $headerdata);
  
  foreach ($itemdatalist as $nr => $itemdata)
  {
    $itemdata["_listindex"] = $nr;
    $r .= SimpleTemplate_a($itemtemplate, $itemdata);
  }
  
  $r .= SimpleTemplate_a($endtemplate, $headerdata);
  return $r;
}

function forward($url)
{
  header("Location: $url");
  exit();
}

?>
