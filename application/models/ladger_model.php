<?php 
/**
 * 
 */
class Ladger_model extends CI_Model
{
	
	public function cash()
	{
        $data['user'] = $user =  $this->checkShopLogin();
		if (@$_POST['from_date']=='' && @$_POST['to_date']=='') {
			return false;
		}
		$shop_id     = $user->id;
		$f_date = $_POST['from_date'];
		$t_date = $_POST['to_date'];

		$query = "
		SELECT  mtb.* , 
				o.orderid as orderid,
				CONCAT(v.fname, ' ', v.lname) as name,
				am.title,
				sb.bank_name,
				(SELECT SUM(dr) - SUM(cr) FROM cash_register b 
				 WHERE b.PaymentDate < '{$f_date}'
				   AND b.active = 1 
				   AND b.is_deleted = 'NOT_DELETED' 
				   AND b.shop_id = $shop_id
				   AND b.txn_type = 1) as opening,
				(mtb.dr - mtb.cr) as balance
		FROM cash_register mtb 
		LEFT JOIN pos_orders o ON o.id = mtb.order_id
		LEFT JOIN customers v ON v.id = mtb.customer_id
		LEFT JOIN shop_bank_accounts sb ON sb.id = mtb.bank_account
		LEFT JOIN accounts_master am on am.id = mtb.customer_id
		WHERE mtb.PaymentDate >= '{$f_date}' 
		  AND mtb.PaymentDate <= '{$t_date}' 
		  AND mtb.active = 1 
		  AND mtb.is_deleted = 'NOT_DELETED' 
		  AND mtb.txn_type = 1
		  AND mtb.shop_id = $shop_id
		GROUP BY mtb.id
		ORDER BY mtb.PaymentDate
	";
	

		$return = $this->db->query($query)->result();

		// echo $this->db->last_query();			
		// echo _prx($return);

		return $return;

	}

	public function cash_account()
	{
        $data['user'] = $user =  $this->checkShopLogin();
		$shop_id     = $user->id;
		return $this->db->get_where('shop_cash_account',['shop_id'=>$shop_id])->row();
	}

	public function bank()
	{
        $data['user'] = $user =  $this->checkShopLogin();
		if (@$_POST['from_date']=='' && @$_POST['to_date']=='' && @$_POST['bank_account']=='') {
			return false;
		}
		$shop_id     = $user->id;
		$f_date 	= $_POST['from_date'];
		$t_date 	= $_POST['to_date'];
		$bank_ac	= $_POST['bank_account'];

		$query = "
					SELECT 	mtb.*,
							o.orderid as orderid,
							CONCAT(v.fname, ' ', v.lname) as name,
							am.title,
							sb.bank_name,
							(SELECT SUM(dr) - SUM(cr) FROM cash_register b 
							WHERE b.PaymentDate < '{$f_date}'
								AND b.active = 1 
								AND b.is_deleted = 'NOT_DELETED' 
								AND b.shop_id = $shop_id
								AND b.txn_type = 2
								AND b.bank_account = '{$bank_ac}') as opening,
							(mtb.dr - mtb.cr) as balance
						FROM cash_register mtb 
						LEFT JOIN pos_orders o on o.id = mtb.order_id
						LEFT JOIN customers v on v.id = mtb.customer_id
						LEFT JOIN shop_bank_accounts sb on sb.id = mtb.bank_account
						LEFT JOIN accounts_master am on am.id = mtb.customer_id
						WHERE mtb.PaymentDate >= '{$f_date}' 
							AND mtb.PaymentDate <= '{$t_date}' 
							AND mtb.active = 1 
							AND mtb.is_deleted = 'NOT_DELETED' 
							AND mtb.txn_type = 2
							AND mtb.bank_account = '{$bank_ac}'
							AND mtb.shop_id = $shop_id
							GROUP BY mtb.id
							ORDER BY mtb.PaymentDate
						
						";

		$return = $this->db->query($query)->result();

		//	 echo $this->db->last_query();			
		// echo _prx($return);

		return $return;

	}

	public function bank_account()
	{
		$bank_account = $this->input->get_post('bank_account');
		return $this->db->get_where('shop_bank_accounts',['id'=>$bank_account])->row();
	}

