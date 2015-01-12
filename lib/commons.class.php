<?php

class Commons {
    function isMenuClicked($currentForm, $menuForm) {
    	if ($currentForm==$menuForm) return "menuClicked";
    	return "";
    }
    
    function date_format_sql($value) {
    	if ((! (strcmp($value,"0000-00-00")) ) || (! (strlen($value)) )) {
    		$value="0000-00-00";
    	} else {
    		list($month, $day, $year) = explode('/', $value);
    		$value=sprintf("%s-%s-%s",$year,$month,$day);
    	}
    	return $value;
    }
    
    function date_format_form($value) {
    	if ((! (strcmp($value,"0000-00-00")) ) || (! (strlen($value)) )) {
    		$value="";
    	} else {
    		list($year, $month, $day) = explode('-', $value);
    		$value=sprintf("%s/%s/%s",$month,$day,$year);
    		return $value;
    	}
    }
}
?>