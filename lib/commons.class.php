<?php

class Commons {
    static function isMenuClicked($currentForm, $menuForm) {
    	if ($currentForm==$menuForm) return "menuClicked";
    	return "";
    }
    
    static function date_format_sql($value) {
    	if ((! (strcmp($value,"0000-00-00")) ) || (! (strlen($value)) )) {
    		$value="0000-00-00";
    	} else {
    		list($month, $day, $year) = explode('/', $value);
    		$value=sprintf("%s-%s-%s",$year,$month,$day);
    	}
    	return $value;
    }
    
    static function date_format_form($value) {
    	if ((! (strcmp($value,"0000-00-00")) ) || (! (strlen($value)) )) {
    		$value="";
    	} else {
    		list($year, $month, $day) = explode('-', $value);
    		$value=sprintf("%s/%s/%s",$month,$day,$year);
    		return $value;
    	}
    }
    
    static function money_format($value) {
    	return number_format($value, 2, '.', ',');
    }
}
?>