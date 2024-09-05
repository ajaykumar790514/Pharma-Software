<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class shops_vendor_model extends CI_Model
{

	function __construct(){
		$this->tbl1 = 'customers';
		$this->load->database();
	}
    public function vendors($limit=null,$start=null)
    {
        if ($limit!=null) {
            $this->db->limit($limit, $start);
        }
        $this->db
        ->select('t1.*,t1.id as sup_id,sup.*,t2.name as state_name,t3.name as city_name')
        ->from('customers t1')
        ->join('supplier_details sup','sup.supplier_id=t1.id','left')
        ->join('states t2', 't2.id = sup.state','left')        
        ->join('cities t3', 't3.id = sup.city','left')        
        ->where(['t1.is_deleted' => 'NOT_DELETED','t1.user_type'=>'supplier'])
        ->order_by('t1.added','desc');
        if (@$_POST['search']) {
            $data['search'] = $_POST['search'];
            $this->db->group_start();
            $this->db->like('sup.name', $_POST['search']);
            $this->db->or_like('sup.vendor_code', $_POST['search']);
            $this->db->or_like('sup.mobile', $_POST['search']);
            $this->db->or_like('sup.gstin', $_POST['search']);
            $this->db->where('t1.user_type', 'supplier');
            $this->db->group_end();
		}
        
	
        if($limit!=null)
            return $this->db->get()->result();
        else
            return $this->db->get()->num_rows();
		return $this->db->get()->result();
    }
    public function customer($limit=null,$start=null)
    {
        if ($limit!=null) {
            $this->db->limit($limit, $start);
        }
        $this->db
        ->select('t1.*,t1.fname as vendor_fname,t1.lname as vendor_lname,t2.name as state_name,t3.name as city_name')
        ->from('customers t1')
        ->join('states t2', 't2.id = t1.state','left')        
        ->join('cities t3', 't3.id = t1.city','left')        
        ->where(['t1.is_deleted' => 'NOT_DELETED'])
        ->where(['t1.user_type' => 'customer'])
        ->order_by('t1.added','desc');
        if (@$_POST['search']) {
			$data['search'] = $_POST['search'];
            $this->db->group_start();
			$this->db->like('t1.fname', $_POST['search']);
			$this->db->or_like('t1.vendor_code', $_POST['search']);
            $this->db->or_like('t1.mobile', $_POST['search']);
            $this->db->or_like('t1.gstin', $_POST['search']);
            $this->db->where(['t1.is_deleted'=>'NOT_DELETED','t1.user_type'=>'customer']);
            $this->db->group_end();
		}
        if($limit!=null)
            return $this->db->get()->result();
        else
            return $this->db->get()->num_rows();
		return $this->db->get()->result();
    }
    function getData($tb, $data = 0, $order = null, $order_by = null, $limit = null, $start = null)
    {

        if ($order != null) {
            if ($order_by != null) {
                $this->db->order_by($order_by, $order);
            } else {
                $this->db->order_by('id', $order);
            }
        }

        if ($limit != null) {
            $this->db->limit($limit, $start);
        }

        if ($data == 0 or $data == null) {
            return $this->db->get($tb)->result();
        }
        if (@$data['search']) {
            $search = $data['search'];
            unset($data['search']);
        }
        return $this->db->get_where($tb, $data)->result();
    }
    
    public function Purchase($limit=null,$start=null)
    {
        if ($limit!=null) {
            $this->db->limit($limit, $start);
        }
        $this->db
        ->select("A1.id as purchase_id,A1.*,t1.*,t1.name AS vendor_name,t2.name as state_name,t3.name as city_name,t4.name as status_name")
        ->from('purchase A1')
        ->join('customers cus', 'cus.id = A1.supplier_id','left') 
        ->join('supplier_details t1', 't1.supplier_id = cus.id','left') 
        ->join('states t2', 't2.id = t1.state','left')        
        ->join('cities t3', 't3.id = t1.city','left') 
        ->join('purchase_order_status t4', 't4.id = A1.status','left')  
        ->where(['A1.is_deleted'=>'NOT_DELETED','cus.user_type'=>'supplier'])  
        ->order_by('A1.added','DESC');
        if (@$_POST['search']) {
			$this->db->like('t1.name', $_POST['search']);
            $this->db->or_like('t1.mobile', $_POST['search']);
			$this->db->or_like('t1.vendor_code', $_POST['search']);
            $this->db->or_like('A1.purchase_order_no', $_POST['search']);
            $this->db->or_like('t1.gstin', $_POST['search']);
		}
        if (@$_POST['status']) {
			$this->db->where('A1.status',$_POST['status']);
		}


		if (@$_POST['from_date']) {
			$from_date = $_POST['from_date'] .' 00:00:00';    
			$this->db->where('A1.purchase_order_date >=',$from_date);
		}

		if (@$_POST['end_date']) {
			$end_date = $_POST['end_date'] . ' 23:59:59';
			$this->db->where('A1.purchase_order_date <=',$end_date);
		}
        if($limit!=null)
            return $this->db->get()->result();
        else
            return $this->db->get()->num_rows();
    }
    public function get_vendor_opening($user_id)
    {
        $this->db->where('user_id',$user_id);
        $this->db->where('type','1');
        return $this->db->get('vendors_opening')->row();
    }
    public function customers($id)
    {
        $query = $this->db
        ->select('t1.*,t2.name as state_name,t3.name as city_name,t4.business_id,t4.id as shop_id,t4.shop_name')
        ->from('customers t1')
        ->join('states t2', 't2.id = t1.state','left')        
        ->join('cities t3', 't3.id = t1.city','left')        
        ->join('shops t4', 't4.id = t1.shop_id','left')        
		->where(['t1.is_deleted' => 'NOT_DELETED','t1.id'=>$id])    
        ->get();
		return $query->row();
    }
    public function getDatacustomers()
    {
        $query = $this->db
        ->select('t1.*,t2.*')
        ->from('customers t1')
        ->join('supplier_details t2', 't2.supplier_id = t1.id','left')         
		->where(['t1.is_deleted' => 'NOT_DELETED','t1.active'=>'1','user_type'=>'supplier'])    
        ->get();
		return $query->result();
    }
    
    public function vendor_opening($data,$user_id=null)
    {
        if ($this->get_vendor_opening($user_id)) {
            $this->db->where('user_id',$user_id);
            $this->db->update('vendors_opening',$data);
        }
        else{
            $this->db->insert('vendors_opening',$data);
        }
    }
     public function vendor_opening_new($data,$user_id=null)
    {
        if ($this->get_vendor_opening($user_id)) {
            $this->db->where(['user_id'=>$user_id,'type'=>'2']);
            $this->db->update('vendors_opening',$data);
        }
        else{
            $this->db->insert('vendors_opening',$data);
        }
    }
      public function vendor_opening_new2($data,$user_id=null)
    {
        if ($this->get_vendor_opening($user_id)) {
            $this->db->where(['user_id'=>$user_id,'type'=>'1']);
            $this->db->update('vendors_opening',$data);
        }
        else{
            $this->db->insert('vendors_opening',$data);
        }
    }
    
    public function vendor($id)
    {
        $query = $this->db
        ->select('t1.*,t2.name as state_name,t3.name as city_name,t4.business_id,t4.id as shop_id,t4.shop_name')
        ->from('vendors t1')
        ->join('states t2', 't2.id = t1.state','left')        
        ->join('cities t3', 't3.id = t1.city','left')        
        ->join('shops t4', 't4.id = t1.shop_id','left')        
		->where(['t1.is_deleted' => 'NOT_DELETED','t1.id'=>$id])    
        ->get();
		return $query->row();
    }
    public function customers_row($id)
    {
        $query = $this->db
        ->select('t1.*,sup.*,t2.name as state_name,t3.name as city_name,t4.business_id,t4.id as shop_id,t4.shop_name')
        ->from('customers t1')
        ->join('supplier_details sup','sup.supplier_id=t1.id','left')
        ->join('states t2', 't2.id = sup.state','left')        
        ->join('cities t3', 't3.id = sup.city','left')        
        ->join('shops t4', 't4.id = t1.shop_id','left')        
		->where(['t1.is_deleted' => 'NOT_DELETED','t1.id'=>$id])    
        ->get();
		return $query->row();
    }
    function Update($tb,$data,$cond) {

        $this->db->where($cond);

        if($this->db->update($tb,$data)) {

            return true;

        }

        return false;

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
    function Save($tb,$data){
		if($this->db->insert($tb,$data)){
			return $this->db->insert_id();
		}
		return false; 
	}
    public function search_supplier($name) {
        $this->db->like('sup.name', $name, 'both');
        $query = $this->db
        ->select('t1.*,t1.id as sup_id,sup.*')
        ->from('customers t1')
        ->join('supplier_details sup','sup.supplier_id=t1.id','left')       
        ->where(['user_type'=>'supplier','active'=>'1','t1.is_deleted' => 'NOT_DELETED','sup.is_deleted' => 'NOT_DELETED'])   
        ->get();
        return $query->result();
    }
    public function search_product($name) {
        $this->db->like('name', $name, 'both');
        $this->db->where(['is_deleted'=>'NOT_DELETED','active'=>'1']);
        $query = $this->db->get('products_subcategory');
        return $query->result();
    }
    
    public function get_supplier_details($supplierId) {
        $this->db
        ->select("t1.*, sup.name, t2.name as state_name, t3.name as city_name,sup.address,sup.pincode,sup.gstin")
        ->from('customers t1')
        ->join('supplier_details sup','sup.supplier_id=t1.id','left')
        ->join('states t2', 'sup.state = t2.id','left')
        ->join('cities t3', 'sup.city = t3.id','left')
        ->where(['t1.active'=>'1','t1.is_deleted' => 'NOT_DELETED','sup.is_deleted' => 'NOT_DELETED','t1.user_type'=>'supplier','t1.id'=>$supplierId]);
        $query = $this->db->get();
        return $query->row();
    }
    public function getItemCodeData($name) {
        $this->db->like('t1.product_code', $name);
        $this->db
        ->select('t1.*,t1.id as product_id,t2.mrp,t2.selling_rate,t2.qty,t3.offer_upto,t3.discount_type')
        ->from('products_subcategory t1')
        ->join('shops_inventory t2', 't2.product_id = t1.id','left')
        ->join('shops_coupons_offers t3', 't3.product_id = t1.id','left')
        ->where(['t1.is_deleted'=>'NOT_DELETED']);
        $query = $this->db->get();
        return $query->row();
    }
    public function getItemCodeDataInventory($product_id) {
        $this->db
        ->select('t1.*')
        ->from('shops_inventory t1')
        ->where('t1.product_id',$product_id);
        $query = $this->db->get();
        return $query->row();
    }
    
    public function getItemIDData($id) {
        
        $this->db
        ->select('t1.*,t1.id as product_id,t2.mrp,t2.selling_rate,t2.qty,t3.offer_upto,t3.discount_type')
        ->from('products_subcategory t1')
        ->join('shops_inventory t2', 't2.product_id = t1.id','left')
        ->join('shops_coupons_offers t3', 't3.product_id = t1.id','left')
        ->where(['t1.id'=>$id,'t1.is_deleted'=>'NOT_DELETED','t1.active'=>'1']);
        $query = $this->db->get();
        return $query->row();
    }
    
    public function getItemIDDataNew($id) {
        $this->db
        ->select('t1.*,t1.id as product_id')
        ->from('products_subcategory t1')
        ->where(['t1.id'=>$id,'t1.is_deleted'=>'NOT_DELETED']);
        $query = $this->db->get();
        return $query->row();
    }

    public function getPurchasedetails($id)
    {
        
        $this->db
        ->select("t1.*,t1.id as purchase_id,t2.name as vendor_name,t3.name as state_name,t4.name as city_name,t2.mobile,t2.address,t2.gstin,t2.pincode")
        ->from('purchase t1')
        ->join('supplier_details t2', 't2.supplier_id = t1.supplier_id','left')
        ->join('states t3', 't2.state = t3.id')
        ->join('cities t4', 't2.city = t4.id')
        ->where('t1.id', $id);
        $query = $this->db->get();
        return $query->row_array();
        
    }
    public function getPurchaseItemdetails($id,$vendor_id)
    {
        
        $this->db
        ->select('t1.*,t3.name,t3.product_code,t3.unit_type,t3.unit_type_id,t3.tax_value,t4.discount_type,t4.offer_upto,t5.id as inventtory_id,t5.qty as inventory_qty')
        ->from('purchase_items t1')
        ->join('purchase t2', 't2.id = t1.purchase_id','left')
        ->join('products_subcategory t3', 't3.id = t1.item_id','left')
        ->join('shops_coupons_offers t4', 't4.product_id = t1.item_id','left')
        ->join('shops_inventory t5', 't1.id = t5.purchase_item_id','left')
        ->where(['t1.purchase_id'=>$id]);
        $query = $this->db->get();
        return $query->result_array();
        
    }
    public function product($id)
    {
        $query = $this->db
        ->select('t1.*,t1.parent_cat_id,t2.id as cat_id,t2.name as cat_name,t2.is_parent,t3.id as main_cat_id,t3.name as main_cat_name,t3.is_parent as main_is_parent')
        ->from('products_subcategory t1')
        ->join('products_category t2', 't2.id = t1.parent_cat_id','left')        
        ->join('products_category t3', 't3.id = t1.sub_cat_id','left')        
        ->where(['t1.is_deleted' => 'NOT_DELETED','t1.id'=>$id])
        ->get();
		return $query->row();
        // return $this->db->get_where('products_subcategory',['id'=>$id])->row();
    }

    public function product_props($pid)
    {
       $query=$this->db->select('product_props_master.name,product_props_value.value,t5.name as flavour')
       ->from('product_props')
       ->join('product_props_master','product_props.props_id=product_props_master.id') 
       ->join('product_props_value','product_props.value_id=product_props_value.id')
       ->join('products_subcategory t4', 't4.id = product_props.product_id','left')  
        ->join('flavour_master t5', 't5.id = t4.flavour_id','left') 
       ->where(['product_props.product_id'=>$pid,'product_props.is_deleted'=>'NOT_DELETED','product_props_master.is_selectable'=>'3'])
       ->get();
       //echo $this->db->last_query();
       if($query)
       {
       $output=$query->result();
       
       return $output;
       }
       else
       {
        return false;
       }
    }
    public function delete_purchase($id)
    {
        $is_deleted = array('is_deleted' => 'DELETED');
        return $this->db->where('id', $id)->update('purchase', $is_deleted);
    }
    public function delete_inventory($id)
    {
        $is_deleted = array('is_deleted' => 'DELETED');
        return $this->db->where('id', $id)->update('shops_inventory', $is_deleted);
    }
    public function delete_inventory_log($data)
    {
        
        return $this->db->insert('shop_inventory_logs', $data);
    }
    public function delete_product($id)
    {
        $is_deleted = array('is_deleted' => 'DELETED');
        return $this->db->where('id', $id)->update('products_subcategory', $is_deleted);
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
		$details_html = $this->load->view('shop/master/customers/b2b/modals/business-details',['customer'=> $details], true);
		return [
			'status' => true,
			'message' => 'get customer details successfully',
			'data' => $details_html
		];			   
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
					   
		$details_html = $this->load->view('shop/master/customers/b2b/modals/pan-details',['customer'=> $details], true);
		return [
			'status' => true,
			'message' => 'get customer details successfully',
			'data' => $details_html
		];			   
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
					   
		$details_html = $this->load->view('shop/master/customers/b2b/modals/license-details',['customer'=> $details], true);
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
					   
		$details_html = $this->load->view('shop/master/customers/b2b/modals/add-credit-limit',['customer'=> $details], true);
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
?>