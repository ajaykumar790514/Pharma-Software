<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stocks extends CI_Controller {

	public function __construct()
    {
        parent::__construct();

        $data['user']  = $user         = $this->checkShopLogin();
        $this->check_role_menu();
    }

    public function isLoggedIn(){
        $is_logged_in = $this->session->userdata('shop_logged_in');
        if(!isset($is_logged_in) || $is_logged_in!==TRUE)
        {
            redirect(base_url());
            exit;
        }
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
    public function check_role_menu(){
        $data['user']  = $user         = $this->checkShopLogin();
        $shop_role_id = $user->role_id;
        $uri = $this->uri->segment(1);
        $role_menus = $this->admin_model->all_role_menu_data($shop_role_id);
        $url_array = array();
        if(!empty($role_menus))
        {
            foreach($role_menus as $menus)
            {
                array_push($url_array,$menus->url);
            }
            if(!in_array($uri,$url_array))
            {
                redirect(base_url());
            }
        }
        else
        {
            redirect(base_url());
            exit;
        }      
    } 

    public function index()
    {
        $data['user'] = $user =  $this->checkShopLogin();
        $menu_id = $this->uri->segment(2);
        $data['menu_id'] = $menu_id;
        $role_id = $user->role_id;
        $data['sub_menus'] = $this->admin_model->get_submenu_data($menu_id,$role_id);
        $data['title'] = 'Sales & Purchase Data';
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
        $page = 'shop/purchase/sales_purchase_data';
        $this->header_and_footer($page, $data);
    }
    
    public function purchases()
    {
        $data['user'] = $user =  $this->checkShopLogin();
        $menu_id = $this->uri->segment(2);
        $data['menu_id'] = $menu_id;
        $role_id = $user->role_id;
        $data['sub_menus'] = $this->admin_model->get_submenu_data($menu_id,$role_id);
        $data['title'] = 'Sales & Purchase Data';
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
        $page = 'shop/purchase/sales_data';
        $this->header_and_footer($page, $data);
    }
    
    public function stocks()
    {
        $data['user'] = $user =  $this->checkShopLogin();
        $menu_id = $this->uri->segment(2);
        $data['menu_id'] = $menu_id;
        $role_id = $user->role_id;
        $data['sub_menus'] = $this->admin_model->get_submenu_data($menu_id,$role_id);
        $data['title'] = 'Sales & Purchase Data';
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
        $page = 'shop/stocks/stocks_data';
        $this->header_and_footer($page, $data);
    }
    public function getStockData(){
        $array_cond_like=array();
        $array_cond['cat_id'] = $_GET['cat_id'];
        // $array_cond['active'] = '1';
        if(isset($_GET['filter']['product_id']) && $_GET['filter']['product_id']!=='0'){
            $array_cond['product_id'] = $_GET['filter']['product_id'];
        }
        if(isset($_GET['filter']['purchase_rate']) && $_GET['filter']['purchase_rate']!==''){
            $array_cond_like['purchase_rate'] = $_GET['filter']['purchase_rate'];
        }
        if(isset($_GET['filter']['mrp']) && $_GET['filter']['mrp']!== ''){
            $array_cond_like['mrp'] = $_GET['filter']['mrp'];
        }
        if(isset($_GET['filter']['qty']) && $_GET['filter']['qty']!==''){
            $array_cond_like['qty'] = $_GET['filter']['qty'];
        }
        if(isset($_GET['filter']['selling_rate']) && $_GET['filter']['selling_rate']!==''){
            $array_cond_like['selling_rate'] = $_GET['filter']['selling_rate'];
        } 
        if(isset($_GET['filter']['status']) && $_GET['filter']['status']!==''){
            $array_cond_like['status'] = $_GET['filter']['status'];
        }        
        $getData = $this->shops_inventory_model->getInventoryData(array('conditions'=>$array_cond,'conditions_like'=>$array_cond_like,'limit'=>$_GET['filter']['pageSize'],'offset'=>$_GET['filter']['pageSize']*($_GET['filter']['pageIndex']-1)));
        $array =array();
        $item_count=0;
        if($getData!==FALSE){
            foreach($getData as $data){
                $array[] = array(
                                    'id' => $data['id'],
                                    'img' => $data['img'],
                                    'product_id' => $data['product_id'],
                                    'vendor_id' => $data['vendor_id'],
                                    'is_igst' => $data['is_igst'],
                                    'qty' => $data['qty'],
                                    'purchase_rate' => $data['purchase_rate'],
                                    'mrp' => $data['mrp'],
                                    'selling_rate' => $data['selling_rate'],
                                    'status' => $data['status'],
                                    'mfg_date' => $data['mfg_date'],
                                    'expiry_date' => $data['expiry_date'],
                                    'total_value' => $data['total_value'],
                                    'total_tax' => $data['total_tax'],
                                    'invoice_no' => $data['invoice_no'],
                                    'invoice_date' => $data['invoice_date'],
                                    'vendor_name' => $data['vendor_name'],
                                    'tax_value' => $data['tax_value']
                );
            }
            $item_count = count($this->shops_inventory_model->getInventoryData(array('conditions'=>$array_cond)));
        }
       
        echo json_encode(array('data'=>$array,'itemsCount'=>$item_count));
    }
    public function product_list(){
//        $getData = $this->products_subcategory_model->getRows(array('conditions'=>$_POST));
//        // $getData = $this->products_subcategory_model->getRows(array('conditions'=>array('is_deleted'=>'NOT_DELETED')));
//        $array = array(array('id'=>"0",'name'=>"Select any one product"));
//        // $array = array();
//        foreach($getData as $data){
//            $temp_array = array(
//                                'id' => $data['id'],
//                                'name' => str_pad($data['product_code'], 6, '0', STR_PAD_LEFT).' - '.$data['name'],
//                                'gst' => $data['tax_value']
//            );
//            array_push($array,$temp_array);
//        }
//        echo json_encode($array);
//        //return TRUE;
        
        $getData = $this->products_subcategory_model->getRowsWithInventory(array('conditions'=>$_POST));
        // $getData = $this->products_subcategory_model->getRows(array('conditions'=>array('is_deleted'=>'NOT_DELETED')));
        $array = array(array('id'=>"0",'name'=>"Select any one product"));
        // $array = array();
        foreach($getData as $data){
            $temp_array = array(
                                'id' => $data['id'],
                                'name' => str_pad($data['product_code'], 6, '0', STR_PAD_LEFT).' - '.$data['name'].' -  ('.$data['qty'].')',
                                'gst' => $data['tax_value']
            );
            array_push($array,$temp_array);
        }
        echo json_encode($array);
    }
    public function vendor_list(){
        $getData = $this->products_subcategory_model->get_vendors(array('conditions'=>$_POST));
        // $getData = $this->products_subcategory_model->getRows(array('conditions'=>array('is_deleted'=>'NOT_DELETED')));
        $array = array();
        // $array = array(array('id'=>"0",'name'=>"Select any one vendor"));
        foreach($getData as $data){
            $temp_array = array(
                                'id' => $data['id'],
                                'name' => str_pad($data['vendor_code'], 6, '0', STR_PAD_LEFT).' - '.$data['name'],
            );
            array_push($array,$temp_array);
        }
        echo json_encode($array);
        //return TRUE;
    }

    public function header_and_footer($page, $data)
    {
        $data['user'] = $user =  $this->checkShopLogin();
        $shop_id     = $user->id;
        $shop_role_id     = $user->role_id;
        $data['shop_menus'] = $this->admin_model->get_role_menu_data($shop_role_id);        
        $data['all_menus'] = $this->admin_model->get_data1('tb_admin_menu','status','1');
		$shop_details = $this->shops_model->get_shop_data($shop_id);
        $template_data = array(
        'menu'=> $this->load->view('template/menu',$data,TRUE),
        'main_body_data'=> $this->load->view($page,$data,TRUE),
        'shop_photo'=>$shop_details->logo
        );
            $this->load->view('template/main_template',$template_data);
    }

    public function Purchase($action = null, $p1 = null, $p2 = null, $p3 = null)
    {
        $data['user'] = $user =  $this->checkShopLogin();  
        $shop_id     = $user->id;
        switch ($action) {
            case null:
                $data['title']          = 'Purchase';
                $data['menu_id']        = $uri = $this->uri->segment(2);
                $data['tb_url']         = base_url() . 'purchase/tb';
                $data['new_url']        = base_url() . 'purchase/create';
                $data['search']	 		= $this->input->post('search');
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $data['orderStatus']    = $this->master_model->getData('purchase_order_status',['active'=>'1']);
                $page                   = 'shop/purchase/index';
                $this->header_and_footer($page, $data);
                break;

            case 'tb':
                $data['search'] = '';
                if (@$_POST['search']) {
                    $data['search'] = $_POST['search'];
                }
                
                $this->load->library('pagination');
                $config = array();
                $config["base_url"]         = base_url() . "purchase/tb/";
                $config["total_rows"]       = $this->shops_vendor_model->Purchase();
                $data['total_rows']         = $config["total_rows"];
                $config["per_page"]         = 10;
                $config["uri_segment"]      = 3;
                $config['attributes']       = array('class' => 'pag-link');
                $config['full_tag_open']    = "<div class='pag'>";
                $config['full_tag_close']   = "</div>";
                $config['first_link']       = '&lt;&lt;';
                $config['last_link']        = '&gt;&gt;';
                $this->pagination->initialize($config);
                $data["links"]              = $this->pagination->create_links();
                $data['page']               = $page = ($p1 != null) ? $p1 : 0;
                $data['per_page']           = $config["per_page"];
                $data['purchase']           = $this->shops_vendor_model->Purchase($config["per_page"], $page);
                $data['shop_details'] = $this->shops_model->get_shop_data($shop_id);
                $data['update_url']         = base_url() . 'purchase/update-purchase/';
                $page                       = 'shop/purchase/tb';
                $this->load->view($page, $data);
                break;
                case 'details':
                    $data['title']          = 'Purchase';
                    $data['orderid']         = $p1;
                    $page                       = 'shop/purchase/details';
                    $shop_id     = $user->id;
                    $shop_role_id     = $user->role_id;
                    $data['shop_menus'] = $this->admin_model->get_role_menu_data($shop_role_id);
                    $data['all_menus'] = $this->admin_model->get_data1('tb_admin_menu','status','1');
                    $shop_details = $this->shops_model->get_shop_data($shop_id);
                    $data['shop_currency']=$shop_details->currency;
                    $data['orderStatus']=$this->purchase_status_master_model->getRows();
                    $data['orderData']=$this->purchase_model->getRows(array('conditions'=>array('id'=>$p1)));
                    $data['orderItems']=$this->purchase_items_model->getRows(array('conditions'=>array('purchase_id '=>$p1)));
                    $data['menu_url'] = $this->uri->segment(1);
                    $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                    $this->header_and_footer($page, $data); 
                break; 
                case  'updateOrderStatus':
                    $oder_id = $_POST['item']['id'];
                    $rs = $this->purchase_model->get_row_order($oder_id);
                    
                    $checkExisting = $this->purchase_model->getRows2($_POST['item']['id']);
                    
                    if($checkExisting!==FALSE){
                        $this->purchase_model->updateRow($_POST['item']['id'],array('status'=>$_POST['item']['status']));
                        $logdata = array(
                            'status_id'=>$_POST['item']['status'],
                            'purchase_id'=>$_POST['item']['id'],
                        );
                        $this->purchase_status_master_model->SaveLog($logdata);
                    }   
                break; 
                case 'new-purchase':
                    $data['menu_id']        = $p1;
                    $data['title']          = 'Add New Purchase';
                    $data['new_url']        = base_url() . 'purchase/create';
                    $data['remote']             = base_url().'shop-master-data/remote/products/';
                    $data['tax_slabs'] = $this->master_model->get_data('tax_slabs','active','1');
                    $data['properties']         = $this->master_model->view_data('product_props_master');
                    $data['unit_type']          = $this->master_model->get_data('unit_master','active','1');
                    $data['brands']          = $this->master_model->get_data('brand_master','active','1');
                    $data['flavours']          = $this->master_model->get_data('flavour_master','active','1');
                    $data['offers']           = $this->master_model->getOffers();
                    $data['parent_cat'] = $this->master_model->get_parent_category();
                    $data['parent_id'] = $this->master_model->get_parent_id();
                    $data['categories'] = $this->master_model->get_data('products_category','is_parent !=','0');
                    $data['all_categories'] = $this->db->get_where('products_category',['active' =>'1', 'is_deleted' => 'NOT_DELETED'])->result();
                    $data['suppliers']      = $this->shops_vendor_model->getDatacustomers();
                    $data['shop_details'] = $this->shops_model->get_shop_data($shop_id);
                    $page                       = 'shop/purchase/new-purchase';
                    $data['menu_url'] = $this->uri->segment(1);
                    $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                    $this->header_and_footer($page, $data); 
                break; 
                case 'search_supplier':
                    $name = $this->input->post('contactname');
                    $suppliers = $this->shops_vendor_model->search_supplier($name);
                    $results1 = [];
                    foreach ($suppliers as $supplier) {
                        $results1[] = [
                            'id' => $supplier->sup_id,
                            'text' => $supplier->name
                        ];
                    }
                    $results = array(
                        'items' => $results1,
                        'total_count' => count($results1) 
                    );
                    echo json_encode($results);
                break;
                case 'search_product':
                    $name = $this->input->post('contactname');
                    $products = $this->shops_vendor_model->search_product($name);
                    $results1 = [];
                    foreach ($products as $product) {
                        $results1[] = [
                            'id' => $product->id,
                            'text' => $product->name
                        ];
                    }
                    $results = array(
                        'items' => $results1,
                        'total_count' => count($results1) 
                    );
                    echo json_encode($results);
                break;    
                case 'get_supplier_details':
                $supplierId = $this->input->post('supplierId');
                $exists = $this->shops_vendor_model->get_supplier_details($supplierId);
                if($exists)
                {
                    $response = array(
                        'success' =>true,
                        'row' => $exists
                    );
                }else{
                    $response = array(
                        'success' =>false,
                        'row' => $exists
                    );
                }
               
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
               break;
               case 'supplier-modal':
                $data['Name'] = $p1;
                $data['remote']             = base_url().'shop-master-data/remote/vendor/';
                $data['action_url']         = base_url().'purchase/supplier-save';
                $data['states']  = $this->shops_vendor_model->view_data('states');
                $data['cities']  = $this->shops_vendor_model->view_data('cities');
                $page                       = 'shop/purchase/new-supplier';
                $this->load->view($page, $data);
                break;
                case 'supplier-save':
                    $id = $p1;
                    $return['res'] = 'error';
                    $return['msg'] = 'Not Saved!';
                    if ($this->input->server('REQUEST_METHOD')=='POST') { 
                      
                        $shop_id     = $user->id;
                        $datamain = array(
                                'mobile'              => $this->input->post('mobile'),
                                'user_type'        =>'supplier',
                                'shop_id'        => $shop_id,
                                'active'        =>'1',
                            );
                        if ($insert_id=$this->shops_vendor_model->Save('customers',$datamain)) {
                            $data = array(
                                'supplier_id'=>$insert_id,
                                'name'     => $this->input->post('fname'),
                                'mobile'              => $this->input->post('mobile'),
                                'alternate_mobile'   => $this->input->post('alternate_mobile'),
                                'state'      => $this->input->post('state'),
                                'city'        => $this->input->post('city'),
                                'address'       => $this->input->post('address'),
                                'email'        => $this->input->post('email'),
                                'gstin'        => $this->input->post('gstin'),
                                'vendor_code'        => $this->input->post('vendor_code'),
                                'pincode'        => $this->input->post('pincode'),
                            );
                            $this->shops_vendor_model->Save('supplier_details',$data);
                            $opening = array(
                                'user_id' => $insert_id,
                                'dr_cr' => $this->input->post('dr_cr'),
                                'amount' => $this->input->post('amount'),
                                'remark' => $this->input->post('remark'),
                                );
                                  $this->shops_vendor_model->vendor_opening($opening);
                                $exists = $this->shops_vendor_model->get_supplier_details($insert_id);
                                if($exists)
                                {
                                    $return['res'] = 'success';
                                    $return['msg'] = 'Saved.';
                                    $return['row'] = $exists;
                                }else{
                                    $return['res'] = 'success';
                                    $return['msg'] = 'Saved.';
                                    $return['row'] = $exists;
                                }
                             
                            }
                    }
                    echo json_encode($return);
                break;
                case 'getItemCodeData':
                    $itemCode = $this->input->post('itemCode');
                    $row = $this->shops_vendor_model->getItemCodeData($itemCode);
                    $row2 = $this->shops_vendor_model->getItemCodeDataInventory(@$row->product_id);
                    if ($row) {
                        if($row2)
                        {
                            $response = array(
                                'success' => 'YES_INVENTORY',
                                'rowData' => $row,
                            );
                        }else{
                            $response = array(
                                'success' => 'NO_INVENTORY',
                                'rowData' => $row,
                            );
                        }
                      
                    } else {
                        $response = array(
                            'success' => false,
                            'message' => 'No data found for the specified item code.',
                        );
                    }
                    echo json_encode($response);
                    break;
                case 'add-new-product':
                    $id = $p1;
                    $return['res'] = false;
                    $return['msg'] = 'Not Saved!';
    
                    if ($this->input->server('REQUEST_METHOD')=='POST') { 
                      
                            $tax_slab = explode(",",$this->input->post('tax_id'));
                            $cat_id = count($this->input->post('cat_id'));
                            $namepro = $this->input->post('name');
                            $product_code=$this->input->post('product_code');
                             $productexist = $this->db->get_where('products_subcategory',['name'=>$namepro])->row();
                             if($productexist)
                             {
                                $return['existpro'] = true;
                                echo json_encode($return);
                                die();
                             }
                             $productexist2 = $this->db->get_where('products_subcategory',['product_code'=>$product_code])->row();
                             if($productexist2)
                             {
                                $return['existcode'] = true;
                                echo json_encode($return);
                                die();
                             }
                            $data = array(
                                    'url'=>$convertedName,
                                    'tax_id'     => $tax_slab[0],
                                    'tax_value'     => $tax_slab[1],
                                    'name'              => $this->input->post('name'),
                                    'search_keywords'   => $this->input->post('search_keywords'),
                                    'product_code'      => $this->input->post('product_code'),
                                    'unit_type'=>'PIECE',
                                    'unit_type_id'=>'2',
                                    'unit_value'=>'1',
                                    'description'       => $this->input->post('description'),
                                    'sku'        => $this->input->post('sku'),
                                    'meta_title'       => $this->input->post('meta_title'),
                                    'meta_keywords'        => $this->input->post('meta_keywords'),
                                    'meta_description'        => $this->input->post('meta_description'),
                                );
                               
                            if ($result = $this->master_model->add_product($data)) {
                                logs($user->id,$result,'ADD','Add  Product');
                                for ($i=0; $i < $cat_id; $i++) { 
                                    $data_cat_id = array(
                                        'pro_id'=>$result,
                                        'cat_id'=>$this->input->post('cat_id')[$i],
                                    );
                                    $mapid=$this->master_model->add_cat_pro_map($data_cat_id);
                                    $msg = 'Cat Pro Maps'.$id.'-'.$this->input->post('cat_id')[$i];
                                    logs($user->id,$mapid,'ADD',$msg);
                                }
                                   // Apply offer code
                                   if($this->input->post('NewOffer') && is_numeric($this->input->post('NewOffer'))) {
                                   $getOffer = $this->master_model->getRow('coupons_and_offers',['id'=>$this->input->post('NewOffer')]);
                                   $offerdata = array(
                                    'offer_assosiated_id'     => $this->input->post('NewOffer'),
                                    'product_id'     => $result,
                                    'shop_id'     => '6',
                                    'offer_associated'     => @$getOffer->value,
                                    'offer_upto'     => @$getOffer->value,
                                    'discount_type'     => @$getOffer->discount_type,
                                );
                            
                                $this->offers_model->add_data('shops_coupons_offers',$offerdata);
                                logs($user->id,$result,'ADD','Apply Offer Product');
                            }
                                  $rowCountArray = $this->input->post('rowCount');
                                  if($rowCountArray){
                                    foreach ($rowCountArray as $index => $rowCount) {
                                        $propId = $this->input->post('propId')[$index];
                                        // $propName = $this->input->post('propName')[$index];
                                        // $propNameValue = $this->input->post('propNameValue')[$index];
                                        $propTypeId = $this->input->post('prop_typeID')[$index];

                                        $data = array(
                                            'props_id' => $propId,
                                            'product_id' => $result,
                                            'value_id' => $propTypeId
                                        );

                                        $this->db->insert('product_props', $data);
                                    }
                                }
                                $exists = $this->shops_vendor_model->getItemIDDataNew($result);
                                if($exists)
                                {
                                    $exists->mrp =  $this->input->post('NewMrp'); 
                                    $exists->selling_rate = $this->input->post('NewSellingRate');
                                    $exists->qty =  $this->input->post('NewQty');
                                    $exists->landing_cost =  $this->input->post('NewLandingCost'); 
                                    $exists->purchase_rate = $this->input->post('NewPurchaseRate');
                                    $exists->tax_value = $this->input->post('NewTaxRate');
                                    $exists->tax_amount = $this->input->post('NewTaxAmount');
                                    if($this->input->post('NewOffer') && is_numeric($this->input->post('NewOffer'))) {
                                    $exists->NewOfferValue = $this->input->post('NewOfferValue');
                                    $exists->offer_upto =  @$getOffer->value ? @$getOffer->value : '';
                                    $exists->discount_type =  @$getOffer->discount_type ? @$getOffer->discount_type : '2';
                                    }else{
                                        $exists->NewOfferValue = '0';
                                        $exists->offer_upto = '0';
                                        $exists->discount_type =  '2';
                                    }
                                    $return['res'] = true;
                                    $return['msg'] = 'Saved.';
                                    $return['rowData'] = $exists;
                                }else{
                                    $return['res'] = false;
                                    $return['msg'] = 'Not Saved.';
                                    $return['rowData'] = $exists;
                                }
                            }
                    }
                    echo json_encode($return);
               break;
               case 'add-purchase':
                $return['status'] = 'error';
                $return['message'] = 'Not Saved!';
                if ($this->input->server('REQUEST_METHOD')=='POST') { 
                // Basic Details 
                $supplier_id = $this->input->post('supplier') ? $this->input->post('supplier'):$this->input->post('supplier2') ;
                $purchase_order_date = $this->input->post('purchase_order_date');
                $purchase_order_no = $this->input->post('purchase_order_no');
                $shipping_date = $this->input->post('shipping_date');
                $shipping_note = $this->input->post('shipping_note');
                $notes = $this->input->post('notes');
                
                
                // Product Total
                $flatDiscountRate = $this->input->post('flatDiscount');
                $GrossTotal = $this->input->post('GrossTotal');
                $FinalDiscountAmount = $this->input->post('FinalDiscount');
                $FinalTaxWithAmount = $this->input->post('FinalTaxWithAmount');
                $FinalTax = $this->input->post('FinalTax');
                $FinalTotal = $this->input->post('FinalTotal');
                $FinalQty = $this->input->post('FinalQty');

                
                // Get the dynamic inputs using $_POST directly 
                $itemCodes = $this->input->post('itemCode');
                $productVariants = $this->input->post('productVarientId');
                $quantities = $this->input->post('quantity');
                $unitCosts = $this->input->post('unitCost');
                $taxAmounts = $this->input->post('taxAmount');
                $taxRates = $this->input->post('taxRate');
                $landingCosts = $this->input->post('landingCost');
                $margins = $this->input->post('margin');
                $netAmounts = $this->input->post('netAmount');
                $discount_value = $this->input->post('discount_value');
                $mrp = $this->input->post('mrpp');
                $SellRate = $this->input->post('SellRate');
                $purchase = array(
                'supplier_id' => $supplier_id,
                'shop_id' => $user->id,
                'purchase_order_date' => $purchase_order_date,
                'purchase_order_no' => $purchase_order_no,
                'shipping_date' => $shipping_date,
                'shipping_note' => $shipping_note,
                'flat_discount' => $flatDiscountRate,
                'flat_discount_value' => $FinalDiscountAmount,
                'total_amount' => $FinalTotal,
                'total_tax' => $FinalTax,
                'gross_total' => $GrossTotal,
                'total_with_tax' => $FinalTaxWithAmount,
                'total_qty' => $FinalQty,
                'remark'=>$notes,
                'status'=>'3',
                );
                $this->db->insert('purchase',$purchase);
                $insert_id = $this->db->insert_id();
                if($insert_id){
                // Add case register
         
                $ledger['type']         = 'Purchase';
                $ledger['customer_id']  = $supplier_id;
                $ledger['order_id']     = NULL;
                $ledger['inventory_id'] = $insert_id;
                $ledger['dr']           = $FinalTotal;
                $ledger['reference_no'] = $purchase_order_no;
                $ledger['PaymentDate']  = $purchase_order_date;
                $ledger['shop_id']      = $user->id;
                $ledger['txn_type']     = 4;
                $this->load->model('cash_register_model');
                $this->cash_register_model->insertRow($ledger);

                // Loop through each row  
                foreach ($itemCodes as $index => $itemCode) {
                      // get products dynamic selectable props while placing order
                   
                        $dataitem = array(
                        'purchase_id' => $insert_id,
                        'item_id' => $productVariants[$index],
                        'qty' => $quantities[$index],
                        'unit_cost' => $unitCosts[$index],
                        'mrp' => $mrp[$index],
                        'tax' => $taxRates[$index],
                        'tax_value' => $taxAmounts[$index],
                        'landing_cost' => $landingCosts[$index],
                        'margin' => $margins[$index],
                        'total' => $netAmounts[$index],
                        'discount'=>$flatDiscountRate,
                        'discount_value'=>$discount_value[$index],
                    );
                    $this->db->insert('purchase_items',$dataitem);
                    $insert_id2 = $this->db->insert_id();
                    $data_shop_inventry = array(
                        'vendor_id' => $supplier_id,
                        'product_id'=>$productVariants[$index],
                        'qty'=>$quantities[$index],
                        'purchase_rate'=>$unitCosts[$index],
                        'mrp'=>$mrp[$index],
                        'selling_rate'=>$mrp[$index],
                        'shop_id'=>$user->id,
                        'tax_value'=>$taxRates[$index],
                        'total_value'=>$netAmounts[$index],
                        'total_tax'=>$taxAmounts[$index],
                        'purchase_item_id'=>$insert_id2,
                        );
                        $data_shop_inventry_log = array(
                            'vendor_id' => $supplier_id,
                            'product_id'=>$productVariants[$index],
                            'qty'=>$quantities[$index],
                            'purchase_rate'=>$unitCosts[$index],
                            'mrp'=>$mrp[$index],
                            'selling_rate'=>$mrp[$index],
                            'shop_id'=>$user->id,
                            'tax_value'=>$taxRates[$index],
                            'total_value'=>$netAmounts[$index],
                            'total_tax'=>$taxAmounts[$index],
                            );

                    if ($insert_stock = $this->master_model->add_data('shops_inventory',$data_shop_inventry)) {
                        logs($user->id,$insert_stock,'ADD','Add Purchase Inventory');
                        $data_shop_inventry_log['action']="ADD_INVENTORY";
                        $data_shop_inventry_log['shops_inventory_id']=$insert_stock;
                        $this->master_model->add_data('shop_inventory_logs', $data_shop_inventry_log);

                    }
                   
                  }
                  $return['status'] = 'success';
                  $return['message'] = 'Purchase successfully saved!.';
                 }
               }
                echo json_encode($return);
               break;
               case 'getOffetValue':
                $id = $_POST['id'];
                $result = $this->db->get_where('coupons_and_offers', ['id' => $id])->row();
                if ($result) {
                    $return['res'] = true;
                    $return['row'] = $result;
                } else {
                    $return['res'] = false;
                    $return['message'] = 'Offer not found';
                }
                echo json_encode($return);                
               break;
               case 'get_product_details':
                $id = $_POST['id'];
                $row = $this->shops_vendor_model->getItemIDData($id);
                $row2 = $this->shops_vendor_model->getItemCodeDataInventory(@$row->product_id);
                if ($row) {
                    if($row2)
                    {
                        $response = array(
                            'success' => 'YES_INVENTORY',
                            'rowData' => $row,
                        );
                    }else{
                        $response = array(
                            'success' => 'NO_INVENTORY',
                            'rowData' => $row,
                        );
                    }
                  
                } else {
                    $response = array(
                        'success' => false,
                        'message' => 'No data found for the specified item code.',
                    );
                }
                echo json_encode($response); 
                break;
                case 'get_properties_value':
                    $prop_id = $this->input->post('prop_id');
                    $value_id = $this->input->post('value_id');
        
                    $data['property_value'] = $this->master_model->get_data('product_props_value', 'prop_id',$prop_id);
                    foreach($data['property_value'] as $row): 
                        if ($value_id == $row->id) {
                            echo '<option value="'.$row->id.','.$row->value.'" selected>'.$row->value.'</option>';
                        }
                        else{
                            echo '<option value="'.$row->id.','.$row->value.'">'.$row->value.'</option>';
                        }                
                    endforeach;
                break;
                case 'check_purchase_order':
                    $purchase_order_no = $this->input->post('purchase_order_no');
                    $purchase_order_exists = $this->db->get_where('purchase',['purchase_order_no'=>$purchase_order_no,'is_deleted'=>'NOT_DELETED'])->row();
                    if($purchase_order_exists)
                    {
                        $response = array(
                            'exists' => true
                        );
                    }else{
                        $response = array(
                            'exists' => false
                        );
                    }
                   
                    echo json_encode($response);
                break;
                case 'get_product_code':
                    $productId = $this->input->post('productId');
                    $productCode = $this->master_model->getRow('products_subcategory',['id'=>$productId]);
            
                    if ($productCode) {
                        echo json_encode(array('success' => true, 'productCode' => $productCode->product_code));
                    } else {
                        echo json_encode(array('success' => false));
                    }
                break;
                case 'get_product_edit_details':
                    $productId = $this->input->post('productId');
                    $UnitCost = $this->input->post('UnitCost');
                    $productDetails = $this->master_model->getRow('products_subcategory', ['id' => $productId]); 
                    if ($productDetails) {
                    $data['pid'] = $productId;
                    $data['product'] = $productDetails;
                    $data['action_url']     = base_url().'purchase/product-edit/'.$productId;
                    $data['value']          = $this->shops_vendor_model->product($productId);
                    $data['cat_pro_map']    = $this->master_model->get_cat_pro_map($productId);
                    $data['remote']         = base_url().'shop-master-data/remote/products/'.$productId;
                    $data['offers']           = $this->master_model->getOffers();
                    $data['tax_slabs'] = $this->master_model->get_data('tax_slabs','active','1');
                    $data['shops_inventory'] = $this->master_model->get_data2_row('shops_inventory',['product_id'=>$data['value']->id]);
                     $data['applyoffer'] = $this->master_model->getRow('shops_coupons_offers',['product_id'=>$productId,'is_deleted'=>'NOT_DELETED','shop_id'=>'6']);
                     $data['parent_cat'] = $this->master_model->get_parent_category();
                     $data['parent_id'] = $this->master_model->get_parent_id();
                     $data['categories'] = $this->master_model->get_data('products_category','is_parent !=','0');
                     $data['all_categories'] = $this->db->get_where('products_category',['active' =>'1', 'is_deleted' => 'NOT_DELETED'])->result();
                     $data['UnitCost'] = $UnitCost;
                     $data['property_val']    = $this->master_model->get_property_val($productId);
                        $html = $this->load->view('shop/purchase/editProduct_view', $data, true); 
                        echo json_encode(array('success' => true, 'html' => $html));
                    } else {
                        echo json_encode(array('success' => false));
                    }
                 break;
                 case 'product-edit':
                    $id = $p1;
                    $return['res'] = 'error';
                    $return['msg'] = 'Not Saved!';
                    if ($this->input->server('REQUEST_METHOD')=='POST') { 
                            $UnitCost = $this->input->post('UnitCost');
                            $tax_slab = explode(",",$this->input->post('tax_id'));
                            $cat_id = count($this->input->post('cat_id'));
                            $this->db->delete('cat_pro_maps', array('pro_id' => $id));
                            logs($user->id,$id,'DELETE','Cat Pro Maps');
                            $namepro = $this->input->post('name');
                            $data = array(
                                    'url'=>$this->input->post('url') ? remove_spaces($this->input->post('url')) : $convertedName,
                                    'tax_id'     => $tax_slab[0],
                                    'tax_value'     => $tax_slab[1],
                                    'name'              => $this->input->post('name'),
                                    'search_keywords'   => $this->input->post('search_keywords'),
                                    'product_code'      => $this->input->post('product_code'),
                                    'unit_type'=>'PIECE',
                                    'unit_type_id'=>'2',
                                    'unit_value'=>'1',
                                    'description'       => $this->input->post('description'),
                                    'sku'        => $this->input->post('sku'),
                                    'meta_title'       => $this->input->post('meta_title'),
                                    'meta_keywords'        => $this->input->post('meta_keywords'),
                                    'meta_description'        => $this->input->post('meta_description'),
                                );
                            if($this->master_model->edit_product($data,$id)){
                                logs($user->id,$id,'EDIT','Edit Product');
                                for ($i=0; $i < $cat_id; $i++) { 
                                    $data_cat_id = array(
                                        'pro_id'=>$id,
                                        'cat_id'=>$this->input->post('cat_id')[$i],
                                       
                                    );
                                   $mapid= $this->master_model->add_cat_pro_map($data_cat_id);
                                    $msg = 'Cat Pro Maps'.$id.'-'.$this->input->post('cat_id')[$i];
                                    logs($user->id,$mapid,'ADD',$msg);
                           }
                           $rowCountArray = $this->input->post('rowCount');
                           if($rowCountArray){
                           foreach ($rowCountArray as $index => $rowCount) {
                               $propId = $this->input->post('propId')[$index];
                               $propTypeId = $this->input->post('prop_typeID')[$index];
                               $dataproperty = array(
                                   'props_id' => $propId,
                                   'product_id' => $id,
                                   'value_id' => $propTypeId
                               );
                               $count = $this->master_model->Counter('product_props', array('product_id'=>$id,'props_id'=>$propId,'value_id'=> $propTypeId));
                               if($count >=1){
                              }else{
                               $this->db->insert('product_props', $dataproperty);
                              }
                           }
                        }
                           $UnitCostTotal = ($UnitCost * $tax_slab[1] ) / 100;
                           $return['res'] = 'success';
                           $return['taxRate'] = $tax_slab[1];
                           $return['taxAmount'] = $UnitCostTotal;
                           $return['msg'] = 'Edit Successfull.';
                           $return['name'] = $this->input->post('name');
                           $return['id'] = $id;
                        }
                    }
                    echo json_encode($return);
                    break;
                
                case 'update-purchase':
                    $data['menu_id']        = $p1;
                    $data['purchase_id']        = $p1;
                    $data['menu_url'] = $this->uri->segment(1);
                    $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                    $data['title']          = 'Update Purchase';
                    $data['new_url']        = base_url() . 'purchase/create';
                    $data['remote']             = base_url().'shop-master-data/remote/products/';
                    $data['tax_slabs'] = $this->master_model->get_data('tax_slabs','active','1');
                    $data['properties']         = $this->master_model->view_data('product_props_master');
                    $data['unit_type']          = $this->master_model->get_data('unit_master','active','1');
                    $data['brands']          = $this->master_model->get_data('brand_master','active','1');
                    $data['flavours']          = $this->master_model->get_data('flavour_master','active','1');
                    $data['offers']           = $this->master_model->getOffers();
                    $data['parent_cat'] = $this->master_model->get_parent_category();
                    $data['parent_id'] = $this->master_model->get_parent_id();
                    $data['categories'] = $this->master_model->get_data('products_category','is_parent !=','0');
                    $data['all_categories'] = $this->db->get_where('products_category',['active' =>'1', 'is_deleted' => 'NOT_DELETED'])->result();
                    $data['suppliers']      = $this->shops_vendor_model->getDatacustomers();
                    $data['shop_details'] = $this->shops_model->get_shop_data($shop_id);
                    $data['purchase']     = $this->shops_vendor_model->getPurchasedetails($p1);
                    $data['purchase_item']     = $this->shops_vendor_model->getPurchaseItemdetails($p1,$data['purchase']['supplier_id']);
                    $data['products'] = $this->master_model->getData('products_subcategory',['is_deleted'=>'NOT_DELETED']);
                    $page                       = 'shop/purchase/update-purchase';
                   
                    $this->header_and_footer($page, $data);
                break;  
                
                case 'delete_prop_val':
                    $id = $p1;
                    $return['success'] = false;
                    if($this->master_model->delete_prop_val($id))
                    {                 
                        $return['success'] = true;
                    }
                    echo json_encode($return);
             break;  
             case 'get_duplicate_details':
                $productId = $this->input->post('productId');
                $UnitCost = $this->input->post('UnitCost');
                $landingCost = $this->input->post('landingCost');
                $mrpp = $this->input->post('mrp');
                $sellingRate = $this->input->post('sellingRate');
                $qty = $this->input->post('qty');
                $productDetails = $this->master_model->getRow('products_subcategory', ['id' => $productId]); 
                if ($productDetails) {
                $data['pid'] = $productId;
                $data['product'] = $productDetails;
                $data['action_url']     = base_url().'purchase/product-duplicate';
                $data['value']          = $this->master_model->product($productId);
                $data['cat_pro_map']    = $this->master_model->get_cat_pro_map($productId);
                $data['remote']         = base_url().'shop-master-data/remote/products/';
                $data['offers']           = $this->master_model->getOffers();
                $data['tax_slabs'] = $this->master_model->get_data('tax_slabs','active','1');
                $data['shops_inventory'] = $this->master_model->get_data2_row('shops_inventory',['product_id'=>$data['value']->id]);
                 $data['applyoffer'] = $this->master_model->getRow('shops_coupons_offers',['product_id'=>$productId,'is_deleted'=>'NOT_DELETED','shop_id'=>'6']);
                 $data['parent_cat'] = $this->master_model->get_parent_category();
                 $data['parent_id'] = $this->master_model->get_parent_id();
                 $data['categories'] = $this->master_model->get_data('products_category','is_parent !=','0');
                 $data['all_categories'] = $this->db->get_where('products_category',['active' =>'1', 'is_deleted' => 'NOT_DELETED'])->result();
                 $data['UnitCost'] = $UnitCost;
                 $data['landingCost'] = $landingCost;
                 $data['mrp'] = $mrpp;
                 $data['sellingRate'] = $sellingRate;
                 $data['qty'] = $qty;
                 $data['productId'] = $productId;
                 $data['property_val']    = $this->master_model->get_property_val($productId);
                    $html = $this->load->view('shop/purchase/duplicate', $data, true); 
                    echo json_encode(array('success' => true, 'html' => $html));
                } else {
                    echo json_encode(array('success' => false));
                }
             break;
             case 'product-duplicate':
                $return['res'] = false;
                $return['msg'] = 'Not Saved!';
                if ($this->input->server('REQUEST_METHOD')=='POST') { 
                $tax_slab = explode(",",$this->input->post('tax_id'));
                $cat_id = count($this->input->post('cat_id'));
                $duplicate_id = $this->input->post('duplicate_id');
                $namepro = $this->input->post('name');
                $convertedName =  $this->url_character_remove($namepro);
                $data = array(
                        'url'=>$convertedName,
                        'tax_id'     => $tax_slab[0],
                        'tax_value'     => $tax_slab[1],
                        'name'              => $this->input->post('name'),
                        'search_keywords'   => $this->input->post('search_keywords'),
                        'product_code'      => $this->input->post('product_code'),
                        'unit_type'=>'PIECE',
                        'unit_type_id'=>'2',
                        'unit_value'=>'1',
                        'description'       => $this->input->post('description'),
                        'sku'        => $this->input->post('sku'),
                        'brand_id'        => $this->input->post('brand_id'),
                        'meta_title'       => $this->input->post('meta_title'),
                        'meta_keywords'        => $this->input->post('meta_keywords'),
                        'meta_description'        => $this->input->post('meta_description'),
                    );
                   
                if ($result = $this->master_model->add_duplicate_product($data,$duplicate_id)) {
                    logs($user->id,$result,'DULPICATE_ADD','Add Duplicate Product');
                    for ($i=0; $i < $cat_id; $i++) { 
                        $data_cat_id = array(
                            'pro_id'=>$result,
                            'cat_id'=>$this->input->post('cat_id')[$i],
                        );
                       $mapid= $this->master_model->add_cat_pro_map($data_cat_id);
                        $msg = 'Cat Pro Maps'.$result.'-'.$this->input->post('cat_id')[$i];
                        logs($user->id,$mapid,'ADD',$msg);
                    }
                 
                         // Apply offer code
                         if($this->input->post('NewOffer') && is_numeric($this->input->post('NewOffer'))) {
                            $getOffer = $this->master_model->getRow('coupons_and_offers',['id'=>$this->input->post('NewOffer')]);
                            $offerdata = array(
                             'offer_assosiated_id'     => $this->input->post('NewOffer'),
                             'product_id'     => $result,
                             'shop_id'     => '6',
                             'offer_associated'     => @$getOffer->value,
                             'offer_upto'     => @$getOffer->value,
                             'discount_type'     => @$getOffer->discount_type,
                         );
                     
                         $this->offers_model->add_data('shops_coupons_offers',$offerdata);
                         logs($user->id,$result,'ADD','Apply Offer Product');
                     }
                           $rowCountArray = $this->input->post('rowCount');
                           if($rowCountArray){
                             foreach ($rowCountArray as $index => $rowCount) {
                                 $propId = $this->input->post('propId')[$index];
                                 $propTypeId = $this->input->post('prop_typeID')[$index];

                                 $data = array(
                                     'props_id' => $propId,
                                     'product_id' => $result,
                                     'value_id' => $propTypeId
                                 );

                                 $this->db->insert('product_props', $data);
                             }
                         }
                         $exists = $this->shops_vendor_model->getItemIDDataNew($result);
                         if($exists)
                         {
                             $exists->mrp =  $this->input->post('NewMrp'); 
                             $exists->selling_rate = $this->input->post('NewSellingRate');
                             $exists->qty =  $this->input->post('NewQty');
                             $exists->landing_cost =  $this->input->post('NewLandingCost'); 
                             $exists->purchase_rate = $this->input->post('NewPurchaseRate');
                             $exists->tax_value = $this->input->post('NewTaxRate') ? $this->input->post('NewTaxRate') : $exists->tax_value;
                             $exists->tax_amount = $this->input->post('NewTaxAmount');
                             if($this->input->post('NewOffer') && is_numeric($this->input->post('NewOffer'))) {
                             $exists->NewOfferValue = $this->input->post('NewOfferValue');
                             $exists->offer_upto =  @$getOffer->value ? @$getOffer->value : '';
                             $exists->discount_type =  @$getOffer->discount_type ? @$getOffer->discount_type : '2';
                             }else{
                                 $exists->NewOfferValue = '0';
                                 $exists->offer_upto = '0';
                                 $exists->discount_type =  '2';
                             }
                             $return['res'] = true;
                             $return['msg'] = 'Saved.';
                             $return['rowData'] = $exists;
                         }else{
                             $return['res'] = false;
                             $return['msg'] = 'Saved.';
                             $return['rowData'] = $exists;
                         }
                    }
               }
               echo json_encode($return);
             break;   
             case 'edit-purchase':
                $id = $p1;
                $return['status'] = 'error';
                $return['message'] = 'Not Saved!';
                if ($this->input->server('REQUEST_METHOD')=='POST') { 
                // Basic Details 
                $supplier_id = $this->input->post('supplier') ? $this->input->post('supplier'):$this->input->post('supplier2') ;
                $purchase_order_date = $this->input->post('purchase_order_date');
                $purchase_order_no = $this->input->post('purchase_order_no');
                $shipping_date = $this->input->post('shipping_date');
                $shipping_note = $this->input->post('shipping_note');
                $notes = $this->input->post('notes');
                
                
                // Product Total
                $flatDiscountRate = $this->input->post('flatDiscount');
                $GrossTotal = $this->input->post('GrossTotal');
                $FinalDiscountAmount = $this->input->post('FinalDiscount');
                $FinalTaxWithAmount = $this->input->post('FinalTaxWithAmount');
                $FinalTax = $this->input->post('FinalTax');
                $FinalTotal = $this->input->post('FinalTotal');
                $FinalQty = $this->input->post('FinalQty');
                
                // Get the dynamic inputs using $_POST directly 
                $itemCodes = $this->input->post('itemCode');
                $productVariants = $this->input->post('productVarientId');
                $quantities = $this->input->post('quantity');
                $unitCosts = $this->input->post('unitCost');
                $taxAmounts = $this->input->post('taxAmount');
                $taxRates = $this->input->post('taxRate');
                $landingCosts = $this->input->post('landingCost');
                $margins = $this->input->post('margin');
                $netAmounts = $this->input->post('netAmount');
                $discount_value = $this->input->post('discount_value');
                $mrp = $this->input->post('mrpp');
                $SellRate = $this->input->post('SellRate');
                $purchaseId = $this->input->post('purchaseId');
                $inventtory_id = $this->input->post('inventtory_id');
                $diff_qty = $this->input->post('diff_qty');
                $exist_item = $this->input->post('exist_item');

                // Check in case of any null data
                if($FinalTotal !=''){   
                $purchase = array(
                'supplier_id' => $supplier_id,
                'shop_id' => $user->id,
                'purchase_order_date' => $purchase_order_date,
                'purchase_order_no' => $purchase_order_no,
                'shipping_date' => $shipping_date,
                'shipping_note' => $shipping_note,
                'flat_discount' => $flatDiscountRate,
                'flat_discount_value' => $FinalDiscountAmount,
                'total_amount' => $FinalTotal,
                'total_tax' => $FinalTax,
                'gross_total' => $GrossTotal,
                'total_with_tax' => $FinalTaxWithAmount,
                'total_qty' => $FinalQty,
                'remark'=>$notes,
                );
                
                $this->db->update('purchase',$purchase,['id'=>$id]);
                $affected_rows = $this->db->affected_rows();
                 if ($affected_rows > 0) {
                    $ledger_cond['txn_type']        = 4;
                    $ledger_cond['inventory_id']    = $id;
                    $ledger['dr']           = $FinalTotal;
                    $ledger['reference_no'] = $purchase_order_no;
                    $ledger['PaymentDate']  = $purchase_order_date;
                    $this->load->model('cash_register_model');
                    $this->cash_register_model->updateRow($ledger_cond,$ledger);
                   foreach ($itemCodes as $index => $itemCode) {
                    if(@$exist_item[$index] == @$productVariants[$index]){
                         // get products dynamic selectable props while placing order
                     
                    $dataitem = array(
                        'item_id' => $productVariants[$index],
                        'qty' => $quantities[$index],
                        'unit_cost' => $unitCosts[$index],
                        'mrp' => $mrp[$index],
                        'tax' => $taxRates[$index],
                        'tax_value' => $taxAmounts[$index],
                        'landing_cost' => $landingCosts[$index],
                        'margin' => $margins[$index],
                        'total' => $netAmounts[$index],
                        'discount'=>$flatDiscountRate,
                        'discount_value'=>$discount_value[$index],
                    );
                    $this->db->update('purchase_items',$dataitem,['id'=>$purchaseId[$index]]);

                    $diffQty = $quantities[$index]-$diff_qty[$index];
                    $data_shop_inventry = array(
                        'vendor_id' => $supplier_id,
                        'product_id'=>$productVariants[$index],
                        'qty'=>$diffQty,
                        'purchase_rate'=>$unitCosts[$index],
                        'mrp'=>$mrp[$index],
                        'selling_rate'=>$mrp[$index],
                        'shop_id'=>$user->id,
                        'tax_value'=>$taxRates[$index],
                        'total_value'=>$netAmounts[$index],
                        'total_tax'=>$taxAmounts[$index],
                        );
                        $data_shop_inventry_log = array(
                            'vendor_id' => $supplier_id,
                            'product_id'=>$productVariants[$index],
                            'qty'=>$quantities[$index],
                            'purchase_rate'=>$unitCosts[$index],
                            'mrp'=>$mrp[$index],
                            'selling_rate'=>$mrp[$index],
                            'shop_id'=>$user->id,
                            'tax_value'=>$taxRates[$index],
                            'total_value'=>$netAmounts[$index],
                            'total_tax'=>$taxAmounts[$index],
                            );

                     $this->db->update('shops_inventory',$data_shop_inventry,['id'=>$inventtory_id[$index]]);
                      $affected_rows = $this->db->affected_rows();
                     if ($affected_rows > 0) { {
                        logs($user->id,$inventtory_id[$index],'UPDATE','UPDATE Purchase Inventory');
                        $data_shop_inventry_log['action']="LATEST_UPDATE";
                        $data_shop_inventry_log['shops_inventory_id']=$inventtory_id[$index];
                        $this->db->insert('shop_inventory_logs', $data_shop_inventry_log);

                    }
                   
                  }
                 
                 }
                 else{
                      // get products dynamic selectable props while placing order
                    
                    $dataitemnew = array(
                        'purchase_id' => $p1,
                        'item_id' => $productVariants[$index],
                        'qty' => $quantities[$index],
                        'unit_cost' => $unitCosts[$index],
                        'mrp' => $mrp[$index],
                        'tax' => $taxRates[$index],
                        'tax_value' => $taxAmounts[$index],
                        'landing_cost' => $landingCosts[$index],
                        'margin' => $margins[$index],
                        'total' => $netAmounts[$index],
                        'discount'=>$flatDiscountRate,
                        'discount_value'=>$discount_value[$index],
                    );
                    $this->db->insert('purchase_items',$dataitemnew);
                    $insert_id2 = $this->db->insert_id();
                    $data_shop_inventrynew = array(
                        'vendor_id' => $supplier_id,
                        'product_id'=>$productVariants[$index],
                        'qty'=>$quantities[$index],
                        'purchase_rate'=>$unitCosts[$index],
                        'mrp'=>$mrp[$index],
                        'selling_rate'=>$mrp[$index],
                        'shop_id'=>$user->id,
                        'tax_value'=>$taxRates[$index],
                        'total_value'=>$netAmounts[$index],
                        'total_tax'=>$taxAmounts[$index],
                        'purchase_item_id'=>$insert_id2,
                        );
                        $data_shop_inventry_lognew = array(
                            'vendor_id' => $supplier_id,
                            'product_id'=>$productVariants[$index],
                            'qty'=>$quantities[$index],
                            'purchase_rate'=>$unitCosts[$index],
                            'mrp'=>$mrp[$index],
                            'selling_rate'=>$mrp[$index],
                            'shop_id'=>$user->id,
                            'tax_value'=>$taxRates[$index],
                            'total_value'=>$netAmounts[$index],
                            'total_tax'=>$taxAmounts[$index],
                            );

                    if ($insert_stock = $this->master_model->add_data('shops_inventory',      $data_shop_inventrynew)) {
                        logs($user->id,$insert_stock,'ADD','Add Purchase Inventory');
                        $data_shop_inventry_lognew['action']="ADD_INVENTORY";
                        $data_shop_inventry_lognew['shops_inventory_id']=$insert_stock;
                        $this->master_model->add_data('shop_inventory_logs', $data_shop_inventry_lognew);

                    }
                 }
                 }
                 $return['status'] = 'success';
                 $return['message'] = 'Purchase successfully saved!.';
               }
             }else
             {
                 $return['status'] = 'error';
                 $return['message'] = 'Something went we wrong !.';
             }
            }
                echo json_encode($return);
            break;
            case 'delete_purchase':
            $id = $p1;
            if($this->shops_vendor_model->delete_purchase($id))
            {
            //delete purchase from cash_register
            $this->cash_register_model->isDeleteRow($id);
            $Purchase_Item = $this->master_model->getData('purchase_items',['purchase_id'=>$p1]);
            foreach($Purchase_Item as $item)
            {
                $in = $this->master_model->getRow('shops_inventory',['purchase_item_id'=>$item->id]);
               $this->shops_vendor_model->delete_inventory($in->id);
               $this->shops_vendor_model->delete_product($item->item_id);
               $data = array(
                   'vendor_id' => $in->vendor_id,
                   'product_id'=>$in->product_id,
                   'qty'=>$in->qty,
                   'purchase_rate'=>$in->purchase_rate,
                   'mrp'=>$in->mrp,
                   'selling_rate'=>$in->selling_rate,
                   'shop_id'=>$user->id,
                   'tax_value'=>$in->tax_value,
                   'total_value'=>$in->total_value,
                   'total_tax'=>$in->total_tax,
                   'shops_inventory_id' =>$in->id,
                   'action' =>"DELETE",
               );
               
               $this->shops_vendor_model->delete_inventory_log($data);
               
            }
             logs($user->id,$id,'DELETE','Delete Purchase');  
             $data['search'] = '';
             if (@$_POST['search']) {
                 $data['search'] = $_POST['search'];
             }
             $this->load->library('pagination');
             $config = array();
             $config["base_url"]         = base_url() . "purchase/tb/";
             $config["total_rows"]       = $this->shops_vendor_model->Purchase();
             $data['total_rows']         = $config["total_rows"];
             $config["per_page"]         = 10;
             $config["uri_segment"]      = 3;
             $config['attributes']       = array('class' => 'pag-link');
             $config['full_tag_open']    = "<div class='pag'>";
             $config['full_tag_close']   = "</div>";
             $config['first_link']       = '&lt;&lt;';
             $config['last_link']        = '&gt;&gt;';
             $this->pagination->initialize($config);
             $data["links"]              = $this->pagination->create_links();
             $data['page']               = $page = ($p2 != null) ? $p2 : 0;
             $data['per_page']           = $config["per_page"];
             $data['purchase']           = $this->shops_vendor_model->Purchase($config["per_page"], $page);
             $data['shop_details'] = $this->shops_model->get_shop_data($shop_id);
             $data['update_url']         = base_url() . 'purchase/update-purchase/';
             $page                       = 'shop/purchase/tb';
             $this->load->view($page, $data);
            }
            break; 
            case 'yes-inventory-product':
                $productId = $_POST['id'];
                $productDetails = $this->master_model->getRow('products_subcategory', ['id' => $productId]); 
                $data['pid'] = $productId;
                $data['product'] = $productDetails;
                $data['action_url']     = base_url().'purchase/yes-product-edit/';
                $data['value']          = $this->shops_vendor_model->product($productId);
                $data['cat_pro_map']    = $this->master_model->get_cat_pro_map($productId);
                $data['remote']         = base_url().'shop-master-data/remote/products/';
                $data['offers']           = $this->master_model->getOffers();
                $data['tax_slabs'] = $this->master_model->get_data('tax_slabs','active','1');
                $data['shops_inventory'] = $this->master_model->get_data2_row('shops_inventory',['product_id'=>$data['value']->id]);
                 $data['applyoffer'] = $this->master_model->getRow('shops_coupons_offers',['product_id'=>$productId,'is_deleted'=>'NOT_DELETED','shop_id'=>'6']);
                 $data['parent_cat'] = $this->master_model->get_parent_category();
                 $data['parent_id'] = $this->master_model->get_parent_id();
                 $data['categories'] = $this->master_model->get_data('products_category','is_parent !=','0');
                 $data['unit_type']          = $this->master_model->get_data('unit_master','active','1');
                 $data['brands']          = $this->master_model->get_data('brand_master','active','1');
                 $data['ingredients']          = $this->master_model->get_data('product_ingredients','active','1');
                 $data['dpco']          = $this->master_model->get_data('dpco','active','1');
                 $data['all_categories'] = $this->db->get_where('products_category',['active' =>'1', 'is_deleted' => 'NOT_DELETED'])->result();
                 $data['property_val']    = $this->master_model->get_property_val($productId);
                $this->load->view('shop/purchase/new-product', $data); 
               break;  
               case 'no-inventory-product':
                $productId = $_POST['id'];
                $productDetails = $this->master_model->getRow('products_subcategory', ['id' => $productId]); 
                $data['pid'] = $productId;
                $data['product'] = $productDetails;
                $data['unit_type']          = $this->master_model->get_data('unit_master','active','1');
                $data['brands']          = $this->master_model->get_data('brand_master','active','1');
                $data['ingredients']          = $this->master_model->get_data('product_ingredients','active','1');
                $data['dpco']          = $this->master_model->get_data('dpco','active','1');
                $data['action_url']     = base_url().'purchase/no-product-edit/'.$productId;
                $data['value']          = $this->shops_vendor_model->product($productId);
                $data['cat_pro_map']    = $this->master_model->get_cat_pro_map($productId);
                $data['remote']         = base_url().'shop-master-data/remote/products/'.$productId;
                $data['offers']           = $this->master_model->getOffers();
                $data['tax_slabs'] = $this->master_model->get_data('tax_slabs','active','1');
                $data['shops_inventory'] = $this->master_model->get_data2_row('shops_inventory',['product_id'=>$data['value']->id]);
                 $data['applyoffer'] = $this->master_model->getRow('shops_coupons_offers',['product_id'=>$productId,'is_deleted'=>'NOT_DELETED','shop_id'=>'6']);
                 $data['parent_cat'] = $this->master_model->get_parent_category();
                 $data['parent_id'] = $this->master_model->get_parent_id();
                 $data['categories'] = $this->master_model->get_data('products_category','is_parent !=','0');
                 $data['all_categories'] = $this->db->get_where('products_category',['active' =>'1', 'is_deleted' => 'NOT_DELETED'])->result();
                 $data['property_val']    = $this->master_model->get_property_val($productId);
                $this->load->view('shop/purchase/new-product', $data); 
               break; 
               case 'no-product-edit':
                $id = $p1;
                $return['res'] = 'error';
                $return['msg'] = 'Not Saved!';
                if ($this->input->server('REQUEST_METHOD')=='POST') { 
                        $UnitCost = $this->input->post('UnitCost');
                        $tax_slab = explode(",",$this->input->post('tax_id'));
                        $cat_id = count($this->input->post('cat_id'));
                        $unit_type = explode(",",$this->input->post('unit_type_id'));
                        $this->db->delete('cat_pro_maps', array('pro_id' => $id));
                        logs($user->id,$id,'DELETE','Cat Pro Maps');
                        $namepro = $this->input->post('name');
                        $data = array(
                            'tax_id'     => $tax_slab[0],
                            'tax_value'     => $tax_slab[1],
                            'name'              => $this->input->post('name'),
                            'search_keywords'   => $this->input->post('search_keywords'),
                            'product_code'      => $this->input->post('product_code'),
                            'unit_value'        => $this->input->post('unit_value'),
                            'unit_type_id'         => $unit_type[0],
                            'unit_type'         => $unit_type[1],
                            'description'       => $this->input->post('description'),
                            'sku'        => $this->input->post('sku'),
                            'brand_id'        => $this->input->post('brand_id'),
                            'ingredient_id'        => $this->input->post('ingredient_id'),
                            'is_return'        => ($this->input->post('is_return'))?'1':'0',
                            'dpco'        => $this->input->post('dpco'),
                            );
                        if($this->master_model->edit_product($data,$id)){
                            logs($user->id,$id,'EDIT','Edit Product');
                            for ($i=0; $i < $cat_id; $i++) { 
                                $data_cat_id = array(
                                    'pro_id'=>$id,
                                    'cat_id'=>$this->input->post('cat_id')[$i],
                                   
                                );
                               $mapid= $this->master_model->add_cat_pro_map($data_cat_id);
                                $msg = 'Cat Pro Maps'.$id.'-'.$this->input->post('cat_id')[$i];
                                logs($user->id,$mapid,'ADD',$msg);
                       }
                        // Apply offer code
                        if($this->input->post('NewOffer') && is_numeric($this->input->post('NewOffer'))) {
                            $getOffer = $this->master_model->getRow('coupons_and_offers',['id'=>$this->input->post('NewOffer')]);
                            $offerdata = array(
                            'offer_assosiated_id'     => $this->input->post('NewOffer'),
                            'product_id'     => $id,
                            'shop_id'     => '6',
                            'offer_associated'     => @$getOffer->value,
                            'offer_upto'     => @$getOffer->value,
                            'discount_type'     => @$getOffer->discount_type,
                        );

                        $this->db->update('shops_coupons_offers',$offerdata,['product_id'=>$id]);
                        logs($user->id,$id,'UPDATE','Update Offer Product');
                        }

                       $rowCountArray = $this->input->post('rowCount');
                       if($rowCountArray){
                       foreach ($rowCountArray as $index => $rowCount) {
                           $propId = $this->input->post('propId')[$index];
                           $propTypeId = $this->input->post('prop_typeID')[$index];
                           $dataproperty = array(
                               'props_id' => $propId,
                               'product_id' => $id,
                               'value_id' => $propTypeId
                           );
                           $count = $this->master_model->Counter('product_props', array('product_id'=>$id,'props_id'=>$propId,'value_id'=> $propTypeId));
                           if($count >=1){
                          }else{
                           $this->db->insert('product_props', $dataproperty);
                          }
                       }
                    }
                    $exists = $this->shops_vendor_model->getItemIDDataNew($id);
                    if($exists)
                    {

                        $exists->mrp =  $this->input->post('NewMrp'); 
                        $exists->selling_rate = $this->input->post('NewSellingRate');
                        $exists->qty =  $this->input->post('NewQty');
                        $exists->landing_cost =  $this->input->post('NewLandingCost'); 
                        $exists->purchase_rate = $this->input->post('NewPurchaseRate');
                        $exists->tax_value = $this->input->post('NewTaxRate');
                        $exists->tax_amount = $this->input->post('NewTaxAmount');
                        if($this->input->post('NewOffer') && is_numeric($this->input->post('NewOffer'))) {
                        $exists->NewOfferValue = $this->input->post('NewOfferValue');
                        $exists->offer_upto =  @$getOffer->value ? @$getOffer->value : '';
                        $exists->discount_type =  @$getOffer->discount_type ? @$getOffer->discount_type : '2';
                        }else{
                            $exists->NewOfferValue = '0';
                            $exists->offer_upto = '0';
                            $exists->discount_type =  '2';
                        }
                        $return['res'] = true;
                        $return['msg'] = 'Saved.';
                        $return['rowData'] = $exists;
                    }else{
                        $return['res'] = false;
                        $return['msg'] = 'Saved.';
                        $return['rowData'] = $exists;
                    }
                    }
                }
                echo json_encode($return);
                break; 

                case 'yes-product-edit':
                    $id = $p1;
                    $return['res'] = 'error';
                    $return['msg'] = 'Not Saved!';
                    if ($this->input->server('REQUEST_METHOD')=='POST') { 
                            $UnitCost = $this->input->post('UnitCost');
                            $tax_slab = explode(",",$this->input->post('tax_id'));
                            $unit_type = explode(",",$this->input->post('unit_type_id'));
                            $cat_id = count($this->input->post('cat_id'));
                            $namepro = $this->input->post('name');
                            $data = array(
                                'tax_id'     => $tax_slab[0],
                                'tax_value'     => $tax_slab[1],
                                'name'              => $this->input->post('name'),
                                'search_keywords'   => $this->input->post('search_keywords'),
                                'product_code'      => $this->input->post('product_code'),
                                'unit_value'        => $this->input->post('unit_value'),
                                'unit_type_id'         => $unit_type[0],
                                'unit_type'         => $unit_type[1],
                                'description'       => $this->input->post('description'),
                                'sku'        => $this->input->post('sku'),
                                'brand_id'        => $this->input->post('brand_id'),
                                'ingredient_id'        => $this->input->post('ingredient_id'),
                                'is_return'        => ($this->input->post('is_return'))?'1':'0',
                                'dpco'        => $this->input->post('dpco'),
                                );
                            if($result = $this->master_model->add_product($data)){
                                logs($user->id,$result,'ADD','ADD Product');
                                for ($i=0; $i < $cat_id; $i++) { 
                                    $data_cat_id = array(
                                        'pro_id'=>$result,
                                        'cat_id'=>$this->input->post('cat_id')[$i],
                                       
                                    );
                                   $mapid= $this->master_model->add_cat_pro_map($data_cat_id);
                                    $msg = 'Cat Pro Maps'.$result.'-'.$this->input->post('cat_id')[$i];
                                    logs($user->id,$mapid,'ADD',$msg);
                           }
                            // Apply offer code
                            if($this->input->post('NewOffer') && is_numeric($this->input->post('NewOffer'))) {
                                $getOffer = $this->master_model->getRow('coupons_and_offers',['id'=>$this->input->post('NewOffer')]);
                                $offerdata = array(
                                'offer_assosiated_id'     => $this->input->post('NewOffer'),
                                'product_id'     => $result,
                                'shop_id'     => '6',
                                'offer_associated'     => @$getOffer->value,
                                'offer_upto'     => @$getOffer->value,
                                'discount_type'     => @$getOffer->discount_type,
                            );
    
                            $this->offers_model->add_data('shops_coupons_offers',$offerdata);
                            logs($user->id,$result,'ADD','ADD Offer Product');
                            }
    
                           $rowCountArray = $this->input->post('rowCount');
                           if($rowCountArray){
                           foreach ($rowCountArray as $index => $rowCount) {
                               $propId = $this->input->post('propId')[$index];
                               $propTypeId = $this->input->post('prop_typeID')[$index];
                               $dataproperty = array(
                                   'props_id' => $propId,
                                   'product_id' => $result,
                                   'value_id' => $propTypeId
                               );
                               $count = $this->master_model->Counter('product_props', array('product_id'=>$result,'props_id'=>$propId,'value_id'=> $propTypeId));
                               if($count >=1){
                              }else{
                               $this->db->insert('product_props', $dataproperty);
                              }
                           }
                        }
                        $exists = $this->shops_vendor_model->getItemIDDataNew($result);
                        if($exists)
                        {
    
                            $exists->mrp =  $this->input->post('NewMrp'); 
                            $exists->selling_rate = $this->input->post('NewSellingRate');
                            $exists->qty =  $this->input->post('NewQty');
                            $exists->landing_cost =  $this->input->post('NewLandingCost'); 
                            $exists->purchase_rate = $this->input->post('NewPurchaseRate');
                            $exists->tax_value = $this->input->post('NewTaxRate');
                            $exists->tax_amount = $this->input->post('NewTaxAmount');
                            if($this->input->post('NewOffer') && is_numeric($this->input->post('NewOffer'))) {
                            $exists->NewOfferValue = $this->input->post('NewOfferValue');
                            $exists->offer_upto =  @$getOffer->value ? @$getOffer->value : '';
                            $exists->discount_type =  @$getOffer->discount_type ? @$getOffer->discount_type : '2';
                            }else{
                                $exists->NewOfferValue = '0';
                                $exists->offer_upto = '0';
                                $exists->discount_type =  '2';
                            }
                            $return['res'] = true;
                            $return['msg'] = 'Saved.';
                            $return['rowData'] = $exists;
                        }else{
                            $return['res'] = false;
                            $return['msg'] = 'Not Saved.';
                            $return['rowData'] = $exists;
                        }
                        }
                    }
                    echo json_encode($return);
                    break;
                 case 'create-new-product':
                   $data['code']=@$_POST['code'] ? $_POST['code'] : '';
                   $data['name']=@$p1 ? $p1 : '';
                    $data['remote']             = base_url().'shop-master-data/remote/products/';
                    $data['tax_slabs'] = $this->master_model->get_data('tax_slabs','active','1');
                    $data['properties']         = $this->master_model->view_data('product_props_master');
                    $data['unit_type']          = $this->master_model->get_data('unit_master','active','1');
                    $data['brands']          = $this->master_model->get_data('brand_master','active','1');
                    $data['flavours']          = $this->master_model->get_data('flavour_master','active','1');
                    $data['offers']           = $this->master_model->getOffers();
                    $data['parent_cat'] = $this->master_model->get_parent_category();
                    $data['parent_id'] = $this->master_model->get_parent_id();
                    $data['categories'] = $this->master_model->get_data('products_category','is_parent !=','0');
                    $data['all_categories'] = $this->db->get_where('products_category',['active' =>'1', 'is_deleted' => 'NOT_DELETED'])->result();
                    $this->load->view('shop/purchase/create-new-product', $data); 
                break;
                case 'create-new-product-name':
                    $data['name']=@$p1 ? $p1 : '';
                     $data['remote']             = base_url().'shop-master-data/remote/products/';
                     $data['tax_slabs'] = $this->master_model->get_data('tax_slabs','active','1');
                     $data['properties']         = $this->master_model->view_data('product_props_master');
                     $data['unit_type']          = $this->master_model->get_data('unit_master','active','1');
                     $data['brands']          = $this->master_model->get_data('brand_master','active','1');
                     $data['flavours']          = $this->master_model->get_data('flavour_master','active','1');
                     $data['offers']           = $this->master_model->getOffers();
                     $data['parent_cat'] = $this->master_model->get_parent_category();
                     $data['parent_id'] = $this->master_model->get_parent_id();
                     $data['categories'] = $this->master_model->get_data('products_category','is_parent !=','0');
                     $data['all_categories'] = $this->db->get_where('products_category',['active' =>'1', 'is_deleted' => 'NOT_DELETED'])->result();
                     $this->load->view('shop/purchase/create-new-product-name', $data); 
                 break;
               case 'delete_item':
                $return['res'] = false;
                $return['msg'] = 'Not Deleted!';
                if ($this->input->server('REQUEST_METHOD')=='POST') { 
                    $itemCode = $this->input->post('itemCode');
                    $id = $this->input->post('id');
                    $purchase_id = $this->input->post('purchase_id');
                    $Pro = $this->master_model->getRow('purchase_items',['id'=>$id]);
                    $this->db->where('id', $id);
                    $this->db->delete('purchase_items');
                    if($this->db->affected_rows() > 0)
                    {
                        $purchase_ids = $this->master_model->getData('purchase_items',['purchase_id'=>$purchase_id]);
                        if(count($purchase_ids) <= 1)
                        {
                            $this->shops_vendor_model->delete_purchase($purchase_id);
                        }
                        $Inventory = $this->master_model->getData('shops_inventory',['product_id'=>$Pro->item_id]);
                        foreach($Inventory as $in)
                        {
                           $this->shops_vendor_model->delete_inventory($in->id);
                           $this->shops_vendor_model->delete_product($Pro->item_id); 
                           $data = array(
                               'vendor_id' => $in->vendor_id,
                               'product_id'=>$in->product_id,
                               'qty'=>$in->qty,
                               'purchase_rate'=>$in->purchase_rate,
                               'mrp'=>$in->mrp,
                               'selling_rate'=>$in->selling_rate,
                               'shop_id'=>$user->id,
                               'tax_value'=>$in->tax_value,
                               'total_value'=>$in->total_value,
                               'total_tax'=>$in->total_tax,
                               'shops_inventory_id' =>$in->id,
                               'action' =>"DELETE",
                           );
                           
                           $this->shops_vendor_model->delete_inventory_log($data);
                           
                        }
                        logs($user->id,$id,'DELETE','Delete Purchase Item'); 
                        $return['res'] = true;
                        $return['msg'] = 'Deleted!';
                    }

                }
                echo json_encode($return);    
               break;
               case 'upload-purchase':
                $data['menu_id']        = $p1;
                $data['title']          = 'Add New Purchase';
                $data['action_url']         = base_url().'purchase/import_excel';
                $page                       = 'shop/purchase/upload-purchase';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $this->header_and_footer($page, $data); 
                break;
                case 'import_excel':
                    $return['res'] = 'error';
                    $return['msg'] = 'Not Saved!';
                     if ($this->input->server('REQUEST_METHOD')=='POST') { 
                    $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    if(isset($_FILES['import_file']['name']) && in_array($_FILES['import_file']['type'],    $file_mimes)) {
                        $arr_file = explode('.', $_FILES['import_file']['name']);
                        $extension = end($arr_file);
                        if('csv' == $extension) {
                            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                        } else {
                            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        }
                        $spreadsheet = $reader->load($_FILES['import_file']['tmp_name']);
                        $sheetData = $spreadsheet->getActiveSheet()->toArray();
                        $flag = TRUE;
                        $supplierCodes = array();
                        $newCount = $count = 0;
                        $supplierCodeCount = 0;
                        $productCodeCount = 0;
                        $createdItems = array(); 
                        $validProductsFound = false;
                        foreach ($sheetData as $data) {
                            if ($flag) {
                                $flag = false; 
                                continue; 
                            }
                            // if (!empty($data[8]) || isset($data[8])) {
                            //     continue; // Skip the iteration
                            // }
                            if (!empty($data)) {
                                $product_code = isset($data[1]) ? trim($data[1]) : '';
                                $supplier_code = isset($data[2]) ? trim($data[2]) : '';
                                $purchase_rate_without_tax = isset($data[3]) ? trim($data[3]) : '';
                                $tax_rate = isset($data[4]) ? str_replace('%', '', trim($data[4])) : '';
                                $landing_price = isset($data[5]) ? trim($data[5]) : '';
                                $qty = isset($data[6]) ? trim($data[6]) : '';
                                $total_amount = isset($data[7]) ? trim($data[7]) : '';
                                $products = $this->master_model->getRow('products_subcategory',['product_code'=>$product_code,'is_deleted'=>'NOT_DELETED']);
                                if(empty($products->id)){
                                    continue; 
                                }
                                if (!empty($product_code) && !empty($supplier_code) && !empty($purchase_rate_without_tax) && !empty($tax_rate) && isset($landing_price) && !empty($qty) && !empty($total_amount)) {
                                    //echo $landing_price . "<br>"; // Echo only if landing_price exists
                        
                                    if (!isset($supplierDetails[$supplier_code])) {
                                        $supplierDetails[$supplier_code] = array(
                                            'product_codes' => array(),
                                            'purchase_rate_without_tax' => array(),
                                            'tax_rate' => array(),
                                            'landing_price' => array(),
                                            'qty' => array(),
                                            'total_amount' => array()
                                        );
                                    }
                                    
                                    // Add data to the supplierDetails array
                                    
                                    $supplierDetails[$supplier_code]['product_codes'][] = $product_code;
                                    $supplierDetails[$supplier_code]['purchase_rate_without_tax'][] = $purchase_rate_without_tax;
                                    $supplierDetails[$supplier_code]['tax_rate'][] = $tax_rate;
                                    $supplierDetails[$supplier_code]['landing_price'][] = $landing_price;
                                    $supplierDetails[$supplier_code]['qty'][] = $qty;
                                    $supplierDetails[$supplier_code]['total_amount'][] = $total_amount;
                                }
                            }
                            
                            $newCount++;
                        }
                        // Initialize total amount sum
                        $purchaseOrderCounter = 1;

                        foreach ($supplierDetails as $supplier_code => $details) {
                            if (!empty($supplier_code)) {
                                
                                $totalAmountSumWith = array_sum($details['purchase_rate_without_tax']);
                                $totalAmountSum = array_sum($details['total_amount']);
                                $totalAmountqty = array_sum($details['qty']);
                                $totalAmountTax = 0;
                                foreach ($details['qty'] as $key => $qty) {
                                    $taxAmount = ($details['landing_price'][$key] - $details['purchase_rate_without_tax'][$key]) * $qty;
                                    $totalAmountTax += $taxAmount;
                                }
                                
                                $purchaseOrderNo = str_pad($purchaseOrderCounter, 5, '0', STR_PAD_LEFT);
                                $purchaseOrderCounter++;
                                $supplier = $this->master_model->getRow('customers',['vendor_code'=>$supplier_code]);
                                $purchaseDate =array(
                                   'supplier_id'=>$supplier->id,
                                   'purchase_order_date'=>'2024-03-01',
                                   'purchase_order_no'=>$purchaseOrderNo,
                                   'shop_id'=>$shop_id,
                                   'shipping_date'=>'2024-03-05',
                                   'total_amount'=>$totalAmountSum,
                                   'total_tax'=>$totalAmountTax,
                                   'gross_total'=>$totalAmountSum,
                                   'total_with_tax'=>$totalAmountSumWith,
                                   'total_qty'=>$totalAmountqty,
                                   'status'=>'3',
                                );
                                $this->db->insert('purchase',$purchaseDate);
                                $purchaseId = $this->db->insert_id();
                                if ($purchaseId) {
                                    
                                    foreach ($details['product_codes'] as $key => $product_code) {
                                        $products = $this->master_model->getRow('products_subcategory',['product_code'=>$product_code,'is_deleted'=>'NOT_DELETED']);
                                       
                                        if(!empty($products->id)){
                                            $productsInventory = $this->master_model->getRow('shops_inventory',['product_id'=>$products->id,'is_deleted'=>'NOT_DELETED']);
                                            $margn=@$productsInventory->mrp-$details['purchase_rate_without_tax'][$key];
                                            $margnper = ($margn/$details['purchase_rate_without_tax'][$key])*100;
                                        $purchaseItemData = array(
                                            'purchase_id' => $purchaseId,
                                            'item_id' => $products->id,
                                            'unit_cost' => $details['purchase_rate_without_tax'][$key],
                                            'tax' => $details['tax_rate'][$key],
                                            'landing_cost' => $details['landing_price'][$key],
                                            'qty' => $details['qty'][$key],
                                            'total' => $details['total_amount'][$key],
                                            'mrp' => @$productsInventory->mrp,
                                            'tax_value'=>($details['landing_price'][$key]-$details['purchase_rate_without_tax'][$key]) * $details['qty'][$key],
                                            'margin'=>$margnper,
                                            'discount'=>'0',
                                            'discount_value'=>'0.00',
                                        );
                                        
                                $this->db->insert('purchase_items',$purchaseItemData);
                                $purchase_item_id = $this->db->insert_id();
                                $this->db->update('shops_inventory',[
                                    'purchase_rate'=>$details['purchase_rate_without_tax'][$key],
                                    'purchase_item_id'=>$purchase_item_id,
                                    'qty'=>$details['qty'][$key],
                                    'tax_value'=>$details['tax_rate'][$key],
                                    'total_value'=>$details['total_amount'][$key],
                                    'total_tax'=>($details['landing_price'][$key]-$details['purchase_rate_without_tax'][$key]) * $details['qty'][$key],
                                    'vendor_id'=>$supplier->id,
                                    ],
                                    ['product_id'=>$products->id]);
                                $this->db->update('shop_inventory_logs',[
                                    'purchase_rate'=>$details['purchase_rate_without_tax'][$key],
                                     'qty'=>$details['qty'][$key],
                                    'tax_value'=>$details['tax_rate'][$key],
                                    'total_value'=>$details['total_amount'][$key],
                                    'total_tax'=>($details['landing_price'][$key]-$details['purchase_rate_without_tax'][$key]) * $details['qty'][$key],
                                    'vendor_id'=>$supplier->id,
                                    ],
                                    ['shops_inventory_id'=>$productsInventory->id]);
                                $productCodeCount++; // Increment product code count
                                 }
                               }
                             }
                             $supplierCodeCount++;
                         }
                        }
                       $return['res']='success';
                       $return['msg'] = $supplierCodeCount . ' Purchase  and ' . $productCodeCount . ' Product Items have been created';
                    }
                        echo json_encode($return);
                    }
                break;     
                default:
                # code...
                break;
        }
    }
    public function insertStockData(){
        $product_id = explode(",",$_POST['item']['product_id']);

        $insertArray = array(
            'product_id' => $product_id[0],            
            'tax_value' => $product_id[1],            
            'purchase_rate' => is_null($_POST['item']['purchase_rate'])?0.00:$_POST['item']['purchase_rate'] ,
            'selling_rate' => $_POST['item']['selling_rate'],
            'mrp' => $_POST['item']['mrp'],
            'mfg_date' => $_POST['item']['mfg_date'],
            'expiry_date' => $_POST['item']['expiry_date'],
            'shop_id' => $_POST['shop_id'],
            'vendor_id' => $_POST['item']['vendor_id'],
            'is_igst' => $_POST['item']['is_igst'],
            'total_value' => $_POST['item']['total_value'],
            'invoice_no' => $_POST['item']['invoice_no'],
            'invoice_date' => $_POST['item']['invoice_date'],
            'total_tax' => $_POST['item']['total_tax'],
        );
        // $checkinsertArray = array(
        //     'product_id' => $product_id[0],            
        //     'tax_value' => $product_id[1],            
        //     'purchase_rate' => is_null($_POST['item']['purchase_rate'])?0.00:$_POST['item']['purchase_rate'] ,
        //     'selling_rate' => $_POST['item']['selling_rate'],
        //     'mrp' => $_POST['item']['mrp'],                  
        //     'shop_id' => $_POST['shop_id'],
        // );
        // print_r($insertArray);
        // die();

        // $checkExistingData=$this->shops_inventory_model->getRows(array('conditions'=>$checkinsertArray));
        // $insertArray1['get_shop_inventory_id']=$this->shops_inventory_model->get_row_data1('shops_inventory','purchase_rate',$purchase_rate,'selling_rate',$selling_rate,'mrp',$mrp);
        // if($checkExistingData===FALSE){
            $insertArray['qty'] = $_POST['item']['qty'];
            // $this->shops_inventory_model->insertRow($insertArray);
            $insert_stock = $this->shops_inventory_model->insertRow1($insertArray);
            //log generated
            $insertArray['action']="LATEST_UPDATE";
            $insertArray['shops_inventory_id']=$insert_stock;
            $this->shop_inventory_logs_model->insertRow($insertArray);
        // }else{
        //     $this->shops_inventory_model->updateRow($checkExistingData[0]['id'],array('qty'=>($_POST['item']['qty']+$checkExistingData[0]['qty'])));
        //     $insertArray['qty'] = $_POST['item']['qty'];
        //     $insertArray['action']="UPDATE";
        //     $insertArray['shops_inventory_id']=$checkExistingData[0]['id'];
        //     $this->shop_inventory_logs_model->insertRow($insertArray);
        // }
    }
    public function updateStockData(){
        $checkExistingData = $this->shops_inventory_model->getRows(array('conditions'=>array('id'=>$_POST['item']['id'])));
        $total_val = $_POST['item']['qty'] * $_POST['item']['purchase_rate'];

        // $total_tax = $_POST['item']['purchase_rate'] - ($_POST['item']['purchase_rate'] * (100 / (100 + $_POST['item']['tax_value'] ) ) );
        $total_tax = $total_val - ($total_val * (100 / (100 + $_POST['item']['tax_value'] ) ) );

        if($checkExistingData!==FALSE){
            $updateData = array(
                // 'product_id'=>$_POST['item']['product_id'],
                //                     'qty'=>$_POST['item']['qty'],
                //                     'purchase_rate' => $_POST['item']['purchase_rate'],
                //                     'selling_rate' => $_POST['item']['selling_rate'],
                //                     'shop_id' => $_POST['item']['shop_id'],
                //                     'mfg_date' => $_POST['item']['mfg_date'],
                //                     'expiry_date' => $_POST['item']['expiry_date'],
                //                     'vendor_id' => $_POST['item']['vendor_id'],
                //                     'is_igst' => $_POST['item']['is_igst'],
                //                     'invoice_no' => $_POST['item']['invoice_no'],
                //                     'tax_value' => $_POST['item']['tax_value'],
                //                     'status' => $_POST['item']['status'],
                //                     'total_value' => $total_val,
                //                     'total_tax' => $total_tax,

                                    'qty'=>$_POST['item']['qty'],
                                    'purchase_rate' => $_POST['item']['purchase_rate'],
                                    'selling_rate' => $_POST['item']['selling_rate'],
                                    'mrp' => $_POST['item']['mrp'],
                                    'status' => $_POST['item']['status'],
                                    'total_value' => $total_val,
                                    'total_tax' => $total_tax,
                                );
            $this->shops_inventory_model->updateRow($_POST['item']['id'],$updateData);

            //log generated
            $insertArray = array(
                'product_id' => $checkExistingData[0]['product_id'],
                'qty' => $_POST['item']['qty'],
                'purchase_rate' => $_POST['item']['purchase_rate'],
                'selling_rate' => $_POST['item']['selling_rate'],
                'mrp' => $_POST['item']['mrp'],
                'shop_id' => $checkExistingData[0]['shop_id'],
                'mfg_date' => $_POST['item']['mfg_date'],
                'expiry_date' => $_POST['item']['expiry_date'],
                'vendor_id' => $_POST['item']['vendor_id'],
                'is_igst' => $_POST['item']['is_igst'],
                'invoice_no' => $_POST['item']['invoice_no'],
                'invoice_date' => $_POST['item']['invoice_date'],
                'tax_value' => $_POST['item']['tax_value'],
                'total_value' => $total_val,
                'total_tax' => $total_tax,
            );
            // if($checkExistingData[0]['status'] !== $_POST['item']['status']){
            //     if($_POST['item']['status']==='0'){
            //         $insertArray['action']='DISABLED';
            //         $insertArray['shops_inventory_id']=$checkExistingData[0]['id'];
            //     }else{
            //         $insertArray['action']='ENABLED';
            //         $insertArray['shops_inventory_id']=$checkExistingData[0]['id'];
            //     }
            // }else{
                $insertArray['action']='LATEST_UPDATE';
                $insertArray['shops_inventory_id']=$checkExistingData[0]['id'];
            // }
            $shop_inventory_id = $_POST['item']['id'];

            $data['get_inventory_log']    = $this->shop_inventory_logs_model->getMaxRow($shop_inventory_id);
            
            $inventory_log_id = $data['get_inventory_log']->id;

            $updateaction['action']='UPDATE';
            if(!empty($data['get_inventory_log']))
            {
                $this->shop_inventory_logs_model->updateRow($inventory_log_id,$updateaction);
            }
            $this->shop_inventory_logs_model->insertRow($insertArray);
        }
    }
    public function updateCustomStockData(){
        $data['user']  = $user         = $this->checkShopLogin();
        $checkExistingData = $this->shops_inventory_model->getRows(array('conditions'=>array('id'=>$_POST['item']['id'])));
        if($checkExistingData!==FALSE){
            $shop_id     = $user->id;
            $product_id = explode(",",$_POST['item']['product_id']);
      
            $updateData = array(
                                    'product_id' => $product_id[0],            
                                    'tax_value' => $product_id[1],
                                    'vendor_id' => $_POST['item']['vendor_id'],
                                    'purchase_rate' => $_POST['item']['purchase_rate'],
                                    'selling_rate' => $_POST['item']['selling_rate'],
                                    'qty' => $_POST['item']['qty'],
                                    'mrp' => $_POST['item']['mrp'],
                                    'mfg_date' => $_POST['item']['mfg_date'],
                                    'expiry_date' => $_POST['item']['expiry_date'],
                                    'total_value' => $_POST['item']['total_value'],
                                    'total_tax' => $_POST['item']['total_tax'],
                                    'invoice_no' => $_POST['item']['invoice_no'],
                                    'invoice_date' => $_POST['item']['invoice_date'],
                                    'is_igst' => $_POST['item']['is_igst'],
                                    'shop_id' => $shop_id,
                                );
                            }
            
            $this->shops_inventory_model->updateRow($_POST['item']['id'],$updateData);

            // log generated
            $insertArray = array(
                'product_id' => $product_id[0],            
                'tax_value' => $product_id[1],
                'vendor_id' => $_POST['item']['vendor_id'],
                'purchase_rate' => $_POST['item']['purchase_rate'],
                'selling_rate' => $_POST['item']['selling_rate'],
                'qty' => $_POST['item']['qty'],
                'mrp' => $_POST['item']['mrp'],
                'mfg_date' => $_POST['item']['mfg_date'],
                'expiry_date' => $_POST['item']['expiry_date'],
                'total_value' => $_POST['item']['total_value'],
                'total_tax' => $_POST['item']['total_tax'],
                'invoice_no' => $_POST['item']['invoice_no'],
                'invoice_date' => $_POST['item']['invoice_date'],
                'is_igst' => $_POST['item']['is_igst'],
                'shop_id' => $shop_id,
                'action' => 'LATEST_UPDATE',
                'shops_inventory_id'=>$_POST['item']['id'],
            );
            $shop_inventory_id = $_POST['item']['id'];

            $data['get_inventory_log']    = $this->shop_inventory_logs_model->getMaxRow($shop_inventory_id);
            
            $inventory_log_id = $data['get_inventory_log']->id;

            $updateaction['action']='UPDATE';
            if(!empty($data['get_inventory_log']))
            {
                $this->shop_inventory_logs_model->updateRow($inventory_log_id,$updateaction);
            }
            $this->shop_inventory_logs_model->insertRow($insertArray);
  
    }
    public function deleteStockData(){
        // $this->shops_inventory_model->updateRow($_POST['item']['id'],array('status'=>'0'));
        $data['user']  = $user         = $this->checkShopLogin();
        $shop_inventory_id = $_POST['item']['id'];
        //log generated
        if($this->shops_inventory_model->delete_data('shops_inventory',$shop_inventory_id))
        {
            $insertArray = array(
                'product_id'=>$_POST['item']['product_id'],
                'qty'=>$_POST['item']['qty'],
                'purchase_rate' => $_POST['item']['purchase_rate'],
                'selling_rate' => $_POST['item']['selling_rate'],
                'mrp' => $_POST['item']['mrp'],
                'shop_id' => $user->id,
                'action'=>'DELETED',
                'shops_inventory_id'=>$_POST['item']['id'],
                'mfg_date' => $_POST['item']['mfg_date'],
                'expiry_date' => $_POST['item']['expiry_date'],
                'total_value' => $_POST['item']['total_value'],
                'vendor_id' => $_POST['item']['vendor_id'],
                'is_igst' => $_POST['item']['is_igst'],
                'invoice_no' => $_POST['item']['invoice_no'],
                'invoice_date' => $_POST['item']['invoice_date'],
                'total_tax' => $_POST['item']['total_tax'],
                'tax_value' => $_POST['item']['tax_value'],
            );
            
            $data['get_inventory_log']    = $this->shop_inventory_logs_model->getMaxRow($shop_inventory_id);
                
            $inventory_log_id = $data['get_inventory_log']->id;

            $updateaction['action']='DELETED';
            if(!empty($data['get_inventory_log']))
            {
                $this->shop_inventory_logs_model->updateRow($inventory_log_id,$updateaction);
            }
            $this->shop_inventory_logs_model->insertRow($insertArray);
        }
        // $insertArray = array(
        //     'product_id' => $checkExistingData[0]['product_id'],
        //     'qty' => $_POST['item']['qty'],
        //     'purchase_rate' => $checkExistingData[0]['purchase_rate'],
        //     'selling_rate' => $checkExistingData[0]['selling_rate'],
        //     'mrp' => $checkExistingData[0]['mrp'],
        //     'shop_id' => $checkExistingData[0]['shop_id'],
        //     'action'=>'DELETED'
        // );
        // $this->shop_inventory_logs_model->insertRow($insertArray);


    }
    public function stock_category(){
        $data['user']  = $user         = $this->checkShopLogin();
        // if($this->session->has_userdata('logged_in') && $this->session->logged_in === TRUE){
            if(!empty($user)){
            $shop_id     = $user->id;
            $shop_role_id     = $user->role_id;
            $data['shop_menus'] = $this->admin_model->get_role_menu_data($shop_role_id);
            $data['all_menus'] = $this->admin_model->get_data1('tb_admin_menu','status','1');
		    $shop_details = $this->shops_model->get_shop_data($shop_id);
            $viewData['product_category'] = $this->products_category_model->getRows(array('conditions'=>array('active'=>'1','is_parent'=>'0','is_deleted'=>'NOT_DELETED')));
            $viewData['cat_or_pro_flg'] =true;
			$template_data = array(
									'menu'=> $this->load->view('template/menu',$data,TRUE),
									'main_body_data'=> $this->load->view('shop/stocks-category',$viewData,TRUE),
                                    'shop_photo'=>$shop_details->logo
								);
			$this->load->view('template/main_template',$template_data);
		}else{
			redirect(base_url());
		}
    }
    
    
    public function stock_sub_category($category_id){
        $data['user']  = $user         = $this->checkShopLogin();
        $shop_id     = $user->id;
        $shop_role_id     = $user->role_id;
        $data['shop_menus'] = $this->admin_model->get_role_menu_data($shop_role_id);
        $data['all_menus'] = $this->admin_model->get_data1('tb_admin_menu','status','1');
        $shop_details = $this->shops_model->get_shop_data($shop_id);
        // if($this->session->has_userdata('logged_in') && $this->session->logged_in === TRUE){
            if(!empty($user)){
            $viewData['product_category'] = $this->products_category_model->getRows(array('conditions'=>array('active'=>'1','is_parent'=>$category_id,'is_deleted'=>'NOT_DELETED')));
            $viewData['cat_or_pro_flg'] =false;
            if($viewData['product_category']!==FALSE){
                
                if(count($viewData['product_category'])===1){
                    
                    redirect(base_url('stocks/category/'.$viewData['product_category'][0]['id']));
                }else{
                     
                }
            }
            else
            {
                redirect(base_url('stocks/category/'.$category_id));
            }
            $shop_id     = $user->id;
		    $shop_details = $this->shops_model->get_shop_data($shop_id);
			$template_data = array(
									'menu'=> $this->load->view('template/menu',$data,TRUE),
									'main_body_data'=> $this->load->view('shop/stocks-category',$viewData,TRUE),
                                    'shop_photo'=>$shop_details->logo
								);
			$this->load->view('template/main_template',$template_data);
		}else{
			redirect(base_url());
		}
    }
    public function show_stocks($sub_cat_id){
        $data['user']  = $user         = $this->checkShopLogin();
        $shop_id     = $user->id;
        $shop_role_id     = $user->role_id;
        $data['shop_menus'] = $this->admin_model->get_role_menu_data($shop_role_id);
        $data['all_menus'] = $this->admin_model->get_data1('tb_admin_menu','status','1');
        $shop_details = $this->shops_model->get_shop_data($shop_id);
        // if($this->session->has_userdata('logged_in') && $this->session->logged_in === TRUE){
            if(!empty($user)){
            $sub_cat_data = $this->products_category_model->getRows(array('conditions'=>array('id'=>$sub_cat_id)));
            $cat_data = $this->products_category_model->getRows(array('conditions'=>array('id'=>$sub_cat_data[0]['is_parent'])));
            $shop_id     = $user->id;
		    $shop_details = $this->shops_model->get_shop_data($shop_id);
            $viewData= array(
                                'parent_cat_id'=>$sub_cat_id,
                                'sub_cat_data' => $sub_cat_data[0],
                                'cat_data' => $cat_data[0],
                                'shop_id' => $shop_id,
                            );
			$template_data = array(
									'menu'=>$this->load->view('template/menu',$data,TRUE),
									'main_body_data'=>$this->load->view('shop/jsgrid_test',$viewData,TRUE),
                                    'shop_photo'=>$shop_details->logo
								);
			$this->load->view('template/main_template',$template_data);
		}else{
			redirect(base_url());
		}
    }
}
