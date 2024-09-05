<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class orders_model extends CI_Model
{
	function index(){
		echo 'This is model index function';
	}
	function __construct(){
		$this->tbl1 = 'orders';
		$this->load->database();
	}
	function getRows($data = array()){
		$this->db->select("*,(SELECT shop_name FROM shops where id=orders.shop_id) as \"shop_name\",
		(SELECT contact FROM shops where id=orders.shop_id) as \"shop_mobile\",
		(SELECT CONCAT(fname,' ',mname,' ',lname) FROM customer_personal_details where customer_id=orders.user_id) as \"customer_name\",
		(SELECT email FROM customer_personal_details where customer_id=orders.user_id) as \"customer_email\"");
		$this->db->from($this->tbl1);
		if (array_key_exists("conditions", $data)) {
			foreach ($data['conditions'] as $key => $value) {
				$this->db->where($key,$value);
			}
		}
		$query = $this->db->get();
		$result = ($query->num_rows() > 0)?$query->result_array():FALSE;
		return $result;
	}
	function insertRow($data = array()){
		$result = $this->db->insert($this->tbl1,$data);
		return $result;
	}
	function updateRow($id,$data = array()){
		$this->db->where($this->tbl1.'.'.'id',$id);
		$result = $this->db->update($this->tbl1,$data);
		return $result;
	}
	function deleteRow($id){
		$this->db->where($this->tbl1.'.'.'id',$id);
		$result = $this->db->delete($this->tbl1);
		return $result;
	}
	function getOrdersData($data = array(),$mobile='',$payment_mode=''){
		// echo('<pre>');
		// print_r($payment_mode[0]);
		// die();
		$this->db->select("
							orders.id,
							orders.orderid,
							orders.invoice_no,
							(SELECT shop_name FROM shops where id=orders.shop_id) as \"shop_name\",
							(SELECT CONCAT(fname,' ',lname,' (',email,')') FROM customers where id=orders.user_id) as \"customer_name\",
							orders.datetime,
							CONCAT(datetime,' (',TIME_FORMAT(timeslot_starttime, \"%h:%i %p\"),' - ',TIME_FORMAT(timeslot_endtime, \"%h:%i %p\"),')') as \"delivery_slot\",
							orders.address_id,
							orders.random_address,
							orders.total_value,
							orders.total_savings,
							orders.payment_method,
							orders.status,
							orders.added,
							customers.mobile,
							order_status_master.name as status_name,
							CONCAT(db.full_name,' (',db.contact_number,')') as delivery_boy,
							db.id as delivery_boy_id
						");
		$this->db->from($this->tbl1);
        $this->db->join('customers', 'customers.id = orders.user_id');
        $this->db->join('order_status_master', 'order_status_master.id = orders.status');
        $this->db->join('order_assign_deliver oad', 'oad.order_id = orders.id','left');
        $this->db->join('delivery_boys db', 'db.id = oad.delivery_boy_id','left');
		if (array_key_exists("conditions", $data)) 
		{
			foreach ($data['conditions'] as $key => $value) {
				$this->db->where($this->tbl1.".".$key,$value);
			}
		}
		if ($mobile != 'null') {
				$this->db->where('customers.mobile',$mobile);
		}
		if ($payment_mode != 'null') {
			if ($payment_mode == 'cod') {
                $this->db->where('orders.payment_method' , 'cod');
            }
			else if($payment_mode == 'online')
            {
                $this->db->where('orders.payment_method!=' , 'cod');
            }
		}
		if(isset($_SESSION['order_table_filters']['from_date']) && $_SESSION['order_table_filters']['from_date']!==''){
			if (array_key_exists("order_date", $data)) {
				$from_date = DATE($data['order_date']['start_date']);
				$to_date = DATE($data['order_date']['end_date']);
				// print_r($from_date);
				$this->db->where(['DATE('.$this->tbl1.'.added) >='=>$from_date , 'DATE('.$this->tbl1.'.added) <='=>$to_date]);
				// $this->db->where(['DATE('.$this->tbl1.'.added) >='=>'2021-07-01' , 'DATE('.$this->tbl1.'.added) <='=>'2021-10-30']);
				// $this->db->last_query();
			}
		}
		if(isset($_SESSION['order_table_filters']['delivery_boy']) && $_SESSION['order_table_filters']['delivery_boy']!==''){
			$this->db->where('db.id',$_SESSION['order_table_filters']['delivery_boy']);
		}
		if (array_key_exists("conditions_join", $data)) {
			foreach ($data['conditions_join'] as $key => $value) {
				$this->db->where('customers'.".".$key,$value);
			}
		}
		if (array_key_exists("conditions_like", $data)) {
			foreach ($data['conditions_like'] as $key => $value) {
				$this->db->like($this->tbl1.".".$key,$value);
			}
		}
		if (array_key_exists("conditions_in", $data)) {
			foreach ($data['conditions_in'] as $key => $value) {
				$this->db->where_in($this->tbl1.".".$key,$value);
			}
		}
		if(isset($data['order_field']) && isset($data['order'])){
			$this->db->order_by($data['order_field'],strtoupper($data['order']));
		}else{
			$this->db->order_by('orders.added','DESC');
		}

		if(isset($data['limit']) && isset($data['offset'])){
			$this->db->limit($data['limit'],$data['offset']);
		}
		
		$query = $this->db->get();
		$result = ($query->num_rows() > 0)?$query->result_array():FALSE;
		
		return $result;
	}

	public function GetAllOrder($search,$limit=null,$start=null)
    {
        if ($limit!=null) {
            $this->db->limit($limit, $start);
        }
        $this->db
        ->select("t1.*,t1.id as Oid, 
		          CONCAT(t3.fname, ' ', t3.mname, ' ', t3.lname, ' (', t3.email, ')') AS customer_name,
				  t2.mobile as customer_mobile,
				  CONCAT(datetime,' (',TIME_FORMAT(timeslot_starttime, \"%h:%i %p\"),' - ',TIME_FORMAT(timeslot_endtime, \"%h:%i %p\"),')') as \"delivery_slot\",
				  CONCAT(db.full_name,' (',db.contact_number,')') as delivery_boy,
				  db.id as delivery_boy_id,
				  t1.payment_method,
				  order_status_master.name as status_name
				  ")
        ->from('orders t1')
        ->join('customers t2', 't1.id = t1.user_id')
		->join('customer_personal_details t3', 't3.customer_id = t2.id')
        ->join('order_status_master', 'order_status_master.id = t1.status')
        ->join('order_assign_deliver oad', 'oad.order_id = t1.id','left')
        ->join('delivery_boys db', 'db.id = oad.delivery_boy_id','left')  
		->group_by('t1.id')
        ->order_by('t1.added','desc');
        if ($search != 'null'  ) {
            $this->db->group_start();
			$this->db->like('t2.mobile', $search);
            $this->db->group_end();
		}
		if (@$_POST['start_date']) {
			$start_date = $_POST['start_date'] .' 00:00:00';    
			$this->db->where('t1.added >=',$start_date);
		}

		if (@$_POST['end_date']) {
			$end_date = $_POST['end_date'] . ' 23:59:59';
			$this->db->where('t1.added <=',$end_date);
		}
		if (@$_POST['order_status']) {
			$order_status = $_POST['order_status'];
			$this->db->where('t1.status',$order_status);
		}
		if (@$_POST['payment_method']) {
			$payment_method = $_POST['payment_method'];
			$this->db->where('t1.payment_mode',$payment_method);
		}
		if (@$_POST['delivery_boy']) {
			$delivery_boy = $_POST['delivery_boy'];
			$this->db->where('db.id',$delivery_boy);
		}
		
       
		if($limit!=null)
            return $this->db->get()->result();
        else
            return $this->db->get()->num_rows();
    }
	function getRows2($oid){
		$this->db
		 ->select('t1.*,t2.shop_name,t2.contact as shop_mobile,t3.mobile as alternate_mobile')
		 ->from('orders t1')
		 ->join('shops t2', 't2.id = t1.shop_id','left')        
		 ->join('customers t3', 't3.id = t1.user_id','left') 

		 ->where(['t1.id'=>$oid])  ;
	 
		 $query = $this->db->get();
		 $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
		 return $result;
	 }


	 

	function get_row_order($oid){
		$this->db
		 ->select('t1.*,t2.shop_name,t2.contact as shop_mobile,t3.mobile as alternate_mobile')
		 ->from('orders t1')
		 ->join('shops t2', 't2.id = t1.shop_id','left')        
		 ->join('customers t3', 't3.id = t1.user_id','left') 
		 ->where(['t1.id'=>$oid])  ;
	 
		 $query = $this->db->get()->row();
		 return $query;
	 }
	// function getNewOrdersRows($data = array()){
	// 	$this->db->select("*,(SELECT shop_name FROM shops where id=orders.shop_id) as \"shop_name\",
	// 	(SELECT contact FROM shops where id=orders.shop_id) as \"shop_mobile\",
	// 	(SELECT CONCAT(fname,' ',lname) FROM customers where id=orders.user_id) as \"customer_name\",
	// 	(SELECT mobile FROM customers where id=orders.user_id) as \"customer_mobile\"");
	// 	$this->db->from($this->tbl1);
	// 	if (array_key_exists("conditions", $data)) {
	// 		foreach ($data['conditions'] as $key => $value) {
	// 			$this->db->where($key,$value);
	// 		}
	// 	}
	// 	$this->db->order_by('added','DESC');
	// 	$query = $this->db->get();
	// 	$result = ($query->num_rows() > 0)?$query->result_array():FALSE;
	// 	return $result;
	// }
	function getNewOrdersRows($data = array()){
		$this->db->select("*,(SELECT shop_name FROM shops where id=orders.shop_id) as \"shop_name\",
		(SELECT contact FROM shops where id=orders.shop_id) as \"shop_mobile\",
		(SELECT mobile FROM customers where id=orders.user_id) as \"customer_mobile\"");
		$this->db->from($this->tbl1);
		if (array_key_exists("conditions", $data)) {
			foreach ($data['conditions'] as $key => $value) {
				$this->db->where($key,$value);
			}
		}
		$this->db->order_by('added','DESC');
		$query = $this->db->get();
		$result = ($query->num_rows() > 0)?$query->result_array():FALSE;
		return $result;
	}


	function getNewOrdersCancelRows($data = array()){
		$this->db->select("*,(SELECT shop_name FROM shops where id=orders.shop_id) as \"shop_name\",
		(SELECT contact FROM shops where id=orders.shop_id) as \"shop_mobile\",
		(SELECT CONCAT(fname,' ',mname,' ',lname) FROM customer_personal_details where customer_id=orders.user_id) as \"customer_name\",
		(SELECT mobile FROM customers where id=orders.user_id) as \"customer_mobile\"");
		$this->db->from($this->tbl1);
		if (array_key_exists("conditions", $data)) {
			foreach ($data['conditions'] as $key => $value) {
				$this->db->where($key,$value);
			}
		}
		$this->db->order_by('added','DESC');
		$query = $this->db->get();
		$result = ($query->num_rows() > 0)?$query->result_array():FALSE;
		return $result;
	}

	
	function getNewOrdersReturnRows($data = array()){
		$this->db->select("*,(SELECT shop_name FROM shops where id=orders.shop_id) as \"shop_name\",
		(SELECT contact FROM shops where id=orders.shop_id) as \"shop_mobile\",
		(SELECT CONCAT(fname,' ',mname,' ',lname) FROM customer_personal_details where customer_id=orders.user_id) as \"customer_name\",
		(SELECT mobile FROM customers where id=orders.user_id) as \"customer_mobile\"");
		$this->db->from($this->tbl1);
		if (array_key_exists("conditions", $data)) {
			foreach ($data['conditions'] as $key => $value) {
				$this->db->where($key,$value);
			}
		}
		$this->db->order_by('added','DESC');
		$query = $this->db->get();
		$result = ($query->num_rows() > 0)?$query->result_array():FALSE;
		return $result;
	}
	public function invoice_details($oid)
    {
        $query = $this->db
        ->select('t1.id as oid,t1.*,t1.added as order_date,t1.tax as order_tax,t2.qty as item_qty,t2.purchase_rate,t2.tax_value,t3.name as status_name,t4.id as product_id,t4.name as product_name,t4.unit_value,t4.unit_type,t5.img,t62.fname,t62.mname,t62.lname,t6.mobile,t62.email as cust_email,t7.address as cust_address,t7.contact_name,t7.contact,t8.*,t9.name as city_name,t10.name state_name')
        ->from('orders t1')
        ->join('order_items t2', 't2.order_id = t1.id','left')        
        ->join('order_status_master t3', 't3.id = t1.status','left')        
        ->join('products_subcategory t4', 't4.id = t2.product_id','left')        
		->join('products_photo t5', 't5.item_id = t4.id AND t5.is_cover="1"','left')  
		->join('customers t6', 't6.id = t1.user_id','left')
		->join('customer_personal_details t62', 't62.customer_id = t6.id','left')   
		->join('customers_address t7', 't7.id = t1.address_id','left')  
		->join('shops t8', 't8.id = t1.shop_id','left')  
		->join('cities t9', 't9.id = t8.city','left')  
		->join('states t10', 't10.id = t8.state','left')  
        ->where(['t4.is_deleted' => 'NOT_DELETED','t1.id'=>$oid])  
		->get();   
		return $query->row();
    }




	

	

}
?>