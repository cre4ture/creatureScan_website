<?php

if (isset($_GET['id']))
  $fileID = $_GET['id'];
else $fileID = "";

include("sys.php");

if ((!is_numeric($fileID))or($fileID <= 0))
{
  forward("/");
}

include_once('download.php');

ConnectDB();
if ($list = FindDownloads("","","",$fileID))
{
  addklick($list[0]["ID"]);
  forward($list[0]["directory"].$list[0]["filename"]);
}

DisconnectDB();

?>
