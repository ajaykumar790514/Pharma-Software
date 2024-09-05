<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        $data['user'] = $user =  $this->checkShopLogin();
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
    public function check_role_menu(){
        $data['user'] = $user =  $this->checkShopLogin();
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

    public function order($action=null,$p1=null,$p2=null)
	{
       
        $data['user'] = $user =  $this->checkShopLogin();
		$view_dir = 'shop/orders/';
		switch ($action) {
			case null:
                $this->load->model('shops_delivery_boy_model');
				$data['title'] 		= 'Orders';
				$page	= $view_dir.'index';
                $data['menu_id'] = $this->uri->segment(2);
				$data['tb_url']	  	=  base_url().'orders/tb';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
				$data['orderStatus']=$this->order_status_master_model->getRows();
                $data['orderPaymentStatusMaster']=$this->payment_mode_master_model->getRows();
                $data['delivery_boys'] =$this->shops_delivery_boy_model->delivery_boys(1000,0);
				$this->header_and_footer($page, $data);
				break;

			    case 'tb':
				$shop_id = $user->id;
					$data['search'] = '';
					$search='null';
				   
					if($p1!=null)
							{
					$data['search'] = $p1;
					$search = $p1;
							}
					if (@$_POST['search']) {
					$data['search'] = $_POST['search'];
					$search=$_POST['search'];
						   
							}
					$this->load->library('pagination');
					
					$data['contant'] 		= $view_dir.'tb';
					$config = array();
					$config["base_url"] 	= base_url()."orders/tb";
					$config["total_rows"]  	= ($this->orders_model->GetAllOrder($search));
					$data['total_rows']    	= $config["total_rows"];
					$config["per_page"]    	=10;
					$config["uri_segment"]      = $this->uri->total_segments();
					$config['attributes']  	= array('class' => 'pag-link ');
					$this->pagination->initialize($config);
					$data['links']   		= $this->pagination->create_links();
					$data['page']    		= $page = ($p2!=null) ? $p2 : 0;
					$data['search']	 		= $this->input->post('search');
                    $data['shop_details'] = $this->shops_model->get_shop_data($shop_id);
					$data['update_url']		= base_url('game-items/create/');
					$data['rows']    		= $this->orders_model->GetAllOrder($search,$config["per_page"],$page);
                   
					load_view($data['contant'],$data);
				break;
                case 'details':
                $this->load->model('shops_delivery_boy_model');
				$data['title'] 		= 'Orders';
				$page	= $view_dir.'details';
                $data['menu_id'] = $this->uri->segment(2);
				$data['tb_url']	  	=  base_url().'orders/tb';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
                $shop_details = $this->shops_model->get_shop_data($user->id);
                $data['orderStatus']=$this->order_status_master_model->getRows();
                $data['orderData']=$this->orders_model->getRows(array('conditions'=>array('id'=>$p1)));
                $data['orderItems']=$this->order_items_model->getRows(array('conditions'=>array('order_id '=>$p1)));
                $data['orderAssign']=$this->order_assign_deliver_model->getRows(array('conditions'=>array('order_id '=>$p1)));
                $data['delivery_boys']= $this->shops_delivery_boy_model->delivery_boys(1000,0);
                $data['shop_currency']=$shop_details->currency;
                $data['orderStatusData'] = $this->order_status_master_model->getRows(array('conditions'=>array('id'=>$data['orderData'][0]['status'])));
                if($data['orderData'][0]['address_id']!==''){
                    $customerData = $this->customers_model->getRows(array('conditions'=>array('id'=>$data['orderData'][0]['user_id'])));
                    $data['customerAddress'] = $this->customers_address_model->getRows(array('conditions'=>array('customer_id '=>$data['orderData'][0]['user_id'],'is_default'=>'1'))); 
                }
              
				$this->header_and_footer($page, $data);
                 break;
                 case 'print-bill':
                    $data['user'] = $user =  $this->checkShopLogin();
                    $data['invoice'] = $this->orders_model->invoice_details($p1);
                    $this->load->view('template/order_bill_print_layout',$data);
                 break;   
                 case 'updateOrderStatus':
                    $oder_id = $_POST['item']['id'];
                    $rs = $this->orders_model->get_row_order($oder_id);
                    
                    $checkExisting = $this->orders_model->getRows2($_POST['item']['id']);
                    
                    if($checkExisting!==FALSE){
                        $this->orders_model->updateRow($_POST['item']['id'],array('status'=>$_POST['item']['status']));
                        $logdata = array(
                            'status_id'=>$_POST['item']['status'],
                            'order_id'=>$_POST['item']['id'],
                        );
                        $this->order_status_master_model->SaveLog($logdata);
                    }
                    return TRUE;
                break;
               case  'updateDeliveryBoy':
                   if (@$_POST['item']['id'] && @$_POST['item']['delivery_boy']) {
                    if ($order = $this->order_assign_deliver_model->getRows(array('conditions'=>array('order_id'=>$_POST['item']['id'])))) {
                        $this->order_assign_deliver_model->updateRow($order[0]['id'],array('delivery_boy_id'=>$_POST['item']['delivery_boy']));
                    }else{
                        $this->order_assign_deliver_model->insertRow(array('delivery_boy_id'=>$_POST['item']['delivery_boy'],'order_id'=>$_POST['item']['id']));
                            $this->orders_model->updateRow($_POST['item']['id'],array('status'=>20));
            
            
                        
                    }   
                    }
                break;
			
			default:
				// code...
				break;
		}
		
	}


	// public function index(){
    //     $data['user'] = $user =  $this->checkShopLogin();
    //     if(!empty($user)){
	// 	// if($this->session->has_userdata('logged_in') && $this->session->logged_in === TRUE){
    //         $shop_id     = $user->id;
    //         $shop_role_id     = $user->role_id;
    //         $data['shop_menus'] = $this->admin_model->get_role_menu_data($shop_role_id);
    //         $data['all_menus'] = $this->admin_model->get_data1('tb_admin_menu','status','1');
	// 	    $shop_details = $this->shops_model->get_shop_data($shop_id);
    //         $this->load->model('shops_delivery_boy_model');
    //         $viewData = array(
    //                             'orderStatus'=>$this->order_status_master_model->getRows(),
    //                             'orderPaymentStatusMaster'=>$this->payment_mode_master_model->getRows(),
    //                             'delivery_boys' => $this->shops_delivery_boy_model->delivery_boys(1000,0),
    //                         );
	// 		$template_data = array(
	// 								'menu'=>$this->load->view('template/menu',$data,TRUE),
    //                                 'main_body_data'=>$this->load->view('shop/orders-table',$viewData,TRUE),
    //                                 'shop_photo'=>$shop_details->logo
	// 							);
	// 		$this->load->view('template/main_template',$template_data);
	// 	}else{
	// 		redirect(base_url());
	// 	}
    // }
    public function getOrderStatus(){
        $data['user'] = $user =  $this->checkShopLogin();
        if(isset($_POST)){
            $arrayCond = array_merge($_POST,array('active'=>'1'));
        }else{
            $arrayCond = array('active'=>'1');
        }

        $getData = $this->order_status_master_model->getRows(array('conditions'=>$arrayCond));
        $array = array(array('id'=>"0",'name'=>"Select Status"));
        foreach($getData as $data){
            $temp_array = array(
                                'id' => $data['id'],
                                'name' => $data['name']
            );
            array_push($array,$temp_array);
        }
        echo json_encode($array);
    }
    public function getOrders(){
        $data['user'] = $user =  $this->checkShopLogin();
        $shop_id     = $user->id;
        $shop_details = $this->shops_model->get_shop_data($shop_id);
        $array_cond_like=array();
        // $array_cond['shop_id'] = $_GET['shop_id'];
        $array_cond=array();
        $array_cond_in=array();
        $array_cond_join=array();
        $date=array();
        $mobile = 'null';
        $payment_mode = 'null';
        if(isset($_GET['shop_id']) && $_GET['shop_id']!==''){
            $array_cond['shop_id']=$_GET['shop_id'];
        }

        if(isset($_SESSION['order_table_filters']['from_date']) && $_SESSION['order_table_filters']['from_date']!==''){
            // $date['start_date'] = '01-07-2021';
            $date['start_date'] = $_SESSION['order_table_filters']['from_date'];
        }
        if(isset($_SESSION['order_table_filters']['to_date']) && $_SESSION['order_table_filters']['to_date']!==''){
            // $date['end_date'] = '30-10-2021';
            $date['end_date'] = $_SESSION['order_table_filters']['to_date'];
        }
        // if(isset($_SESSION['order_table_filters']['from_date']) && $_SESSION['order_table_filters']['from_date']!==''){
        //     $array_cond['datetime >='] = $_SESSION['order_table_filters']['from_date'];
        // }
        // if(isset($_SESSION['order_table_filters']['to_date']) && $_SESSION['order_table_filters']['to_date']!==''){
        //     $array_cond['datetime <='] = $_SESSION['order_table_filters']['to_date'];
        // }
        if(isset($_SESSION['order_table_filters']['status_ids']) && $_SESSION['order_table_filters']['status_ids']!==''){
            $array_cond_in['status'] = $_SESSION['order_table_filters']['status_ids'];
        }
        if(isset($_SESSION['order_table_filters']['payment_method']) && $_SESSION['order_table_filters']['payment_method']!==''){
            $payment_mode = $_SESSION['order_table_filters']['payment_method'];
        }
        if(isset($_SESSION['order_table_filters']['customer_mobile']) && $_SESSION['order_table_filters']['customer_mobile']!==''){
            $mobile = $_SESSION['order_table_filters']['customer_mobile'];
        }
        if(isset($_GET['filter']['total_value']) && $_GET['filter']['total_value']!==''){
            $array_cond['total_value'] = $_GET['filter']['total_value'];
        }
        if(isset($_GET['filter']['customer_name']) && $_GET['filter']['customer_name']!==''){
            $array_cond_join['mobile'] = $_GET['filter']['customer_name'];
        }
        if(isset($_GET['filter']['status']) && $_GET['filter']['status']!=='0'){
            $array_cond['status'] = $_GET['filter']['status'];
        }
        $arrayQuery = array(
                                'conditions'=>$array_cond,
                                'conditions_like'=>$array_cond_like,
                                'conditions_in'=>$array_cond_in,
                                'conditions_join'=>$array_cond_join,
                                'limit'=>$_GET['filter']['pageSize'],
                                'offset'=>$_GET['filter']['pageSize']*($_GET['filter']['pageIndex']-1),
                                'order_date'=>$date,
                            );
        if(isset($_GET['filter']['sortField']) && isset($_GET['filter']['sortOrder'])){
            if($_GET['filter']['sortField']==='order_id'){
                $arrayQuery['order_field']='orderid';
            }else{
                $arrayQuery['order_field']=$_GET['filter']['sortField'];
            }
            $arrayQuery['order']=$_GET['filter']['sortOrder'];
        }
        $getData = $this->orders_model->getOrdersData($arrayQuery,$mobile,$payment_mode);
        $array =array();
        $item_count=0;
        if($getData!==FALSE){
            $i=0;
            foreach($getData as $data){
                if (@$data['delivery_boy']) {
                    $delivery_boy = array('order'=>$data['id'],'row_id'=>$data['id'],'status'=>$data['status'],'delivery_boy'=>$data['delivery_boy'],'delivery_boy_id'=>$data['delivery_boy_id']);

                    // $delivery_boy = $data['delivery_boy'];
                }
                else{
                    // $this->load->model('shops_delivery_boy_model');
                    // $delivery_boys = $this->shops_delivery_boy_model->delivery_boys(1000,0);
                    // $options[''] = '-- Select delivery boy' 
                    // foreach ($delivery_boys as $drow) {
                    //     $options = 
                    // }
                    $delivery_boy = array('order'=>$data['id'],'row_id'=>$data['id'],'status'=>$data['status']);

                }
                $array[$i] = array(
                                    'id' => $data['id'],
                                    'order_id' => array('order'=>$data['id'],'row_id'=>$data['id']),
                                    'invoice_no' => $data['invoice_no'],
                                    'shop_name' => $data['shop_name'],
                                    'customer_name' => $data['customer_name'],
                                    'datetime' => $data['datetime'],
                                    'order_date' => date_format_func($data['added']),
                                    'delivery_slot'=> $data['delivery_slot'],
                                    'random_address' => $data['random_address'],
                                    'total_value' => $shop_details->currency.' '.$data['total_value'],
                                    'total_savings' => $shop_details->currency.' '.$data['total_savings'],
                                    'payment_method' => $data['payment_method'],
                                    'status' => $data['status'],
                                    'status_name' => $data['status_name'],
                                    'orderid' => $data['orderid'],
                                    'delivery_boy' => $delivery_boy,
                );
                if ($data['status']==='1') {
                    $array[$i]['print_bill'] = array('order'=>'0','row_id'=>'0');
                }else{
                    $array[$i]['print_bill'] = array('order'=>$data['id'],'row_id'=>$data['id']);    
                }
                
                $i++;
            }
            unset($arrayQuery['limit'],$arrayQuery['offset'],$arrayQuery['order_field'],$arrayQuery['order']);
            $item_count = count($this->orders_model->getOrdersData($arrayQuery,$mobile,$payment_mode));
        }
        // echo json_encode(array('data'=>$array));
        echo json_encode(array('data'=>$array,'itemsCount'=>$item_count));
    }
    public function setOrderSessionFilters(){
        if(isset($_POST['start_date']) && $_POST['start_date']!==''){
            $_SESSION['order_table_filters']['from_date'] = $_POST['start_date'];
        }
        if(isset($_POST['end_date']) && $_POST['end_date']!==''){
            $_SESSION['order_table_filters']['to_date'] = $_POST['end_date'];
        }
        if(isset($_POST['status']) && $_POST['status']!==''){
            $_SESSION['order_table_filters']['status_ids'] = array($_POST['status']);
        }
        if(isset($_POST['payment_method']) && $_POST['payment_method']!==''){
            $_SESSION['order_table_filters']['payment_method'] = $_POST['payment_method'];
        }
        if(isset($_POST['delivery_boy']) && $_POST['delivery_boy']!==''){
            $_SESSION['order_table_filters']['delivery_boy'] = $_POST['delivery_boy'];
        }
        if(isset($_POST['customer_mobile']) && $_POST['customer_mobile']!==''){
            $_SESSION['order_table_filters']['customer_mobile'] = $_POST['customer_mobile'];
        }
    }
    public function clearOrderSessionFilters(){
        unset($_SESSION['order_table_filters']);
    }
    public function orderDetails($order_id){
        $data['user'] = $user =  $this->checkShopLogin();
        if(!empty($user)){
        // if($this->session->has_userdata('logged_in') && $this->session->logged_in === TRUE){
            $this->load->model('shops_delivery_boy_model');
            $shop_id     = $user->id;
            $shop_role_id     = $user->role_id;
            $data['shop_menus'] = $this->admin_model->get_role_menu_data($shop_role_id);
            $data['all_menus'] = $this->admin_model->get_data1('tb_admin_menu','status','1');
            $shop_details = $this->shops_model->get_shop_data($shop_id);
            $viewData = array(
                                'orderStatus'=>$this->order_status_master_model->getRows(),
                                'orderData'=>$this->orders_model->getRows(array('conditions'=>array('id'=>$order_id))),
                                'orderItems'=>$this->order_items_model->getRows(array('conditions'=>array('order_id '=>$order_id))),
                                'orderAssign'=>$this->order_assign_deliver_model->getRows(array('conditions'=>array('order_id '=>$order_id))),
                                'delivery_boys'=> $this->shops_delivery_boy_model->delivery_boys(1000,0),
                                'shop_currency'=>$shop_details->currency,
                            );
            // echo _prx($viewData);
            // die();  
            $viewData['orderStatusData'] = $this->order_status_master_model->getRows(array('conditions'=>array('id'=>$viewData['orderData'][0]['status'])));
            if($viewData['orderData'][0]['address_id']!==''){
                $customerData = $this->customers_model->getRows(array('conditions'=>array('id'=>$viewData['orderData'][0]['user_id'])));
                $viewData['customerAddress'] = $this->customers_address_model->getRows(array('conditions'=>array('customer_id '=>$viewData['orderData'][0]['user_id'],'is_default'=>'1'))); 
            }
			$template_data = array(
									'menu'=>$this->load->view('template/menu',$data,TRUE),
									'main_body_data'=>$this->load->view('shop/orders-details',$viewData,TRUE),
                                    'shop_photo'=>$shop_details->logo
								);
			$this->load->view('template/main_template',$template_data);
		}else{
			redirect(base_url());
		}
    }
    public function updateOrderStatus(){
        $checkExisting = $this->orders_model->getRows(array('conditions'=>array('id'=>$_POST['item']['id'])));
        if($checkExisting!==FALSE){
            $this->orders_model->updateRow($_POST['item']['id'],array('status'=>$_POST['item']['status']));
        }
        return TRUE;
    }
    public function getNewOrdersReturn(){
        $newOrders = $this->orders_model->getNewOrdersReturnRows(array('conditions'=>array('status'=>'7','id >'=>$_SESSION['orders_notification_return_last_id'])));
        $html = '';
        $count=1;
        $cur_last_id = $_SESSION['orders_notification_return_last_id'];
        if($newOrders!==FALSE){
            $_SESSION['orders_notification_return_last_id'] = $newOrders[0]['id'];
            foreach($newOrders as $orders){
                if($count <= 5){
                    $html.= '<a href="orders/'.$orders['id'].'">
                        <div class="mail-contnet">
                            <h5>'.$orders['customer_name'].' - <small>New Order</small></h5>
                            <span class="mail-desc"></span>
                            <span class="time">'.date('M j, Y g:i a',strtotime($orders['added'])).'</span>
                        </div>
                    </a>';
                    $count++;
                }
            }
            $data['status']=TRUE;
            $data['message']= $html;
        }else{
            $data['status']=FALSE;
        }
        if($cur_last_id===0 && $cur_last_id!==$_SESSION['orders_notification_return_last_id']){
            $data['icon_status'] = TRUE;
        }else{
            $data['icon_status'] = FALSE;
        }
        echo json_encode($data);    
    }
    public function getNewOrdersCancel(){
        $newOrders = $this->orders_model->getNewOrdersCancelRows(array('conditions'=>array('status'=>'','id >'=>$_SESSION['orders_notification_cancel_last_id'])));
        $html = '';
        $count=1;
        $cur_last_id = $_SESSION['orders_notification_cancel_last_id'];
        if($newOrders!==FALSE){
            $_SESSION['orders_notification_cancel_last_id'] = $newOrders[0]['id'];
            foreach($newOrders as $orders){
                if($count <= 5){
                    $html.= '<a href="orders/'.$orders['id'].'">
                        <div class="mail-contnet">
                            <h5>'.$orders['customer_name'].' - <small>New Order</small></h5>
                            <span class="mail-desc"></span>
                            <span class="time">'.date('M j, Y g:i a',strtotime($orders['added'])).'</span>
                        </div>
                    </a>';
                    $count++;
                }
            }
            $data['status']=TRUE;
            $data['message']= $html;
        }else{
            $data['status']=FALSE;
        }
        if($cur_last_id===0 && $cur_last_id!==$_SESSION['orders_notification_cancel_last_id']){
            $data['icon_status'] = TRUE;
        }else{
            $data['icon_status'] = FALSE;
        }
        echo json_encode($data);    
    }
    public function getNewOrders(){
        $newOrders = $this->orders_model->getNewOrdersRows(array('conditions'=>array('status'=>'17','id >'=>$_SESSION['orders_notification_last_id'])));
        $html = '';
        $count=1;
        $cur_last_id = $_SESSION['orders_notification_last_id'];
        if($newOrders!==FALSE){
            $_SESSION['orders_notification_last_id'] = $newOrders[0]['id'];
            foreach($newOrders as $orders){
                if($count <= 5){
                    $html.= '<a href="orders/'.$orders['id'].'">
                        <div class="mail-contnet">
                            <h5>'.$orders['customer_name'].' - <small>New Order</small></h5>
                            <span class="mail-desc"></span>
                            <span class="time">'.date('M j, Y g:i a',strtotime($orders['added'])).'</span>
                        </div>
                    </a>';
                    $count++;
                }
            }
            $data['status']=TRUE;
            $data['message']= $html;
        }else{
            $data['status']=FALSE;
        }
        if($cur_last_id===0 && $cur_last_id!==$_SESSION['orders_notification_last_id']){
            $data['icon_status'] = TRUE;
        }else{
            $data['icon_status'] = FALSE;
        }
        echo json_encode($data);    
    }
    public function orderPrintBill($orderId) {
        $data['user'] = $user =  $this->checkShopLogin();
        $data['invoice'] = $this->orders_model->invoice_details($orderId);
        $this->load->view('template/order_bill_print_layout',$data);
    }
    public function updateOrderBillNo(){
        if (isset($_POST['id']) && isset($_POST['bill_no'])) {
            $this->orders_model->updateRow($_POST['id'],array('invoice_no'=>$_POST['bill_no']));
        }
    }

    public function assign_delivery_boy()
    {
        // echo _prx($_POST);
        if (@$_POST['order_id'] && @$_POST['assign_delivery_boy']) {
            if ($order = $this->order_assign_deliver_model->getRows(array('conditions'=>array('order_id'=>$_POST['order_id'])))) {
                if($this->order_assign_deliver_model->updateRow($order[0]['id'],array('delivery_boy_id'=>$_POST['assign_delivery_boy']))){
                    $saved = 1;
                }
            }else{
                if($this->order_assign_deliver_model->insertRow(array('delivery_boy_id'=>$_POST['assign_delivery_boy'],'order_id'=>$_POST['order_id']))){
                    $this->orders_model->updateRow($_POST['order_id'],array('status'=>20));
                    $saved = 1;
                }
            }   
        }

        $response['res']='error';
        $response['msg']='Not Saved!';
        if (@$saved) {
            $response['res']='success';
            $response['msg']='Saved.';
        }
        echo json_encode($response);
    }

    public function updateDeliveryBoy()
    {
       if (@$_POST['item']['id'] && @$_POST['item']['delivery_boy']) {
        if ($order = $this->order_assign_deliver_model->getRows(array('conditions'=>array('order_id'=>$_POST['item']['id'])))) {
            $this->order_assign_deliver_model->updateRow($order[0]['id'],array('delivery_boy_id'=>$_POST['item']['delivery_boy']));
        }else{
            $this->order_assign_deliver_model->insertRow(array('delivery_boy_id'=>$_POST['item']['delivery_boy'],'order_id'=>$_POST['item']['id']));
                $this->orders_model->updateRow($_POST['item']['id'],array('status'=>20));


            
        }   
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


}