	public function party()
	{
        $data['user'] = $user =  $this->checkShopLogin();
		if (@$_POST['from_date']=='' or @$_POST['to_date']=='' or @$_POST['business_id']=='') {
			return false;
		}
		$shop_id     = $user->id;
		$f_date = $_POST['from_date'];
		$t_date = $_POST['to_date'];
		$customer_id = $_POST['business_id'];
        // $is_accounts 	= $_POST['is_accounts'];
         // Adjust the column selection based on $is_accounts]
		 $v_name_column = "CONCAT(vp.fname, ' ', vp.mname, ' ', vp.lname) as v_name";
		$query = "
					SELECT 	mtb.*,
							-- o.orderid as orderid,
							 $v_name_column,
							sb.bank_name,
							p.name as p_name,
							CASE mtb.txn_type
							      WHEN 1 THEN 'Cash'
							      WHEN 2 THEN sb.bank_name
							      WHEN 3 THEN 'Sales'
							      WHEN 4 THEN p2.name
							      WHEN 5 THEN 
							      	CONCAT(p.name ,' &nbsp;&nbsp;&nbsp;&nbsp; ', 
							      			sr.qty,' Pcs.', 
							      			IF(sr.free != 0, ' (' , ''),
							      			IF(sr.free != 0, sr.free , ''),' ',
							      			IF(sr.free != 0, ' free' , ''),
							      			IF(sr.free != 0, ')' , ''),' &nbsp;&nbsp;&nbsp;&nbsp; @ ',
							      			sr.rate,' ',
							      			IF(sr.discount != 0, '( Disc ' , ''),' ',
							      			IF(sr.discount != 0, sr.discount , ''),' ',
							      			IF(sr.discount != 0, ' %' , ''),' ',
							      			IF(sr.discount != 0, ' )' , '')
							      			)
							      WHEN 6 THEN p.name
							      WHEN 7 THEN am.title
							      WHEN 8 THEN am.title
							      ELSE NULL
							  END as 'name',
							CASE mtb.txn_type
							      WHEN 4 THEN mtb.reference_no
							      WHEN 5 THEN sr.invoice_no
							      WHEN 6 THEN pr.invoice_no
								  WHEN 8 THEN mtb.reference_no
							      ELSE o.orderid
							  END as 'orderid',


							(SELECT SUM(dr) - SUM(cr) FROM cash_register b 
							WHERE b.PaymentDate < '{$f_date}'
								AND b.active = 1 
								AND b.shop_id = $shop_id
								AND b.is_deleted = 'NOT_DELETED' 
								AND b.customer_id = '{$customer_id}') as opening,
							(mtb.dr - mtb.cr) as balance
						FROM cash_register mtb 
						LEFT JOIN pos_orders o on o.id = mtb.order_id
						LEFT JOIN customers v on v.id = mtb.customer_id
						LEFT JOIN customer_personal_details vp on vp.customer_id = v.id
						LEFT JOIN accounts_master am on am.id = mtb.order_id
						LEFT JOIN shop_bank_accounts sb on sb.id = mtb.bank_account
						LEFT JOIN products_subcategory p on p.id = mtb.product_id
						LEFT JOIN sales_return sr on sr.id = mtb.return_id
						LEFT JOIN purchase_return pr on pr.id = mtb.return_id
						LEFT JOIN shops_inventory s_in on s_in.id = mtb.inventory_id
						LEFT JOIN products_subcategory p2 on p2.id = s_in.product_id
						
						WHERE mtb.PaymentDate >= '{$f_date}' 
							AND mtb.PaymentDate <= '{$t_date}' 
							AND mtb.active = 1 
							AND mtb.is_deleted = 'NOT_DELETED' 
							AND mtb.customer_id = '{$customer_id}'
							AND mtb.shop_id = $shop_id
							GROUP BY mtb.id
							ORDER BY mtb.PaymentDate
						
						";

		$return = $this->db->query($query)->result();

		// echo $this->db->last_query();			
		// echo _prx($return);
		// die();
		return $return;
	}


	public function monthly_report()
	{
        $data['user'] = $user =  $this->checkShopLogin();
		$result = false;
		if (@$_POST['month'] && (@$_POST['is_Customer']=='on' or @$_POST['is_Vendor']=='on')) {
			$shop_id     = $user->id;
			$f_date = $_POST['from_date'];
			$t_date = $_POST['to_date'];
			$customer_id = @$_POST['business_id'];

			$user_type = (@$_POST['is_Customer']=='on') ? 2 : 1 ;
			$query = "SELECT 
						mtb.id,mtb.name, mtb.vendor_code
						FROM vendors mtb
						WHERE mtb.user_type = $user_type 
						AND mtb.shop_id = $shop_id
						 ";
			if (@$_POST['business_id']) {
				$query .= " AND mtb.id = $customer_id ";
			}

			$result = $this->db->query($query)->result();


			
			// echo $this->db->last_query();	

				foreach ($result as $key => $value) {
					$_POST['business_id'] = $value->id;
					$party_opening = $this->party_opening($add_opening='no');
					// echo _prx($party_opening);
					$value->total_dr = $party_opening['total_dr'];
					$value->total_cr = $party_opening['total_cr'];
					$value->total_balance = $party_opening['total_balance'];
					$value->dr_cr = ($party_opening['total_balance'] <= 0) ? 'Cr' : 'Dr';

				}

			// echo _prx($result);
		
		}
		return $result;

	}


