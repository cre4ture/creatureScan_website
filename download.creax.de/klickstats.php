<?php

  include_once("download.php");
  include_once("sys/objpsys/obj_list.php");
  include_once("sys/objpsys/objpagesystem.php");
  include_once("sys.php");

  $root = new c_objhtmlpage(null);
  $root->header->title = "download.creax.de - Statistiken";
  $root->header->addstylesheet("download.css");
  $liste = new c_objsimplelist($root->body);
  $liste->tpl_item = "<tr><td>%%ID%%</td>".
                     "<td>%%fileID%%</td>".
                     "<td>%%IP%%</td>".
                     "<td>%%hostname%%</td>".
                     "<td>%%time%%</td>".
                     "<td>%%referer%%</td>".
                     "<td>%%loginname%%</td>".
                     "<td>%%system%%</td></tr>\n";


  ConnectDB();

  $klicks = getklicks("all");
  //print_r($klicks);
  $liste->items_vars = &$klicks;

  DisconnectDB();

  $root->objprint();

?>
