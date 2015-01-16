<?php

class Sessions {

	function setSecurityCode($code) {
		$_SESSION['mcc_security_code']= $code;
    }

	function securityCode() {
		return $_SESSION['mcc_security_code'];
	}
	
    function setLoginUserInfo($userInfoData) {
        $_SESSION["mcc_user_info"]= $userInfoData;
    }

    function loginUserInfo() {
		return $_SESSION["mcc_user_info"];
    }

    function isValidSession(){
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

    function attributeValue($attributeName) {
    	if (Sessions::isValidSession()) {
    		$userinfo = Sessions::loginUserInfo();
    		return $userinfo[$attributeName];
    	} else {
    		return 'Your session is invalid. Please login again before trying to send your request.';
    	}
    }

    function loginUserName() {
    	return Sessions::attributeValue('name');
    }

    function loginUserID() {
    	return Sessions::attributeValue('id');
    }

    function loginUserPhone() {
    	return Sessions::attributeValue('phone');
    }

    function isLoginUserSuperAdmin() {
    	if (Sessions::attributeValue('user_type_id')==1) return true;
    	return false;
    }
    
}
?>