	public function party_opening($add_opening='yes')
	{
        $data['user'] = $user =  $this->checkShopLogin();
		if (@$_POST['from_date']=='' or @$_POST['to_date']=='' or @$_POST['business_id']=='') {
			return false;
		}
		$shop_id     	= $user->id;
		$f_date 	 	= $_POST['from_date'];
		$t_date 		= $_POST['to_date'];
		$customer_id 	= $_POST['business_id'];
		$query = "
					SELECT 	mtb.*
					FROM cash_register mtb 
					WHERE mtb.PaymentDate < '{$f_date}'
						AND mtb.active = 1 
						AND mtb.is_deleted = 'NOT_DELETED' 
						AND mtb.customer_id = '{$customer_id}'
						GROUP BY mtb.id
						ORDER BY mtb.PaymentDate
						
						";
		if ($add_opening!='yes') {
			$query = "
					SELECT 	mtb.*
					FROM cash_register mtb 
					WHERE mtb.PaymentDate >= '{$f_date}' 
							AND mtb.PaymentDate <= '{$t_date}' 
						AND mtb.active = 1 
						AND mtb.is_deleted = 'NOT_DELETED' 
						AND mtb.customer_id = '{$customer_id}'
						GROUP BY mtb.id
						ORDER BY mtb.PaymentDate
						
						";
		}
		


		$return = $this->db->query($query)->result();
		// echo $this->db->last_query();
		// echo _prx($return);

		$total_dr = _nf(0);
		$total_cr = _nf(0);

		foreach ($return as $key => $value) {
			$dr = $value->dr;
            $cr = $value->cr;

            if ($value->txn_type!=3) {
                $dr = $value->cr;
                $cr = $value->dr;
            }

            $balance = $dr - $cr;

            $total_dr += $dr; 
            $total_cr += $cr;
		}

		$total_balance = $total_dr - $total_cr;

		$this->db->where('user_id',$customer_id);
		
	
		$vendor_opening = $this->db->get('vendors_opening')->row();

		$this->db->where('id',$customer_id);
		$vendor_tmp = $this->db->get('vendors')->row();

		if (@$vendor_opening && $add_opening=='yes') {
			if ($vendor_opening->dr_cr=="cr") {
				$total_balance = $total_balance - $vendor_opening->amount;
			}
			else{
				$total_balance = $total_balance + $vendor_opening->amount;
			}
		}

		// echo _prx($vendor_opening);



		$returnArray['total_dr'] = _nf($total_dr);
		$returnArray['total_cr'] = _nf($total_cr);
		$returnArray['total_balance'] = _nf($total_balance);
		$returnArray['credit_limit'] =  (@$vendor_tmp->credit_limit) ?  _nf($vendor_tmp->credit_limit) : 0;

		// echo _prx($returnArray);
		// echo _prx($return);

		return $returnArray;
	}


	public function party_opening_new($type,$cust_id=null)
	{
		$add_opening='yes';
        $data['user'] = $user =  $this->checkShopLogin();
		if (@$_POST['from_date']=='' or @$_POST['to_date']=='' or @$_POST['business_id']=='') {
			return false;
		}
		$shop_id     	= $user->id;
		$f_date 	 	= $_POST['from_date'];
		$t_date 		= $_POST['to_date'];
		$customer_id 	= $_POST['business_id'];
		// $is_accounts 	= $_POST['is_accounts'];
		$query = "
					SELECT 	mtb.*
					FROM cash_register mtb 
					WHERE mtb.PaymentDate < '{$f_date}'
						AND mtb.active = 1 
						AND mtb.is_deleted = 'NOT_DELETED' 
						AND mtb.customer_id = '{$customer_id}'
						GROUP BY mtb.id
						ORDER BY mtb.PaymentDate
						
						";
		if ($add_opening!='yes') {
			$query = "
					SELECT 	mtb.*
					FROM cash_register mtb 
					WHERE mtb.PaymentDate >= '{$f_date}' 
							AND mtb.PaymentDate <= '{$t_date}' 
						AND mtb.active = 1 
						AND mtb.is_deleted = 'NOT_DELETED' 
						AND mtb.customer_id = '{$customer_id}'
						GROUP BY mtb.id
						ORDER BY mtb.PaymentDate
						
						";
		}
		


		$return = $this->db->query($query)->result();
		// echo $this->db->last_query();
		// echo _prx($return);

		$total_dr = _nf(0);
		$total_cr = _nf(0);

		foreach ($return as $key => $value) {
			$dr = $value->dr;
            $cr = $value->cr;

            if ($value->txn_type!=3) {
                $dr = $value->cr;
                $cr = $value->dr;
            }

            $balance = $dr - $cr;

            $total_dr += $dr; 
            $total_cr += $cr;
		}

		$total_balance = $total_dr - $total_cr;
        
		
		
		if($type=='Expense' && $cust_id !=null)
		{
			$this->db->where('type','2');
			$this->db->where('user_id',$cust_id);
		}else{
			$this->db->where('type','1');
			$this->db->where('user_id',$customer_id);
		}
		$vendor_opening = $this->db->get('vendors_opening')->row();
        // echo  $total_balance;
		$this->db->where('id',$customer_id);
		$vendor_tmp = $this->db->get('vendors')->row();

		if (@$vendor_opening && $add_opening=='yes') {
			if ($vendor_opening->dr_cr=="cr") {
				$total_balance = $total_balance - $vendor_opening->amount;
			}
			else{
				$total_balance = $total_balance + $vendor_opening->amount;
			}
		}

		// echo _prx($vendor_opening);



		$returnArray['total_dr'] = _nf($total_dr);
		$returnArray['total_cr'] = _nf($total_cr);
		$returnArray['total_balance'] = _nf($total_balance);
		$returnArray['credit_limit'] =  (@$vendor_tmp->credit_limit) ?  _nf($vendor_tmp->credit_limit) : 0;

		// echo _prx($returnArray);
		// echo _prx($return);

		return $returnArray;
	}
	

