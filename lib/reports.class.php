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
     *	list of enrollment.
     *	Returns an array of list
     *
     * @param	int	$iyear.
     */
    function getEnrollmentList($orderBy ="concat(father_name,'', mother_name) asc") {
    	$sql = "SELECT
			    father_name, father_cell, mother_name, mother_cell, 
    			address, city, zipcode, phone, language_primary, language_other,
			    emergency_contact1, emergency_phone1, emergency_relation1,
			    emergency_contact2, emergency_phone2, emergency_relation2,
			    physician_name, physician_phone, physician_address, emergency_hospital,
			    total_fee, financial_aid, 
			    se.comments, first_name, middle_name, last_name,
			    allergies_details, medical_conditions, gender,
			    dob, age, ss.comments scomments
		    FROM
			    school_enrollment se
			    inner join school_student ss on ss.enrollment_id =se.id
    		where
    			ss.active = 1
		    order by ". $orderBy;
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();
    
    	$enrollments = array();
    	$stmt->bind_result($father_name, $father_cell, $mother_name, $mother_cell, 
    			$address, $city, $zipcode, $phone, $language_primary, $language_other,
    			$emergency_contact1, $emergency_phone1, $emergency_relation1,
    			$emergency_contact2, $emergency_phone2, $emergency_relation2,
    			$physician_name, $physician_phone, $physician_address, $emergency_hospital,
    			$total_fee, $financial_aid, $comments, $first_name, $middle_name, $last_name,
			    $allergies_details, $medical_conditions, $gender, $dob, $age, $scomments);
    	while ($stmt->fetch()) {
    		$enrollments[] = array('father_name' => $father_name,
    				'father_cell' => $father_cell,
    				'mother_name' => $mother_name,
    				'mother_cell' => $mother_cell,
    				'address' => $address, 'city' => $city,
    				'zipcode' => $zipcode, 'phone' => $phone,
    				'language_primary' => $language_primary,
    				'language_other' => $language_other,
    				'emergency_contact1' => $emergency_contact1,
    				'emergency_phone1' => $emergency_phone1,
    				'emergency_relation1' => $emergency_relation1,
    				'emergency_contact2' => $emergency_contact2,
    				'emergency_phone2' => $emergency_phone2,
    				'emergency_relation2' => $emergency_relation2, 'comments' => $comments,
    				'physician_name' => $physician_name,
    				'physician_phone' => $physician_phone,
    				'physician_address' => $physician_address,
    				'emergency_hospital' => $emergency_hospital,
    				'total_fee' => $total_fee,
    				'financial_aid' => $financial_aid,
    				'first_name' => $first_name,
    				'middle_name' => $middle_name,
    				'last_name' => $last_name,
    				'allergies_details' => $allergies_details,
    				'medical_conditions' => $medical_conditions,
    				'gender' => $gender,
    				'dob' => $dob,
    				'age' => $age,
    				'scomments' => $scomments);
    	}
    	$stmt->close();
    
    	return $enrollments;
    }
    
    /**
     *	sum of school fees by school year starting from oct and next year sep.
     *	Returns an array of list
     *
     * @param	int	$iyear.
     */
    function getSchoolYearFeeSum($syear) {
    	$eyear = $syear+1;
    	$years = ($syear.','.$eyear);
    	$sdate = $syear.'-10-01';
    	$edate = $eyear.'-09-30';
    	$sql = 'SELECT
	    	1 as pos,
    		SUM(IF(MONTH(fee_date) = 10, amount, 0)) AS S10,
	    	SUM(IF(MONTH(fee_date) = 11, amount, 0)) AS S11,
	    	SUM(IF(MONTH(fee_date) = 12, amount, 0)) AS S12,
	    	SUM(IF(MONTH(fee_date) = 1, amount, 0)) AS E1,
	    	SUM(IF(MONTH(fee_date) = 2, amount, 0)) AS E2,
	    	SUM(IF(MONTH(fee_date) = 3, amount, 0)) AS E3,
	    	SUM(IF(MONTH(fee_date) = 4, amount, 0)) AS E4,
	    	SUM(IF(MONTH(fee_date) = 5, amount, 0)) AS E5,
	    	SUM(IF(MONTH(fee_date) = 6, amount, 0)) AS E6,
	    	SUM(IF(MONTH(fee_date) = 7, amount, 0)) AS E7,
	    	SUM(IF(MONTH(fee_date) = 8, amount, 0)) AS E8,
	    	SUM(IF(MONTH(fee_date) = 9, amount, 0)) AS E9,
    		SUM(amount) AS SE	
	    FROM
	    	school_fee
	    where
	    	fee_date>=? and fee_date <=?
    	union all
		SELECT
			2 as pos,
	    	SUM(IF(MONTH(payment_date) = 10, pa.amount, 0)) AS S10,
	    	SUM(IF(MONTH(payment_date) = 11, pa.amount, 0)) AS S11,
	    	SUM(IF(MONTH(payment_date) = 12, pa.amount, 0)) AS S12,
	    	SUM(IF(MONTH(payment_date) = 1, pa.amount, 0)) AS E1,
	    	SUM(IF(MONTH(payment_date) = 2, pa.amount, 0)) AS E2,
	    	SUM(IF(MONTH(payment_date) = 3, pa.amount, 0)) AS E3,
	    	SUM(IF(MONTH(payment_date) = 4, pa.amount, 0)) AS E4,
	    	SUM(IF(MONTH(payment_date) = 5, pa.amount, 0)) AS E5,
	    	SUM(IF(MONTH(payment_date) = 6, pa.amount, 0)) AS E6,
	    	SUM(IF(MONTH(payment_date) = 7, pa.amount, 0)) AS E7,
	    	SUM(IF(MONTH(payment_date) = 8, pa.amount, 0)) AS E8,
	    	SUM(IF(MONTH(payment_date) = 9, pa.amount, 0)) AS E9,
    		SUM(pa.amount) AS SE	
		from
			event e
			inner join pledge p on e.id = p.event_id
			inner join payment pa on p.id  = pa.pledge_id 
		where
			e.pledge_type_id = 6  and year(e.event_date)=?
		order by pos';
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->bind_param('ssi', $sdate, $edate, $syear);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();
    
    	$reports = array();
    	$stmt->bind_result($pos, $S10, $S11, $S12, $E1, $E2, $E3, $E4, $E5, $E6, $E7, $E8, $E9, $SE);
    	while ($stmt->fetch()) {
    		$reports[] = array('S10' => $S10, 'S11' => $S11, 'S12' => $S12, 
    							'E1' => $E1, 'E2' => $E2, 'E3' => $E3,
    							'E4' => $E4, 'E5' => $E5, 'E6' => $E6, 
    							'E7' => $E7, 'E8' => $E8, 'E9' => $E9, 'SE' => $SE);
    	}
    	$stmt->close();
    
    	return $reports;
    }
    
    /**
     *	fee not paid this month list.
     *	Returns an array of list
     *
     * @param	int	$iyear.
     */
    function getNotPaidThisMonth($syear, $month) {
    	$eyear = $syear+1;
    	$years = ($syear.','.$eyear);
    	$sdate = $syear.'-10-01';
    	$edate = $eyear.'-09-30';
    	$sql = "select 
					concat(father_name,' ', mother_name) parent_name,
    				father_name, father_cell, mother_name, mother_cell, 
    				concat(address,', ',city,' - ', zipcode) address_line, phone,
    			 	financial_aid, total_fee, 
    				(select count(ss.id) from school_student ss where se.id = ss.enrollment_id 
    				 and ss.active = 1)  no_of_student
				from 
					school_enrollment se
					left join school_fee sf on se.id = sf.enrollment_id
    					and fee_date>=? and fee_date <=? and month(fee_date) =?
				group by
					parent_name
    			having  
    				if(SUM(amount) is null,0,SUM(amount))=0";
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->bind_param('ssi', $sdate, $edate, $month);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();
    
    	$reports = array();
    	$stmt->bind_result($parent_name, $father_name, $father_cell, $mother_name, $mother_cell, 
    			$address_line, $phone, $financial_aid, $total_fee, $no_of_student);
    	while ($stmt->fetch()) {
    		$reports[] = array(
    				'parent_name' => $parent_name,
    				'father_name' => $father_name, 
    				'father_cell' => $father_cell,
    				'mother_name' => $mother_name,
    				'mother_cell' => $mother_cell,
    				'address_line' => $address_line,
    				'phone' => $phone,
    				'financial_aid' => $financial_aid,
    				'total_fee' => $total_fee,
    				'no_of_student' => $no_of_student);
    	}
    	$stmt->close();
    
    	return $reports;
    }
    
    /**
     *	sum of parent fees by school year starting from oct and next year sep.
     *	Returns an array of list
     *
     * @param	int	$iyear.
     */
    function getParentFeeSum($syear) {
    	$eyear = $syear+1;
    	$years = ($syear.','.$eyear);
    	$sdate = $syear.'-10-01';
    	$edate = $eyear.'-09-30';
    	$sql = "select 
					concat(father_name,'/',mother_name) parent_name, 
					concat(address,', ',city,' - ', zipcode) address_line, 
    			 	financial_aid, total_fee, 
    				(select count(ss.id) from school_student ss where se.id = ss.enrollment_id 
    				 and ss.active = 1)  no_of_student,
					SUM(IF(MONTH(fee_date) = 10, amount, 0)) AS S10,
			    	SUM(IF(MONTH(fee_date) = 11, amount, 0)) AS S11,
			    	SUM(IF(MONTH(fee_date) = 12, amount, 0)) AS S12,
			    	SUM(IF(MONTH(fee_date) = 1, amount, 0)) AS E1,
			    	SUM(IF(MONTH(fee_date) = 2, amount, 0)) AS E2,
			    	SUM(IF(MONTH(fee_date) = 3, amount, 0)) AS E3,
			    	SUM(IF(MONTH(fee_date) = 4, amount, 0)) AS E4,
			    	SUM(IF(MONTH(fee_date) = 5, amount, 0)) AS E5,
			    	SUM(IF(MONTH(fee_date) = 6, amount, 0)) AS E6,
			    	SUM(IF(MONTH(fee_date) = 7, amount, 0)) AS E7,
			    	SUM(IF(MONTH(fee_date) = 8, amount, 0)) AS E8,
			    	SUM(IF(MONTH(fee_date) = 9, amount, 0)) AS E9,
		    		SUM(amount) AS SE	
				from 
					school_enrollment se
					left join school_fee sf on se.id = sf.enrollment_id
    					and fee_date>=? and fee_date <=?
				group by
					parent_name";
    	$stmt = $this->conn->prepare($sql);
    	$this->db->checkError();
    	$stmt->bind_param('ss', $sdate, $edate);
    	$this->db->checkError();
    	$stmt->execute();
    	$this->db->checkError();
    
    	$reports = array();
    	$stmt->bind_result($parent_name, $address_line, $financial_aid, $total_fee, $no_of_student,
    			$S10, $S11, $S12, $E1, $E2, $E3, $E4, $E5, $E6, $E7, $E8, $E9, $SE);
    	while ($stmt->fetch()) {
    		$reports[] = array(
    				'parent_name' => $parent_name, 
    				'address_line' => $address_line, 
    				'financial_aid' => $financial_aid,
    				'total_fee' => $total_fee,
    				'no_of_student' => $no_of_student,
    				'S10' => $S10, 'S11' => $S11, 'S12' => $S12,
    				'E1' => $E1, 'E2' => $E2, 'E3' => $E3,
    				'E4' => $E4, 'E5' => $E5, 'E6' => $E6,
    				'E7' => $E7, 'E8' => $E8, 'E9' => $E9, 'SE' => $SE);
    	}
    	$stmt->close();
    
    	return $reports;
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
					p.payment_date ,p.amount, pm.payment_method,
    	 			p.comments payment_comments
				FROM
					donator d
					inner join payment p on d.id =p.donator_id
					inner join payment_method pm on p.payment_method_id = pm.id
				WHERE
    	 			year(p.payment_date) = ?
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
        			$amount, $payment_method, $payment_comments);
        while ($stmt->fetch()) {
            $reports[] = array('name' => $name, 'address1' => $address1,
            		'address2' => $address2, 'city' => $city, 'state' => $state,
            		'zipcode' => $zipcode, 'phone' => $phone, 'email' => $email,
            		'company_name' => $company_name,
            		'donator_comments' => $donator_comments,
            		'payment_date' => Commons::date_format_form($payment_date),
            		'amount' => $amount,
            		'payment_method' => $payment_method,
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
    	 			year(p.payment_date) = ?
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
					p.payment_date ,p.amount, pm.payment_method,
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
    			$amount, $payment_method, $payment_comments);
    	while ($stmt->fetch()) {
    		$reports[] = array('name' => $name, 'address1' => $address1,
    				'address2' => $address2, 'city' => $city, 'state' => $state,
    				'zipcode' => $zipcode, 'phone' => $phone, 'email' => $email,
    				'company_name' => $company_name,
    				'donator_comments' => $donator_comments,
    				'payment_date' => Commons::date_format_form($payment_date),
    				'amount' => $amount,
    				'payment_method' => $payment_method,
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
					p.payment_method_id = ? && year(p.payment_date) = ?
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
					year(p.payment_date) = ?
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
     *	list of sum pledged type by yealry.
     *	Returns an array of list
     *
     */
    function getYearlyPledgeTypeSumList() {
		$sql = "select
			year(pa.payment_date) year, sum(pa.amount) amount,
			sum(case when e.pledge_type_id is null then pa.amount else 0 end) no,
			sum(case when e.pledge_type_id = '1' then pa.amount else 0 end) operation,
			sum(case when e.pledge_type_id = '2' then pa.amount else 0 end) newmasjid,
			sum(case when e.pledge_type_id = '3' then pa.amount else 0 end) zakath,
			sum(case when e.pledge_type_id = '4' then pa.amount else 0 end) transportation,
			sum(case when e.pledge_type_id = '5' then pa.amount else 0 end) funeral
		from
			payment pa
			left join pledge pl on pl.id = pa.pledge_id
			left join event e on e.id = pl.event_id
			left join pledge_type pt on pt.id = e.pledge_type_id
		group by
			year(pa.payment_date) desc";
		//echo $sql;
		$stmt = $this->conn->prepare($sql);
		$this->db->checkError();
		$stmt->execute();
		$this->db->checkError();

		$reports = array();
		$stmt->bind_result($year, $amount, $no, $operation, $newmasjid, $zakath, $transportation, $funeral);
		while ($stmt->fetch()) {
			$reports[] = array('year' => $year, 'amount' => $amount,
					'no' => $no, 'operation' => $operation, 'newmasjid' => $newmasjid,
					'zakath' => $zakath, 'transportation' => $transportation,
					'funeral' => $funeral);
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