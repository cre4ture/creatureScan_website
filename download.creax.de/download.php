<?php

include_once("sys.php");

$download_db = ""; 

function ConnectDB()
{
  global $download_db;
  $download_db = mysql_connect("localhost", "downloads", "password"); 
  if (!mysql_select_db("downloads", $download_db))
    throw("function ConnectDB(): Error selecting DB");
}

function DisconnectDB()
{
  global $download_db;
  mysql_close($download_db);
}

function FindDownloads($name, $class, $filename, $ID)
{
  global $download_db;
  
  $name = mysql_escape_string($name);
  $class = mysql_escape_string($class);
  $filename = mysql_escape_string($filename);
  $ID = mysql_escape_string($ID);
  
  $query = 'SELECT `files`.* FROM files WHERE (true';
  
  //only active downloads
  $query .= ' and(`files`.`active` >= 1)';
  
  if ($ID > 0)
    $query .=  ' and(`files`.`ID` ="'.$ID.'")';
  if ($name != "")
    $query .=  ' and(`files`.`name` ="'.$name.'")';
  if ($class != "")
    $query .= ' and(`files`.`class` ="'.$class.'")';
  if ($filename != "")
    $query .=  ' and(`files`.`filename` ="'.$filename.'")';
    
  $query .= ') ORDER BY `time` DESC;';
  
  //echo $query;

  $ret = mysql_query($query, $download_db);       
  $result = Array();
  while ($row = mysql_fetch_assoc($ret))
  {
    $result[] = $row;
  }
  mysql_free_result($ret);
  return $result;
}

function GetClasses()
{
  global $download_db;

  $query = 'SELECT `classes`.* FROM classes WHERE (true);';
  
  $ret = mysql_query($query, $download_db);
  $result = false;
  while ($row = mysql_fetch_assoc($ret))
  {
    $result[] = $row;
  }
  mysql_free_result($ret);
  return $result;
}

function GetClassDownloads($classname)
{
  global $download_db;

  $classname = mysql_escape_string($classname);
  $query = 'SELECT `classes`.* FROM classes WHERE (`Name` = "'.$classname.'");';
  
  $ret = mysql_query($query, $download_db);
  $result = false;
  while ($row = mysql_fetch_assoc($ret))
  {
    $result[] = $row;
  }
  mysql_free_result($ret);
  return $result;
}

function addklick($downloadID)
{
  global $download_db;
  
  if (!is_int($downloadID))
    throwexception("function AddKlick(ID: $downloadID): ID is not a number!");

  $system = mysql_escape_string($_SERVER["HTTP_USER_AGENT"]);
  $ip = mysql_escape_string($_SERVER["REMOTE_ADDR"]);
  $referer = mysql_escape_string($_SERVER["HTTP_REFERER"]);
  $hostname = mysql_escape_string(gethostbyaddr($ip));
  
  $query = "INSERT INTO `klicks` 
    ( `fileID` , `IP`, `hostname`, `time` , `referer` , `loginname` , `system` )
    VALUES ('$downloadID', '$ip', '$hostname', '".time()."', '$referer', '', '$system');";
    
  $ret = mysql_query($query, $download_db);
  if (!$ret)
    throw new Exception(mysql_error($download_db));
}

function klickcount($downloadID)
{
  global $download_db;

  if (!is_numeric($downloadID))
    throw new Exception
      ("function AddKlick(ID: $downloadID): ID is not a number!");

  $query = 'SELECT `ID` FROM `klicks` WHERE (`fileID`="'.$downloadID.'")';
  $ret = mysql_query($query, $download_db);
  if ($ret)
  {
    $klicks = mysql_num_rows($ret);
    mysql_free_result($ret);
    return $klicks;
  }
}

function getklicks($downloadID)
{
  global $download_db;

  if ($downloadID == "all")
  {
    $query = 'SELECT * FROM `klicks`;';
  }
  else
  {
    if (!is_numeric($downloadID))
      throw new Exception
        ("function getklicks(ID: $downloadID): ID is unvalid!");

    $query = 'SELECT * FROM `klicks` WHERE (`fileID`="'.$downloadID.'");';
  }
  $ret = mysql_query($query, $download_db);
  if ($ret)
  {
    $klicks = array();
    while ($row = mysql_fetch_assoc($ret))
    {
      $klicks[] = $row;
    }
    mysql_free_result($ret);
    return $klicks;
  }
}

?>
