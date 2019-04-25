<?php

if (isset($_GET['file']))
  $file = $_GET['file'];
else $file = "";

if (preg_match("/^([a-zA-Z0-9_]+)(\.[a-zA-Z0-9_]+)?$/", $file) == 0)
  $file = "unvalid";
  
if (isset($_GET["class"]))
  $class = $_GET["class"];
else $class = ""; 

if (preg_match("/^[a-zA-Z0-9_]+$/", $class) == 0)
  $class = "unvalid";

include_once("download.php");
include_once("sys/objpsys/obj_list.php");
include_once("sys.php");

$page = new c_objhtmlpage('root');
$page->header->title = "download.creax.de";
$page->header->addstylesheet("download.css");
$page->body->addtextfile("logo.html");

$mainmenu = new c_objsimplelist($page->body);

ConnectDB();
  
if (($file != "unvalid")and($list = FindDownloads("","",$file,"")))
{
  {
    $objl = new c_objsimplelist($page->body);
    $objl->items_vars = $list;
    $objl->tpl_item = '<tr><td>%%name%%<td>'.
      '<td><a href="file.php?id=%%ID%%">%%filename%%</a><td>'.
      '<td>%%description%%<td><tr>';
  }
}
else
if (($class != "unvalid")and($clist = GetClassDownloads($class)))
{
  $page->header->title .= " - $class";
  $page->body->addsimpletext("<br><a>$class</a><br>");
  if (isset($clist[0]["infofile"])&&(file_exists($clist[0]["infofile"])))
  {
    $page->body->addtextfile($clist[0]["infofile"]);
    $page->body->addsimpletext("<br>");
  }
  
  $list = FindDownloads("",$clist[0]["ID"],"","");
  CreateDownloadList($list, $page->body);
}
else
{
  $list = FindDownloads("","","","");
  $page->body->addsimpletext("<br><a>Liste aller Downloads:</a>");

  CreateDownloadList($list, $page->body);
}

$itemtmpl = file_get_contents("tmpl/mainmenu_classitem.html");
$classlist = GetClasses();
$mainmenu->items_vars = $classlist;
$mainmenu->tpl_start = "<table><tr><td>\r<div class=\"mainmenu\">\r".
                       "  <a href=\"/\">alle</a>\r";
$mainmenu->tpl_end = "</div>\r</td></tr></table>";
$mainmenu->tpl_item = $itemtmpl;

DisconnectDB();

$page->objprint();

function CreateDownloadList($list, $owner)
{
  $c = count($list);
  if ($c > 0)
  {
    
    foreach ($list as $nr => &$item)
    {
      $item["klickcount"] = klickcount($item["ID"]);
      $item["timestr"] = date("d.m.Y H:i:s", $item["time"]);
    }
    
    $objl = new c_objsimplelist($owner);
    $objl->items_vars = $list;
    $objl->tpl_item = file_get_contents("tmpl/downloaditem.html");
    
  } else
  {
    $text = new c_objsimpletext($owner);
    $text->text = "Keine Downloads in dieser Kategorie";
  }
}

?>