	function get_order_details($id){
			$query = "SELECT  	
						mtb.product_id, 
						mtb.qty, 
						mtb.price_per_unit, 
						mtb.mrp, 
						mtb.total_price,
						mtb.free,
						p.name,
						p.unit_type,
						mtb.offer_applied,
						mtb.offer_applied2,
						mtb.discount_type,
						mtb.discount_type2
				FROM pos_order_items mtb 
				LEFT JOIN products_subcategory p on mtb.product_id = p.id
				-- LEFT JOIN vendors v on v.id = mtb.customer_id
				WHERE mtb.order_id = '{$id}' ";
						

		$return = $this->db->query($query)->result();
		// echo _prx($return);
		return $return;

		// echo $this->db->last_query();			
	}

	public function shop_details() {
        $data['user'] = $user =  $this->checkShopLogin();
		$shop_id     = $user->id;
		$shop_query ="	SELECT mtb.*, c.name as city_name , s.name as state_name
						FROM shops mtb
						LEFT JOIN cities c on c.id = mtb.city
						LEFT JOIN states s on s.id = mtb.state
						WHERE mtb.id = $shop_id";
		return $this->db->query($shop_query)->row();
	}

      
  




    function checkCookie(){
        $loggedin = false;
        if (get_cookie('63a490ed05b42') && get_cookie('63a490ed05b43') && get_cookie('63a490ed05b44')) {
            $user_id = value_encryption(get_cookie('63a490ed05b42'),'decrypt');
            $user_nm = value_encryption(get_cookie('63a490ed05b43'),'decrypt');
            if (is_numeric($user_id) && !is_numeric($user_nm)) {
                $loggedin = true;
            }
        }

        if ($loggedin) {
            return true;
        }
        else{
            delete_cookie('63a490ed05b42');	
            delete_cookie('63a490ed05b43');	
            delete_cookie('63a490ed05b44');	
            delete_cookie('63a490ed05b45');	
            redirect(base_url().'');
        }
    }
    
        function checkShopLogin(){
            $loggedin = false;
            if (get_cookie('63a490ed05b42') && get_cookie('63a490ed05b43') && get_cookie('63a490ed05b44')) {
                $user_id = value_encryption(get_cookie('63a490ed05b42'),'decrypt');
                $user_nm = value_encryption(get_cookie('63a490ed05b43'),'decrypt');
                $type    = value_encryption(get_cookie('63a490ed05b44'),'decrypt');
                if (is_numeric($user_id) && !empty($user_nm)) {
                    $check['id'] 	   = $user_id;
                    $check['contact'] = $user_nm;
                    if ($type=='shop') {
                        // $user = $this->admin_model->getRow('admin',$check);
                        $CI =& get_instance();
                       $user = $CI->db->get_where('shops',$check)->row();
                      
                    }
                    else{
                        $user = false;
                    }
                    
                    if ($user) {
                        if ($user->isActive==1) {
                            $user->type = $type;
                            $loggedin = true;
                        }
                    }
                    
                }
                
            }
            if ($loggedin) {
                return $user;
            }
            else{
                
                delete_cookie('63a490ed05b42');	
                delete_cookie('63a490ed05b43');	
                delete_cookie('63a490ed05b44');	
                delete_cookie('63a490ed05b45');	
                redirect(base_url().'');
            }
        }
     

}


 ?>