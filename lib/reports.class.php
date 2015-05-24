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

    /**
     *	list of donator payment by year.
     *	Returns an array of list
     *
     * @param	int	$itax_year.
     */
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

    /**
     *	sum of donator payment by year.
     *	Returns an array of list
     *
     * @param	int	$itax_year.
     */
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

    /**
     *	list of donator payment by event.
     *	Returns an array of list
     *
     * @param	int	$event_id.
     */
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

    /**
     *	sum of donator payment list by event.
     *	Returns an array of list
     *
     * @param	int	$event_id.
     */
    function getEventSum($event_id) {
    	$sql = 'SELECT
				    d.name, d.address1, d.address2, d.city, d.state, d.zipcode,
    	 			d.phone, d.email, d.company_name, pl.amount pledgedamount,
    				SUM(p.amount) paid
    			FROM
					pledge pl
					inner join donator d on  d.id = pl.donator_id
    				left join payment p  on pl.id  = p.pledge_id
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

    /**
     *	sum of payment method donator list.
     *	Returns an array of list
     *
     * @param	int	$payment_method_id
     * @param	int	$year.
     */
    function getPaymentMethodList($payment_method_id, $year) {
		$sql = 'select
					d.name, d.address1, d.address2, d.city, d.state, d.zipcode,
					d.phone, d.email, d.company_name, d.comments donator_comments,
					SUM(p.amount) amount
				from
					payment p
					inner join donator d on  d.id = p.donator_id
				where
					p.payment_method_id = ? && p.tax_year = ?
				group by
					 d.id
				ORDER BY
					d.name, p.payment_date';
		$stmt = $this->conn->prepare($sql);
		$this->db->checkError();
		$stmt->bind_param('ii', $payment_method_id, $year);
		$this->db->checkError();
		$stmt->execute();
		$this->db->checkError();

		$reports = array();
		$stmt->bind_result($name, $address1, $address2, $city, $state, $zipcode,
				$phone, $email, $company_name, $donator_comments, $amount);
		while ($stmt->fetch()) {
			$reports[] = array('name' => $name, 'address1' => $address1,
					'address2' => $address2, 'city' => $city, 'state' => $state,
					'zipcode' => $zipcode, 'phone' => $phone, 'email' => $email,
					'company_name' => $company_name,
					'donator_comments' => $donator_comments,
					'amount' => $amount);
		}
		$stmt->close();

		return $reports;
    }

    /**
     *	sum of donator payment list.
     *	Returns an array of list
     *
     *	@param	int	$year.
     */
    function getPaymentMethodSum($year) {
		$sql = 'select
					pm.payment_method, sum(p.amount) amount
				from
					payment p
					inner join donator d on  d.id = p.donator_id
					inner join payment_method pm on pm.id = p.payment_method_id
				where
					p.tax_year = ?
				group by
					 pm.id';
		$stmt = $this->conn->prepare($sql);
		$this->db->checkError();
		$stmt->bind_param('i',$year);
		$this->db->checkError();
		$stmt->execute();
		$this->db->checkError();

		$reports = array();
		$stmt->bind_result($payment_method, $amount);
		while ($stmt->fetch()) {
			$reports[] = array('payment_method' => $payment_method,
					'amount' => $amount);
		}
		$stmt->close();

		return $reports;
    }

    /**
     *	list of pledged reminder donator.
     *	Returns an array of list
     *
     *	@param	int	$event_id.
     */
    function getReminderList($event_id) {
		$sql = 'SELECT
					d.name, d.address1, d.address2, d.city, d.state, d.zipcode,
					d.phone, d.email, d.company_name, pl.amount pledgedamount,
					IF(SUM(p.amount) IS NULL,0,SUM(p.amount)) paid,
					e.title event_title, e.event_date
				FROM
					pledge pl
					inner join event e on pl.event_id = e.id
					inner join donator d on  d.id = pl.donator_id
					left join payment p  on pl.id  = p.pledge_id
				where
					pl.event_id = ?
				GROUP BY
					d.id
				having
					/*pl.amount>100 and 
					(pl.amount - IF(SUM(p.amount) IS NULL,0,SUM(p.amount)))>100*/
					(pl.amount - IF(SUM(p.amount) IS NULL,0,SUM(p.amount)))>0
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
				$phone, $email, $company_name, $pledgedamount, $paid, $event_title, $event_date);
		while ($stmt->fetch()) {
			$reports[] = array('name' => $name, 'address1' => $address1,
					'address2' => $address2, 'city' => $city, 'state' => $state,
					'zipcode' => $zipcode, 'phone' => $phone, 'email' => $email,
					'company_name' => $company_name,
					'pledgedamount' => $pledgedamount,
					'paid' => $paid, 'event_title' => $event_title, 'event_date' => $event_date);
		}
		$stmt->close();

		return $reports;
    }
}

?>