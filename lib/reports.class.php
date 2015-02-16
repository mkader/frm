<?php

class Reports {
    private $db;
    private $conn;

    function __construct(&$db) {
    	$this->common = new Commons();
    	$this->select = new Selects($db);
        $this->db = $db;
        if (!$this->db) {
            $this->db = new Db();
        }

        $this->conn = $this->db->getConnection();
    }

    function getCompleteList($itax_year) {
    	 $sql = 'SELECT 
				    d.name, d.address1, d.address2, d.city, d.state, d.zipcode, 
    	 			d.phone, d.email, d.company_name, d.comments donator_comments,
					p.payment_date ,p.amount, pm.payment_method, p.tax_year, 
    	 			p.comments payment_comments
				FROM 
					donator d 
					inner join payment p on d.id =p.donator_id
					inner join payment_method pm on p.payment_method_id = pm.id
				WHERE
    	 			p.tax_year = ?
    	 		ORDER BY
					d.name, p.payment_date';
        $stmt = $this->conn->prepare($sql);
        $this->db->checkError();
        $stmt->bind_param('i', $itax_year);
        $this->db->checkError();
        $stmt->execute();
        $this->db->checkError();

        $reports = array();
        $stmt->bind_result($name, $address1, $address2, $city, $state, $zipcode, 
        			$phone, $email, $company_name, $donator_comments, $payment_date,
        			$amount, $payment_method, $tax_year, $payment_comments);
        while ($stmt->fetch()) {
            $reports[] = array('name' => $name, 'address1' => $address1, 
            		'address2' => $address2, 'city' => $city, 'state' => $state, 
            		'zipcode' => $zipcode, 'phone' => $phone, 'email' => $email, 
            		'company_name' => $company_name, 
            		'donator_comments' => $donator_comments, 
            		'payment_date' => Commons::date_format_form($payment_date), 
            		'amount' => $amount, 
            		'payment_method' => $payment_method, 'tax_year' => $tax_year, 
            		'payment_comments' => $payment_comments);
        }
		$stmt->close();

        return $reports;
    }
    
    function getCompleteSum($itax_year) {
    	$sql = 'SELECT
				    d.name, d.address1, d.address2, d.city, d.state, d.zipcode,
    	 			d.phone, d.email, d.company_name, SUM(p.amount) amount
				FROM
					donator d
					inner join payment p on d.id =p.donator_id
				WHERE
    	 			p.tax_year = ?
    			GROUP BY
    				d.id
    	 		ORDER BY
					d.name, p.payment_date';
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->bind_param('i', $itax_year);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();
    
    	$reports = array();
    	$stmt->bind_result($name, $address1, $address2, $city, $state, $zipcode,
    			$phone, $email, $company_name, $amount);
    	while ($stmt->fetch()) {
    		$reports[] = array('name' => $name, 'address1' => $address1,
    				'address2' => $address2, 'city' => $city, 'state' => $state,
    				'zipcode' => $zipcode, 'phone' => $phone, 'email' => $email,
    				'company_name' => $company_name,
    				'amount' => $amount);
    	}
    	$stmt->close();
    
    	return $reports;
    }
    
    function getEventPaymentList($event_id) {
    	$sql = 'select 
					d.name, d.address1, d.address2, d.city, d.state, d.zipcode,
					d.phone, d.email, d.company_name, d.comments donator_comments,
					p.payment_date ,p.amount, pm.payment_method, p.tax_year,
					p.comments payment_comments
				from
					pledge pl
					inner join payment p  on pl.id  = p.pledge_id
					inner join donator d on  d.id = p.donator_id
					inner join payment_method pm on p.payment_method_id = pm.id
				where
					pl.event_id = ?
    	 		ORDER BY
					d.name, p.payment_date';
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->bind_param('i', $event_id);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();
    
    	$reports = array();
    	$stmt->bind_result($name, $address1, $address2, $city, $state, $zipcode,
    			$phone, $email, $company_name, $donator_comments, $payment_date,
    			$amount, $payment_method, $tax_year, $payment_comments);
    	while ($stmt->fetch()) {
    		$reports[] = array('name' => $name, 'address1' => $address1,
    				'address2' => $address2, 'city' => $city, 'state' => $state,
    				'zipcode' => $zipcode, 'phone' => $phone, 'email' => $email,
    				'company_name' => $company_name,
    				'donator_comments' => $donator_comments,
    				'payment_date' => Commons::date_format_form($payment_date),
    				'amount' => $amount,
    				'payment_method' => $payment_method, 'tax_year' => $tax_year,
    				'payment_comments' => $payment_comments);
    	}
    	$stmt->close();
    
    	return $reports;
    }
    
    function getEventSum($event_id) {
    	$sql = 'SELECT
				    d.name, d.address1, d.address2, d.city, d.state, d.zipcode,
    	 			d.phone, d.email, d.company_name, pl.amount pledgedamount, 
    				SUM(p.amount) paid
    			FROM
					pledge pl
					left join payment p  on pl.id  = p.pledge_id
					inner join donator d on  d.id = p.donator_id
				where
					pl.event_id = ?
    			GROUP BY
    				d.id
    	 		ORDER BY
					d.name, p.payment_date';
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->bind_param('i', $event_id);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();
    
    	$reports = array();
    	$stmt->bind_result($name, $address1, $address2, $city, $state, $zipcode,
    			$phone, $email, $company_name, $pledgedamount, $paid);
    	while ($stmt->fetch()) {
    		$reports[] = array('name' => $name, 'address1' => $address1,
    				'address2' => $address2, 'city' => $city, 'state' => $state,
    				'zipcode' => $zipcode, 'phone' => $phone, 'email' => $email,
    				'company_name' => $company_name,
    				'pledgedamount' => $pledgedamount,
    				'paid' => $paid);
    	}
    	$stmt->close();
    
    	return $reports;
    }
   
}

?>