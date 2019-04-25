<?php

include_once("objpagesystem.php");
include_once("sys.php");

class c_objsimplelist extends c_obj
{
  public $tpl_start = "<table>\r";
  public $tpl_end   = "</table>\r";
  public $tpl_item  = "<tr><td>%%itemcontent%%</td></tr>\r";
  public $header_vars = array();
  public $items_vars = array();
  
  function objprint()
  {
    echo makelist_custom($this->header_vars, $this->tpl_start, $this->tpl_end,
      $this->items_vars, $this->tpl_item);
  }

}

?>
