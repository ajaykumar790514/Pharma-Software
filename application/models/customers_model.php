<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class customers_model extends CI_Model
{
	function index(){
		echo 'This is model index function';
	}
	function __construct(){
		$this->tbl1 = 'customers';
		$this->load->database();
	}
	function getRows($data = array()){
		$this->db->select("*");
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






	//customer code starts

	public function get_customers()
    {
        $query = $this->db
        ->select('t1.*,t2.*')
        ->from('customers t1')       
        ->join('customers_address t2', 't2.customer_id = t1.mobile','left')  
        ->get();
		return $query->result();
    }

	public function get_customers_data($from_date,$to_date,$search,$limit=null,$start=null)
    {
        if ($limit!=null) {
            $this->db->limit($limit, $start);
        }
        $this->db
        ->select('t1.*,t2.*,t3.*,t1.active as isActive')
        ->from('customers t1')
		->join('customers_address t2', 't2.customer_id = t1.id','left')
		->join('customer_personal_details t3', 't3.customer_id = t1.id','left');
		// ->group_by('t2.customer_id'); 
        if ($search!='null') {
			$this->db->like('t1.fname', $search);
			$this->db->or_like('t1.lname', $search);
			$this->db->or_like('t1.mobile', $search);
			$this->db->or_like('t1.email', $search);
		}
        if ($to_date!='null') {
			$this->db->where('t1.added >=' , $from_date);
			$this->db->where('t1.added <=' , $to_date);
		}
		if($limit!=null)
            return $this->db->get()->result();
        else
            return $this->db->get()->num_rows();
    }

	public function address($id)
    {
        $query = $this->db
        ->select('t1.*,t2.name as state_name,t3.name as city_name')
        ->from('customers_address t1')       
        ->join('states t2', 't2.id = t1.state','left')  
        ->join('cities t3', 't3.id = t1.city','left')  
		->where('t1.customer_id',$id)
        ->get();
		return $query->result();
    }

	/**
	 * get B2B customers list
	 *
	 * @return object
	 * @throws conditon
	 **/
	public function get_b2b_customers_list()
	{
		$customersList = $this->db
								->from('customers c')
								->select('c.mobile, cbd.status as business_status, cld.status as license_status,cpd.pic1,cpd.pic2, cpand.status as pan_status,cdm.title,cpd.*')
								->join('customer_personal_details cpd', 'c.id = cpd.customer_id', 'left')
								->join('customer_business_details cbd', 'c.id = cbd.customer_id', 'left')
								->join('customer_license_details cld', 'c.id = cld.customer_id', 'left')
								->join('customer_pan_details cpand', 'c.id = cpand.customer_id', 'left')
								->join('customer_documents_master cdm', 'cdm.id = cpd.document_type', 'left')
								->get()
								->result();
		return $customersList;
	}

	/**
	 * get b2b customer's business details
	 *
	 * @param Array $request
	 * @return Array
	 **/
	public function get_b2b_customer_business_details($request)
	{
		$request['id']='1';
		if(empty($request['id']) || $this->check_customer_id_exist($request['id']) == false) {
			return [
				'status' => false,
				'message' => 'Unable to get customer',
				'data' => []
			];
		}
		if($this->check_business_details_exist($request['id']) == false) {
			return [
				'status' => false,
				'message' => 'Unable to get customer business details',
				'data' => []
			];
		}
		
		$details= $this->db
					   ->from('customers c')
					   ->select('cbd.*')
					   ->join('customer_business_details cbd', 'c.id = cbd.customer_id','left')
					   ->where('c.id', $request['id'])
					   ->get()
					   ->row();
		//echo $this->db->last_query();		   
		$details_html = $this->load->view('admin/customers/b2b/modals/business-details',['customer'=> $details], true);
		return [
			'status' => true,
			'message' => 'get customer details successfully',
			'data' => $details_html
		];			   
	}

	/**
	 * get b2b customer's pan details
	 *
	 * @param Array $request
	 * @return Array
	 **/
	public function get_b2b_customer_pan_details($request)
	{
		if(empty($request['id']) || $this->check_customer_id_exist($request['id']) == false) {
			return [
				'status' => false,
				'message' => 'Unable to get customer',
				'data' => []
			];
		}
		if($this->check_pan_details_exist($request['id']) == false) {
			return [
				'status' => false,
				'message' => 'Unable to get customer pan details',
				'data' => []
			];
		}

		$details= $this->db
					   ->from('customers c')
					   ->select('cpd.*, cdm.title')
					   ->join('customer_pan_details cpd', 'c.id = cpd.customer_id')
					   ->join('customer_business_documents_master cdm', 'cpd.type_of_ownership = cdm.id')
					   ->where('c.id', $request['id'])
					   ->get()
					   ->row();
					   
		$details_html = $this->load->view('admin/customers/b2b/modals/pan-details',['customer'=> $details], true);
		return [
			'status' => true,
			'message' => 'get customer details successfully',
			'data' => $details_html
		];			   
	}

	/**
	 * get b2b customer's license details
	 *
	 * @param Array $request
	 * @return Array
	 **/
	public function get_b2b_customer_license_details($request)
	{
		if(empty($request['id']) || $this->check_customer_id_exist($request['id']) == false) {
			return [
				'status' => false,
				'message' => 'Unable to get customer',
				'data' => []
			];
		}
		if($this->check_license_details_exist($request['id']) == false) {
			return [
				'status' => false,
				'message' => 'Unable to get customer license details',
				'data' => []
			];
		}

		$details= $this->db
					   ->from('customers c')
					   ->select('cld.*, clm.title')
					   ->join('customer_license_details cld', 'c.id = cld.customer_id','left')
					   ->join('customer_license_master clm', 'cld.license_type = clm.id' ,'left')
					   ->where('c.id', $request['id'])
					   ->get()
					   ->row();
					   
		$details_html = $this->load->view('admin/customers/b2b/modals/license-details',['customer'=> $details], true);
		return [
			'status' => true,
			'message' => 'get customer details successfully',
			'data' => $details_html
		];			   
	}

	/**
	 * change b2b customer's status
	 *
	 * @param Array $request
	 * @return Array
	 **/
	public function get_b2b_customer_change_status($request)
	{
		if(empty($request['customer_id']) || $this->check_customer_id_exist($request['customer_id']) == false) {
			return [
				'status' => false,
				'message' => 'Unable to get customer',
				'data' => []
			];
		}
		if(empty($request['status']) || !in_array($request['status'], ['VERIFIED', 'REJECTED'])) {
			return [
				'status' => false,
				'message' => 'Please select valid status',
				'data' => []
			];
		}
		if($request['status'] == 'REJECTED' && empty($request['rejected_comment'])) {
			return [
				'status' => false,
				'message' => 'Please enter rejected comment',
				'data' => []
			];
		}

		$table = null;
		if($request['customer_detail_type'] == 'business') {
			$table = 'customer_business_details';
		} else if($request['customer_detail_type'] == 'pan') {
			$table = 'customer_pan_details';
		} else if($request['customer_detail_type'] == 'license') {
			$table = 'customer_license_details';
		} else {
			return [
				'status' => false,
				'message' => 'something is wrong. please try again',
				'data' => []
			];
		}

		$status= $this->db
					   ->set('status', $request['status'])
					   ->set('rejected_comment', $request['rejected_comment'])
					   ->where('customer_id', $request['customer_id'])
					   ->update($table);
		return [
			'status' => true,
			'message' => 'status updated successfully',
			'data' => []
		];			   
	}

	/**
	 * change b2b customer's status
	 *
	 * @param Array $request
	 * @return Array
	 **/
	public function get_b2b_customer_verify_status($request)
	{
		if(empty($request['customer_id']) || $this->check_customer_verify_exist($request['customer_id']) == false) {
			return [
				'status' => false,
				'message' => 'Unable to get customer',
				'data' => []
			];
		}
		if(empty($request['status']) || !in_array($request['status'], ['VERIFIED', 'REJECTED'])) {
			return [
				'status' => false,
				'message' => 'Please select valid status',
				'data' => []
			];
		}
		if($request['status'] == 'REJECTED' && empty($request['rejected_comment'])) {
			return [
				'status' => false,
				'message' => 'Please enter rejected comment',
				'data' => []
			];
		}

		$table = null;
		if($request['customer_detail_type'] == 'business') {
			$table = 'customer_business_details';
		} else if($request['customer_detail_type'] == 'pan') {
			$table = 'customer_pan_details';
		} else if($request['customer_detail_type'] == 'license') {
			$table = 'customer_license_details';
		} else {
			return [
				'status' => false,
				'message' => 'something is wrong. please try again',
				'data' => []
			];
		}

		$status= $this->db
					   ->set('status', $request['status'])
					   ->set('rejected_comment', $request['rejected_comment'])
					   ->where('customer_id', $request['customer_id'])
					   ->update($table);
		return [
			'status' => true,
			'message' => 'status updated successfully',
			'data' => []
		];			   
	}

	/**
	 * check customer id exist
	 *
	 * @param Int $id
	 * @return boolean
	 **/
	public function check_customer_id_exist($id)
	{
		 return $this->db
					->from($this->tbl1)
					->where('id', $id)
					->get()
					->num_rows()
					== 1;
	}

	public function check_customer_verify_exist($id)
	{
		 return $this->db
					->from('customer_personal_details')
					->where('customer_id', $id)
					->get()
					->num_rows()
					== 1;
	}

	/**
	 * check customer business details exist
	 *
	 * @param Int $customer_id
	 * @return boolean
	 **/
	public function check_business_details_exist($customer_id)
	{
		 return $this->db
					->from('customer_business_details')
					->where('customer_id', $customer_id)
					->get()
					->num_rows()
					== 1;
	}

	/**
	 * check customer pan details exist
	 *
	 * @param Int $customer_id
	 * @return boolean
	 **/
	public function check_pan_details_exist($customer_id)
	{
		 return $this->db
					->from('customer_pan_details')
					->where('customer_id', $customer_id)
					->get()
					->num_rows()
					== 1;
	}

	/**
	 * check customer license details exist
	 *
	 * @param Int $customer_id
	 * @return boolean
	 **/
	public function check_license_details_exist($customer_id)
	{
		 return $this->db
					->from('customer_license_details')
					->where('customer_id', $customer_id)
					->get()
					->num_rows()
					== 1;
	}

		// Create BY AJAY KUMAR
		public function getRowValue($id)
		{
			$query = $this->db
			->select('t1.*,t2.fname,t2.mname,t2.lname,t2.email')
			->from('customers t1')   
			->join('customer_personal_details t2','t2.customer_id=t1.id','left')       
			->where(['t1.is_deleted' => 'NOT_DELETED','t1.id'=>$id])
			->get();
			return $query->row();
		}
			// Create BY AJAY KUMAR
	public function getRowValue2($tb,$id)
	{
		$query = $this->db
		->select('*')
		->from($tb)       
		->where(['is_deleted' => 'NOT_DELETED','id'=>$id])
		->get();
		return $query->row();
	}
public function hisab_kitab_customer($id)
{
	$this->db
	->from('customer_transaction u')
	->select('u.*')
	->where(['u.is_deleted'=>'NOT_DELETED' , 'customer_id'=> $id]) 
	->order_by('u.tr_date','asc');
	if (@$_POST['start_date']) {
		$start_date = $_POST['start_date'] .' 00:00:00';    
		$this->db->where(['u.tr_date >='=>$start_date]);
	}

	if (@$_POST['end_date']) {
		$end_date = $_POST['end_date'] . ' 23:59:59';
		$this->db->where(['u.tr_date <='=>$end_date]);
	}
	return $this->db->get()->result();
}

public function opening_hisab_kitab_customer($id)
{
	$this->db
	->from('customer_transaction u')
	->select('u.*')
	->where(['u.is_deleted'=>'NOT_DELETED' , 'customer_id'=> $id])
	->order_by('u.tr_date','asc');
	if(!empty($_POST['start_date'])){
			 
			  }else{
				$this->db->limit(1); 
			  }
	

	if (@$_POST['start_date']) {
		$start_date = $_POST['start_date'] .' 00:00:00';    
		$this->db->where(['u.tr_date <'=>$start_date]);
	}

	return $this->db->get()->result();
}
function _delete($tb,$data) {
	if (is_array($data)){
		$this->db->where($data);
		if($this->db->update($tb,['is_deleted'=>'DELETED'])){
			return true;
		}
	}
	else{
		$this->db->where('id',$data);
		if($this->db->update($tb,['is_deleted'=>'DELETED'])){
			return true;
		}
	}
	return false;
}
	public function Customers_Data($search,$limit=null,$start=null)
    {
        if ($limit!=null) {
            $this->db->limit($limit, $start);
        }
        $this->db
        ->from('customers c')
		->select('c.mobile,c.credit_limit, cbd.status as business_status, cld.status as license_status,cpd.pic1,cpd.pic2, cpand.status as pan_status,cdm.title,cpd.*,cbd.business_name')
		->join('customer_personal_details cpd', 'c.id = cpd.customer_id', 'left')
		->join('customer_business_details cbd', 'c.id = cbd.customer_id', 'left')
		->join('customer_license_details cld', 'c.id = cld.customer_id', 'left')
		->join('customer_pan_details cpand', 'c.id = cpand.customer_id', 'left')
		->join('customer_documents_master cdm', 'cdm.id = cpd.document_type', 'left')
		->where('c.user_type','customer')
		->order_by('c.added','desc');					
        if (@$_POST['search']) {
			$data['search'] = $_POST['search'];
			$this->db->group_start();
			$this->db->like('cpd.fname',$_POST['search']);
			$this->db->or_like('c.mobile', $_POST['search']);
			$this->db->where('c.user_type', 'customer');
			$this->db->group_end();
		}
        if($limit!=null)
            return $this->db->get()->result();
        else
            return $this->db->get()->num_rows();
		return $this->db->get()->result();
		//echo $this->db->last_query();
    }



	public function Customers_Data_license($customer_id,$search,$limit=null,$start=null)
    {
        if ($limit!=null) {
            $this->db->limit($limit, $start);
        }
        $this->db
        ->from('customer_license_details t1')
		->select('t1.*,t4.fname,t4.mname,t4.lname,t2.mobile,t4.email,t3.title')
		->join('customers t2', 't2.id = t1.customer_id', 'left')
		->join('customer_license_master t3', 't3.id = t1.license_type', 'left')
		->join('customer_personal_details t4', 't2.id = t4.customer_id', 'left')
		->where('t2.id',$customer_id)
		->order_by('t4.added','desc');		
        if (@$_POST['search']) {
			$data['search'] = $_POST['search'];
			$this->db->group_start();
			$this->db->like('t4.fname',$_POST['search']);
			$this->db->or_like('t1.mobile', $_POST['search']);
			$this->db->group_end();
		}
        if($limit!=null)
            return $this->db->get()->result();
        else
            return $this->db->get()->num_rows();
		return $this->db->get()->result();
    }

	
    /**
	 * get b2b customer's credit details
	 *
	 * @param Array $request
	 * @return Array
	 **/
	public function get_b2b_customer_add_credit($request)
	{
		if(empty($request['id']) || $this->check_customer_id_exist($request['id']) == false) {
			return [
				'status' => false,
				'message' => 'Unable to get customer',
				'data' => []
			];
		}
		if($this->check_pan_details_exist($request['id']) == false) {
			return [
				'status' => false,
				'message' => 'Unable to get customer Credit Limit',
				'data' => []
			];
		}

		$details= $this->db
					   ->from('customers c')
					   ->select('c.*, cpd.fname,cpd.lname,cpd.mname,cpd.customer_id')
					   ->join('customer_personal_details cpd', 'cpd.customer_id = c.id')
					   ->where('c.id', $request['id'])
					   ->get()
					   ->row();
					   
		$details_html = $this->load->view('admin/customers/b2b/modals/add-credit-limit',['customer'=> $details], true);
		return [
			'status' => true,
			'message' => 'get customer Credit details successfully',
			'data' => $details_html
		];			   
	}
		/**
	 * change b2b customer's credit post
	 *
	 * @param Array $request
	 * @return Array
	 **/
	public function get_b2b_customer_credit_limit_post($request)
	{
		//print_r($request['credit_limit']);die();
		if(empty($request['customer_id']) || $this->check_customer_id_exist($request['customer_id']) == false) {
			return [
				'status' => false,
				'message' => 'Unable to get customer',
				'data' => []
			];
		}

		$table = null;
		if($request['credit_limit'] == $request['credit_limit']) {
			$table = 'customers';
		}  else {
			return [
				'status' => false,
				'message' => 'something is wrong. please try again',
				'data' => []
			];
		}

		$status= $this->db
					   ->set('credit_limit', $request['credit_limit'])
					   ->where('id', $request['customer_id'])
					   ->update($table);
		return [
			'status' => true,
			'message' => 'Credit  limit updated successfully',
			'data' => []
		];			   
	}

}
