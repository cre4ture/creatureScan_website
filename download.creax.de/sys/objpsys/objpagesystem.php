<?php

class c_obj
{
  public $owner;
  public $childs = array();
  
  function __construct($aowner)
  {
    $this->owner = $aowner;
    if (is_object($this->owner))
      $this->owner->sign_on($this);
  }

  function __destruct()
  {
    if (is_object($this->owner))
      $this->owner->sign_off($this);
  }
  
  function sign_on($child)
  {
    $this->childs[] = $child;
  }
  
  function sign_off($child)
  {
    $r = array_search ($child, $this->childs, true);
    if ($r)
    {
      unset($this->childs[$r]);
    }
  }
}

class c_objsimpletext extends c_obj
{
  public $text = "";
  
  function objprint()
  {
    echo $this->text;
  }
}

class c_objtextfile extends c_obj
{
  public $filename = "";
  
  function objprint()
  {
    echo file_get_contents($this->filename);
  }
}



class c_objcontainer extends c_obj
{
  function addsimpletext($text)
  {
    $st = new c_objsimpletext($this);
    $st->text = $text;
    return $st;
  }
  
  function addtextfile($filename)
  {
    $st = new c_objtextfile($this);
    $st->filename = $filename;
    return $st;
  }

  function objprint()
  {
    foreach ($this->childs as $cnr => $child)
    {
      $child->objprint();
    }
  }
}

class c_objhtmlheader extends c_objcontainer
{
  public $title = "";

  public function objprint()
  {
    echo "<head>\r".
         "  <title>$this->title</title>\r";
    
    parent::objprint();
    
    echo "</head>\r";
  }
  
  public function addstylesheet($link)
  {
    $this->addsimpletext(
      "  <link rel='stylesheet' type='text/css' href='$link'>\r");
  }
}

class c_objhtmlpage extends c_obj
{
  public $header = '';
  public $body = '';
  
  function __construct($aowner)
  {
    parent::__construct($aowner);
    $this->header = new c_objhtmlheader($this);
    $this->body = new c_objcontainer($this);
  }

  function __destruct()
  {
    unset($this->header);
    unset($this->body);   
    parent::__destruct();
  }
    
  public function objprint()
  {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"'."\r".
         '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'."\r".
         '<html>'."\r";
    
    $this->header->objprint();
    $this->body->objprint();
    
    echo "</html>\r";
  }
  
}

?>
