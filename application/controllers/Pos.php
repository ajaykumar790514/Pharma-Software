<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->checkShopLogin();
        $this->check_role_menu();
        $this->load->model('pos_model');
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
    public function pos_data($action = null, $p1 = null, $p2 = null, $p3 = null, $p4 = null, $p5 = null, $p6 = null, $p7 = null)
    {
        $data['user'] = $user =  $this->checkShopLogin();
        switch ($action) {
            case null:
                $data['title']          = 'POS';
                $data['states']  = $this->shops_vendor_model->view_data('states');
                $data['cities']  = $this->shops_vendor_model->view_data('cities');
                $data['mode']  = $this->master_model->getData('pos_payment_mode',0,'ASC','order');
                $page                   = 'shop/pos/pos_index';
                $data['new_customer']        = base_url() . 'shop-pos/new_customer';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $this->header_and_footer($page, $data);
                break;
                case 'new_customer':
                    $data['remote']             = base_url() . 'shop-master-data/remote/customer/';
                    $data['action_url']         = base_url() . 'shop-pos/save_customer';
                    $data['states']  = $this->shops_vendor_model->getData('states',['is_deleted'=>'NOT_DELETED','country_id'=>'101']);
                    $data['cities']  = $this->shops_vendor_model->view_data('cities');
                    $page                       = 'shop/pos/add_customer';
                    $this->load->view($page, $data);
                    break;
                    case 'getitem':
                        $shop_id     = $user->id;
                        $search = $this->input->post();
                        // Get data
                        $data = $this->pos_model->getItem($search, $shop_id);
                        echo json_encode($data);
                        break;
                    case 'getcustomer':
                        $shop_id     = $user->id;
                        $search = $this->input->post();
                        // Get data
                        $data = $this->pos_model->getcustomer($search, $shop_id);

                        

                        echo json_encode($data);
                        break;
              case 'fetch_city':
                    if($this->input->post('state'))
                    {
                        $sid= $this->input->post('state');
                        $this->master_model->fetch_city($sid);
                    }
             break;
            case 'save_customer':
                $return['res'] = 'error';
                $return['msg'] = 'Not Saved!';

                if ($this->input->server('REQUEST_METHOD') == 'POST') {
                    $shop_id     = $user->id;
                    $data = array(
                        'fname'     => $this->input->post('fname'),
                        'lname'     => $this->input->post('lname'),
                        'dob'     => $this->input->post('dob'),
                        'mobile'              => $this->input->post('mobile'),
                        'alternate_mobile'   => $this->input->post('alternate_mobile'),
                        'state'      => $this->input->post('state'),
                        'city'        => $this->input->post('city'),
                        'address'       => $this->input->post('address'),
                        'email'        => $this->input->post('email'),
                        'gstin'        => $this->input->post('gstin'),
                        'shop_id'        => $shop_id,
                        'vendor_code'        => $this->input->post('vendor_code'),
                        'pincode'        => $this->input->post('pincode'),
                        'aadhar_no'        => $this->input->post('aadhar_no'),
                        'credit_limit'        => $this->input->post('credit_limit'),
                        'user_type'        =>'customer',
                        'isActive'        =>'1',
                        'b2b_b2c'        => $this->input->post('b2b_b2c') ? $this->input->post('b2b_b2c') : 'b2c',
                    );
                    $this->db->where('mobile', $this->input->post('mobile'));
                    $count = $this->db->count_all_results('customers');
                    if ($count > 0) {
                        $return['res'] = 'error';
                        $return['msg'] = 'Failed! Mobile Number  already exist.';
                    }
                    else{
                        if ($user_id = $this->shops_vendor_model->add_data('customers', $data)) {
                            $opening = array(
                                'user_id' => $user_id,
                                'dr_cr' => $this->input->post('dr_cr'),
                                'amount' => $this->input->post('amount'),
                                'remark' => $this->input->post('remark'),
                                );
                                $this->shops_vendor_model->vendor_opening($opening);
                                $userdata = $this->master_model->getRow('customers',['id'=>$user_id]);
                                $return['res'] = 'success';
                                $return['msg'] = 'Customer add successfully!.';
                                $return['row'] = $userdata;
                        }else{
                            $return['res'] = 'error';
                            $return['msg'] = 'Failed! Customer not add.';
                        } 
                    }  
                }
                echo json_encode($return);
                break;
             case 'check_customer_code':
                if ($this->input->post('vendor_code')) {
                    $vendor_code = $this->input->post('vendor_code');
                    if ($this->pos_model->get_customer_code($vendor_code)) {
                        echo 1;
                    }
                }
             break;
             case 'check_order_id':
             
                 $responce['res'] = 'success';
                 $responce['msg'] = 'Order Number available!';
                 if (!$this->pos_orders_model->check_order_id()) {
                     $responce['res'] = 'error';
                     $responce['msg'] = 'Order Number not available!';
                 }
                 echo json_encode($responce);
             break;
             case 'check_customer_credit_limit':
                 // $_POST['business_id']   = 46;
                 $_POST['from_date']     = date('Y-m-d');
                 $_POST['to_date']       = date('Y-m-d');
                 $totalValue             = $_POST['TotalValue'];
                
                 $this->load->model('ladger_model','ladger_m');
         
                 $opening = $this->ladger_m->party_opening();
         
                 $credit_limit     = $opening['credit_limit'];
                 $acredit_limit    = $opening['credit_limit'] - $opening['total_balance'];
                 $balance          = $opening['total_balance'] + $totalValue;
         
                 if ($credit_limit >= $balance ) {
                     $responce['res'] = 'success';
                 }
                 else{
                     $responce['res'] = 'error';
                     $responce['msg'] = "Credit limit exceeded ( Credit Limit =  $credit_limit , Available credit limit = $acredit_limit )";
                 }
         
                 echo json_encode($responce);
                 // echo json_encode($opening); 
                 // echo _prx($rows); die;
            break;

            case  'save_order':
                $responce['res'] = 'error';
                $responce['msg'] = 'Not Saved!';
                $orderid    = strtoupper('CK'.date('M').'00000');
                $shop_id = $user->id;
        
                if (@$_POST['is_pay_later']==1) :
                    $orderData['due_date']               = $_POST['due_date'];
                    $orderData['payment_method']         = NULL;
                else:
                    $orderData['payment_method']         = $_POST['payment_method'];
                    $orderData['reference_no_or_remark'] = $_POST['ref_no_or_remark'];
                 
                    $transArray = array(
                        'customer_id'       => $_POST['user_id'],
                        'dr'                => $_POST['total_value'],
                        'order_id'          => '',
                        'reference_no'      => $_POST['ref_no_or_remark'],
                        'txn_type'          => 1,
                        'PaymentDate'       => '',
                        'narration'         => $this->input->post('narration'),
                        'shop_id'           => $shop_id,
                        'updated'           => '',
                        'type'              => 'Sale',
                    );
                    
                endif;
        
                $OrderTransArray = array(
                        'customer_id'       => $_POST['user_id'],
                        'dr'                => $_POST['total_value'],
                        'order_id'          => '',
                        'reference_no'      => $_POST['ref_no_or_remark'],
                        'txn_type'          => 3,
                        'PaymentDate'       => '',
                        'narration'         => $this->input->post('narration'),
                        'shop_id'           => $shop_id,
                        'updated'           => '',
                        'type'              => 'Sale',
                    );
        
                $orderData['same_as_billing'] = $_POST['same_as_billing'];
                if (@$_POST['same_as_billing']==1) {
                    $orderData['shipping_address'] = null;
                }
                else{
                    $orderData['shipping_address'] = $_POST['shipping_address'];
                }
        
                
                
                $shop_state = $this->shops_model->get_shop_data($shop_id)->state;
                $cus_state = $this->shops_vendor_model->customers($_POST['user_id'])->state;
                $igst = ($shop_state==$cus_state) ? 0 : 1;
        
                if ($_POST['orderId']!='') {
                    $orderid = $_POST['orderId'];
                    if (!$this->pos_orders_model->check_order_id()) {
                        $responce['res'] = 'error';
                        $responce['msg'] = 'Order Number not available!';
                        echo json_encode($responce);
                        die();
                    }
                }
        
        
                $orderDate = (@$_POST['order_date']) ? $_POST['order_date'] : date('Y-m-d');
                
                $orderData['is_pay_later']              = $_POST['is_pay_later'];
                $orderData['orderid']                   = $orderid;
                $orderData['shop_id']                   = $shop_id;
                $orderData['user_id']                   = $_POST['user_id'];
                $orderData['invoice_no']                = NULL;
                $orderData['datetime']                  = $orderDate;
                $orderData['payment_mode']              = $_POST['mode'];
                $orderData['status']                    = 17;
                $orderData['total_value']               = $_POST['total_value'];
                $orderData['tax']                       = $_POST['total_tax'];
                $orderData['total_savings']             = '';
                $orderData['remark']                    = NULL;
                $orderData['added']                     = date('Y-m-d H:s:i');
                $orderData['payment_transaction_code']  = NULL;
                $orderData['address_id']                = NULL;
                $orderData['random_address']            = $_POST['random_address'];
                $orderData['timeslot_starttime']        = NULL;
                $orderData['timeslot_endtime']          = NULL;
                $orderData['time_slot_id']              = NULL;
                $orderData['razorpay_order_id']         = NULL;
                $orderData['razorpay_payment_id']       = NULL;
                $orderData['razorpay_signature']        = NULL;
                $orderData['booking_name']              = NULL;
                $orderData['booking_contact']           = NULL;
                $orderData['bank_name']                 = NULL;
                $orderData['is_igst']                   = $igst;
                $orderData['cancellation_reason_id']    = NULL;
                $orderData['cancellation_comment']      = NULL;
                $orderData['narration']                 = $this->input->post('narration');
        
                if ($id = $this->pos_model->save_order($orderData)) {
        
                    $responce['res'] = 'success';
                    $responce['msg'] = 'Saved!';
                    $responce['invoice_url'] = base_url('pos_orders/print/bill/'.$id);
                    $responce['new_order'] = base_url('shop-pos');
        
                    if (@$_POST['orderId']=='') {
                        $idlen  = strlen($id);
                        $orderid    = substr_replace($orderid, '', -$idlen).$id;
                        $udata['orderid'] = $orderid;
                        $this->pos_model->update_order($udata,$id);
                    }
        
                    $OrderTransArray['order_id'] = $id;
                    $OrderTransArray['PaymentDate'] = $orderDate;
                    $this->load->model('cash_register_model');
                    $this->cash_register_model->add_data('cash_register', $OrderTransArray);
        
                    if (@$transArray) {
                        $transArray['order_id'] = $id;
                        $transArray['PaymentDate'] = $orderDate;
                        $this->cash_register_model->add_data('cash_register', $transArray);
                    }
                    
                    
                    
        
                    foreach ($_POST as $key => $value) {
                        $_POST[$key] = explode(',', $value);
                    }
        
                    $orderItem = [];
                    foreach ($_POST['product_id'] as $key => $value) {
                        $orderItemTmp['inventory_id']   = $_POST['inventory_id'][$key];
                        $orderItemTmp['product_id']     = $_POST['product_id'][$key];
                        $orderItemTmp['order_id']       = $id;
                        $orderItemTmp['qty']            = $_POST['qty'][$key];
                        // $orderItemTmp['free']           = $_POST['free'][$key];
                        $orderItemTmp['price_per_unit'] = $_POST['price_per_unit'][$key];
                        $orderItemTmp['purchase_rate']  = $_POST['purchase_rate'][$key];
                        $orderItemTmp['mrp']            = $_POST['mrp'][$key];
                        $orderItemTmp['total_price']    = $_POST['total_price'][$key];
                        $orderItemTmp['tax']            = $_POST['tax'][$key];
                        $orderItemTmp['tax_value']      = $_POST['tax_value'][$key];
                        $orderItemTmp['offer_applied']  = $_POST['offer_applied'][$key];
                        $orderItemTmp['discount_type']  = $_POST['discount_type'][$key];
                        $orderItemTmp['offer_applied2'] = $_POST['offer_applied2'][$key];
                        $orderItemTmp['discount_type2'] = $_POST['discount_type2'][$key];
                        $orderItem[] = $orderItemTmp;
                        unset($orderItemTmp);
        
                    }
        
                    if($this->db->insert_batch('pos_order_items', $orderItem)){
                        foreach ($_POST['product_id'] as $key => $value) {
                            $inventryCond['id']         = $_POST['inventory_id'][$key];
                            $inventryCond['product_id'] = $_POST['product_id'][$key];
                            $qty = $_POST['qty'][$key];
                            $qty = (int)$qty;
                            if (@$_POST['free'][$key]) {
                                $qty = $qty + (int)$_POST['free'][$key];
                            }
                            $this->pos_model->update_inventry($inventryCond,$qty);
                        }
                    }
        
                }
        
                
                echo json_encode($responce);
          break;
        
         
        }
    }
    public function select_customer(){
        echo _prx($_GET);

        $this->db->order_by('name','asc');
        $vendors = $this->db->get('vendors')->result();
        $html = '';
        foreach ($vendors as $key => $value) {
        $html .= "<option value='$value->id'>$value->name</option>";
        }

        echo $html;

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
