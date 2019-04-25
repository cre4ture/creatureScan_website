<?php
  /* Unit >templates<: functions to process template files */
  
function SimpleArrayApply(&$tpldata, $arraylist, $seperator = '%%')
{
  $regex_start = "/%%\[\]([a-zA-Z0-9_]+)%%/";
  
  $result = "";
  
  $matches = array();
  $ret = preg_match($regex_start, $tpldata, $matches, PREG_OFFSET_CAPTURE);
  while ($ret)
  {
    $listname = $matches[1][0];
    echo "listname: $listname <br/>";
    $result .= substr($tpldata, 0, $matches[0][1]);
    $tpldata = substr($tpldata, $matches[0][1] + strlen($matches[0][0]));
    
    $end = '%%'.$listname.'[]%%';
    $pos = strpos($tpldata, $end);
    $listtext = substr($tpldata, 0, $pos); 
    foreach ($arraylist[$listname] as $item => $value)
    {
      $result .= SimpleTemplate_a($listtext, $value, $seperator);
    }
    $tpldata = substr($tpldata, $pos + strlen($end));
    
    $matches = array();
    $ret = preg_match($regex_start, $tpldata, $matches, PREG_OFFSET_CAPTURE);
  }
  
  $result .= $tpldata;
  return $result;
}
  
function SimpleTemplate_a($tpldata, $valuelist, $seperator = '%%')
{
  $tpldata = SimpleArrayApply($tpldata, $valuelist, $seperator);

	$rvl = array();
	foreach ($valuelist as $key => $value)
	{
	  if (!is_array($value))
		  $rvl[$seperator.$key.$seperator] = $value;
	}
	
	$r = '';
	foreach ((array)str_replace(array_keys($rvl),$rvl,$tpldata) as $line)
    { $r .= $line; }
  
  return $r;
}

function SimpleTemplate($tpldata, $valuelist, $seperator = '%%')
{
	echo SimpleTemplate_a($tpldata, $valuelist, $seperator);
}

function SimpleSwitchApply($tpldata, $switches, $switchsep = '$$')
{
  $sp = explode($switchsep, $tpldata);
	$r = '';
	$on = true;
	$i = 0;
	while ($i < count($sp))
	{
	  if ($on) $r .= $sp[$i];
	  
	  $i++;
	  
		$cursw = explode(';',$sp[$i]);

		if ($on)
		{
			$on = $switches[$cursw[0]];
			if ($cursw[1] == 'not') $on = !$on;
			
			$last = $sp[$i];
		} else
		{
		  $on = ($sp[$i] == $last);
		}
		
    $i++;
	}
	return $r;
}
  
class template
{
	var $raw; //"rohdaten"
	var $file; //array: [0]=text [1]=%var% [2]=text [3]=%var% ...
	var $go_position;
	var $autofill = array();
	var $switches = array();
	var $seperator = '%%';
	var $switchsep = '$$';

	function clearautofill ()
	{
		$this->autofill = array();
	}

	function addautofill ($list)
	{
		$this->autofill = array_merge($this->autofill,$list);
	}
	
	function addswitches ($list)
	{
		$this->switches = array_merge($this->switches,$list);
	}
	
	function applyswitches()
	{
    $this->raw = SimpleSwitchApply($this->raw, $this->switches, $this->switchsep);
	}
 
	function loadtemplate($filename)
	{
  	$this->loadtemplatetext(file_get_contents($filename));
	}
	
	function loadtemplatetext($text)
	{
		$this->raw = $text;
		if (count($this->switches) > 0)	$this->applyswitches();
		$this->file = explode($this->seperator, $this->raw);
	}

	function go_start ()
	{
		$this->go_position = -1;
	}
	
	function get_current ()
	{
		if (($this->go_position >= 0)and($this->go_position < count($this->file)))
		{
			return $this->file[$this->go_position];
		} else {
			return false;
		}
	}
 
	function go_next ()
	{
		$this->go_position++;
		echo $this->get_current();

		$this->go_position++;
		$current = $this->get_current();
		if ($current and isset($this->autofill[$current]))
		{
			echo $this->autofill[$current];
			return $this->go_next();
		}
		else return $current;
	}
}  
?>
