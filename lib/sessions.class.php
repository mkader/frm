<?php

class Sessions {

	public static function setSecurityCode($code) {
		$_SESSION['mcc_security_code']= $code;
    }

	public static function securityCode() {
		return $_SESSION['mcc_security_code'];
	}
	
    public static function setLoginUserInfo($userInfoData) {
        $_SESSION["mcc_user_info"]= $userInfoData;
    }

    public static function loginUserInfo() {
		return $_SESSION["mcc_user_info"];
    }

    public static function  isValidSession(){
    	if (!isset($_SESSION["mcc_user_info"])) return false;
    	else return true;
    	/*if (!isset($_SESSION["mcc_user_info"])){
        	$response['error'] = 1;
        	$response['message'] = 'Your session is invalid. Please login again before trying to send your request.';
        	return $response;
    	} else {
    		return $_SESSION["mcc_user_info"];
    	}*/
    }

    public static function attributeValue($attributeName) {
    	if (Sessions::isValidSession()) {
    		$userinfo = Sessions::loginUserInfo();
    		return $userinfo[$attributeName];
    	} else {
    		return 'Your session is invalid. Please login again before trying to send your request.';
    	}
    }

    public static function loginName() {
    	return Sessions::attributeValue('name');
    }
    
    public static function loginUserName() {
    	return Sessions::attributeValue('username');
    }

    public static function loginUserID() {
    	return Sessions::attributeValue('id');
    }

    public static function loginUserPhone() {
    	return Sessions::attributeValue('phone');
    }

    public static function isLoginUserSuperAdmin() {
    	if (Sessions::attributeValue('user_type_id')==1) return true;
    	return false;
    }
    
}
?>