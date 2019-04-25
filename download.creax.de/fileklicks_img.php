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
if ($list = FindDownloads("","","",$fileID))
{
  $info = array();
  $info["klicks"] = klickcount($list[0]["ID"]);
}
DisconnectDB();

header("Content-type: image/gif");
$img = imagecreatetruecolor(100,18);
$col = imagecolorallocate($img, 254,254,254);
imagefill($img,0,0,$col);
imagecolortransparent($img, $col);
imagestring($img, 5, 0, 0, $info["klicks"], 0);
imagegif($img);

?>
