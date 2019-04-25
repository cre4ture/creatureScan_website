<?php

if (isset($_GET['id']))
  $fileID = $_GET['id'];
else $fileID = "";

if ((!is_numeric($fileID))or($fileID <= 0))
{
  die();
}

include_once('download.php');

ConnectDB();
$list = FindDownloads("","","",$fileID);
if ($list)
{
  $item = $list[0];
}
DisconnectDB();

if(!$list)
{
  die("no such file");
}

//print_r($item);

$item["timestr"] = date("d.m.Y H:i:s", $item["time"]);
echo "<h1>".$item["name"]."</h1>";
echo "Datei: <b>".$item["filename"]."</b><br>";
echo "Datum: <b>".$item["timestr"]."</b><br>";
echo "Beschreibung: <br>".$item["description"];

?>
