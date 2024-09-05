<?php 

/**
 * 
 */
class Purchase_return extends CI_Controller
{

        public function __construct()
        {
            parent::__construct();
            $this->load->model('return_model');
            $this->checkShopLogin();
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
    public function index()
    {
        $data['user'] = $user =  $this->checkShopLogin();
            $shop_id     = $user->id;
            $shop_role_id     = $user->role_id;
		$data['shop_menus'] = $this->admin_model->get_role_menu_data($shop_role_id);
        $data['all_menus']  = $this->admin_model->get_data1('tb_admin_menu','status','1');
        $data['tb_url']     = base_url() . 'purchase_return/list';
        $data['parent_cat'] = $this->master_model->get_data('products_category', 'is_parent', '0');
        $data['vendors']  = $this->master_model->get_vendors($shop_id);
	    $shop_details = $this->shops_model->get_shop_data($shop_id);
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
       
		$template_data = array(
						'menu'=>$this->load->view('template/menu',$data,TRUE),
                        'main_body_data'=>$this->load->view('shop/purchase_return/index',$data,TRUE),
                        'shop_photo'=>$shop_details->logo,
                        'breadcrumb'=>$data['breadcrumb'] ,
							);
		$this->load->view('template/main_template',$template_data);
    		  	
    }

    public function list($value='')
    {
        $data['user'] = $user =  $this->checkShopLogin();
        $shop_id     = $user->id;
        $shop_role_id     = $user->role_id;
        $data['update_url']=base_url() . 'purchase_return/update_purchase_return/';
        $data['rows'] = $this->return_model->get_purchase_return();
        $this->load->view('shop/purchase_return/list',$data);

        // echo _prx($data['rows']);
        // echo _prx($_POST);
        // echo "string";
    }
    public function update_purchase_return($id='')
    {
        $data['user'] = $user =  $this->checkShopLogin();
        $data['id']=$id;
        $data['row'] = $this->return_model->get_purchase_return_update($id);
        $this->load->view('shop/purchase_return/update_return',$data);
    }

    public function store_update()
    {
        $data['user'] = $user =  $this->checkShopLogin();
        $shop_id     = $user->id;
        $shop_role_id     = $user->role_id;
        $response['res']    = 'error';
        $response['msg']    = 'Error!';
        // echo _prx($_POST);
        $id = $_POST['id'];

        if (@$_POST['vendor_id'] && @$_POST['product_id'] && @$_POST['return_qty'] && @$_POST['return_rate'] && @$_POST['return_total'] && @$_POST['stock_id'] ) {
            $post = $this->input->post();

            $insertArray = array(
                'vendor_id'     => $post['vendor_id'],
                'product_id'    => $post['product_id'],
                'qty'           => $post['return_qty'],
                'rate'          => $post['return_rate'],
                'total'         => $post['return_total'],
                'remark'        => $post['remark'],
                'inventory_id'  => $post['stock_id'],
                'date'          => $post['return_date'],
                'created_at'    => date('Y-m-d H:i:s'),
            );

            $TransArray = array(
                'customer_id'       => $post['vendor_id'],
                'cr'                => $post['return_total'],
                'inventory_id'      => $post['stock_id'],
                'product_id'        => $post['product_id'],
                'txn_type'          => 6,
                'PaymentDate'       => $post['return_date'],
                'narration'         => $post['remark'],
                'shop_id'           => $shop_id,
                'type'              => 'Purchase Return',
            );
            $invoice = $this->master_model->getRow('purchase_return',['id'=>$id]);
            if($invoice->qty!=$post['return_qty']){
            $inventory_update_query = "UPDATE `shops_inventory` SET qty = qty - ".$post['return_qty']." WHERE `id` = ".$post['stock_id'];
            }

            $log   = $this->shop_inventory_logs_model->getMaxRow($post['stock_id']);

            $logArray = array(
                'product_id' => $post['product_id'],
                'qty' =>  $post['return_qty'],
                'purchase_rate' =>  $post['return_rate'],
                'shop_id' =>  $shop_id,
                'action' => 'PURCHASE_RETURN',
                'shops_inventory_id' =>  $post['stock_id'],
                'total_value' =>  $post['return_total'],
                'vendor_id' => $log->vendor_id,
                'invoice_no' =>  '',
                'invoice_date' =>  $post['return_date'],
            );

            if (((int)$log->qty - (int)$post['return_qty']) < 0) {
                $response['msg'] = "The Return quantity is higher than the stock quantity!";
                echo json_encode($response);
                die();
            }
            // die();

            // echo _prx($log);
            $this->db->trans_begin();

            $this->db->update('purchase_return',$insertArray,['id'=>$id]);
            
            $updateArray['invoice_no'] = $invoice->invoice_no;
            $this->db->update('cash_register',$TransArray,['return_id'=>$id]);
            if($invoice->qty!=$post['return_qty']){
            $this->db->query($inventory_update_query);
            }
            // $this->db->query($inventory_log_update_query);
            $logArray['invoice_no'] = $updateArray['invoice_no'];
            $this->db->insert('shop_inventory_logs',$logArray);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
                $response['res']    = 'success';
                $response['msg']    = 'Update Successfully!.';
            }

        }
        else{
            $response['res']    = 'warning';
            $response['msg']    = 'Fill all required fields and select Stock!';
        }
        echo json_encode($response);
    }

    public function delete_purchase_return() {
        $id = $this->input->post('id');
        $this->db->update('purchase_return', array('is_deleted' => 'DELETED'), array('id' => $id)); 

        if ($this->db->affected_rows() > 0) {
            $this->db->update('cash_register', array('is_deleted' => 'DELETED'), array('return_id' => $id)); 
            $rows = $this->return_model->get_purchase_return();
            $tbody_content = '';
            if ($rows) {
                foreach ($rows as $key => $value) {
                    $i = $key + 1;
                    $update_url = base_url('purchase_return/update/'); // Adjust as needed
                    $tbody_content .= "<tr>
                        <td> $i </td>
                        <td> $value->product_name </td>
                        <td int> $value->qty </td>
                        <td int> $value->rate </td>
                        <td int> $value->total </td>
                        <td> " . date_format_func($value->date) . " </td>
                        <td> $value->invoice_no </td>
                        <td> $value->remark </td>
                        <td>
                            <a href='javascript:void(0)' data-toggle='modal' data-target='#showModal' data-whatever='Update Purchase Return ( $value->product_name )' data-url='" . $update_url . $value->id . "'>
                                <i class='fa fa-edit'></i>
                            </a>
                            <a href='javascript:void(0)' onclick='delete_purchase_return($value->id)'>
                                <i class='fa fa-trash'></i>
                            </a>
                        </td>
                    </tr>";
                }
            } else {
                $tbody_content .= "<tr><td colspan='9' class='text-center text-danger'>Data Not Found!</td></tr>";
            }
        } else {
            // If no row was affected, return an error message
            $tbody_content = "<tr><td colspan='9' class='text-center text-danger'>Error: Could not delete the record.</td></tr>";
        }
    
        echo $tbody_content;
    }
    
    
    
    
    
    public function return()
    {
        $data['user'] = $user =  $this->checkShopLogin();
        $shop_id     = $user->id;
        $shop_role_id     = $user->role_id;
    	$this->load->view('shop/purchase_return/return');
    }

    public function store()
    {
        $data['user'] = $user =  $this->checkShopLogin();
        $shop_id     = $user->id;
        $shop_role_id     = $user->role_id;
        $response['res']    = 'error';
        $response['msg']    = 'Error!';
        // echo _prx($_POST);

        if (@$_POST['vendor_id'] && @$_POST['product_id'] && @$_POST['return_qty'] && @$_POST['return_rate'] && @$_POST['return_total'] && @$_POST['stock_id'] ) {
            $post = $this->input->post();

            $insertArray = array(
                'vendor_id'     => $post['vendor_id'],
                'product_id'    => $post['product_id'],
                'qty'           => $post['return_qty'],
                'rate'          => $post['return_rate'],
                'total'         => $post['return_total'],
                'invoice_no'    => '',
                'remark'        => $post['remark'],
                'inventory_id'  => $post['stock_id'],
                'date'          => $post['return_date'],
                'created_at'    => date('Y-m-d H:i:s'),
            );

            $TransArray = array(
                'customer_id'       => $post['vendor_id'],
                'cr'                => $post['return_total'],
                'inventory_id'      => $post['stock_id'],
                'product_id'        => $post['product_id'],
                'reference_no'      => '',
                'txn_type'          => 6,
                'PaymentDate'       => $post['return_date'],
                'narration'         => $post['remark'],
                'shop_id'           => $shop_id,
                'updated'           => '',
                'type'              => 'Purchase Return',
            );

            $inventory_update_query = "UPDATE `shops_inventory` SET qty = qty - ".$post['return_qty']." WHERE `id` = ".$post['stock_id'];

            // $inventory_log_update_query = "UPDATE `shop_inventory_logs` SET action = 'UPDATE' WHERE `shops_inventory_id` = ".$post['stock_id']." AND action = 'LATEST_UPDATE'";

            // $this->db->set('qty', 'qty + '.$post['return_qty'], FALSE);
            // $this->db->where('id', $post['stock_id']);
            // $this->db->update('shops_inventory');

            $log   = $this->shop_inventory_logs_model->getMaxRow($post['stock_id']);

            $logArray = array(
                'product_id' => $post['product_id'],
                'qty' =>  $post['return_qty'],
                'purchase_rate' =>  $post['return_rate'],
                'shop_id' =>  $shop_id,
                'action' => 'PURCHASE_RETURN',
                'shops_inventory_id' =>  $post['stock_id'],
                'total_value' =>  $post['return_total'],
                'vendor_id' => $log->vendor_id,
                'invoice_no' =>  '',
                'invoice_date' =>  $post['return_date'],
            );

            if (((int)$log->qty - (int)$post['return_qty']) < 0) {
                $response['msg'] = "The Return quantity is higher than the stock quantity!";
                echo json_encode($response);
                die();
            }
            // die();

            // echo _prx($log);
            $this->db->trans_begin();

            $this->db->insert('purchase_return',$insertArray);
            $insert_id = $this->db->insert_id();
            $updateArray['invoice_no'] = _purchase_return_invoice_no($insert_id);
            $this->db->where('id',$insert_id);
            $this->db->update('purchase_return',$updateArray);
            $TransArray['return_id']    = $insert_id;
            $TransArray['reference_no'] = $updateArray['invoice_no'];
            $this->db->insert('cash_register',$TransArray);
            $this->db->query($inventory_update_query);
            // $this->db->query($inventory_log_update_query);
            $logArray['invoice_no'] = $updateArray['invoice_no'];
            $this->db->insert('shop_inventory_logs',$logArray);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
                $response['res']    = 'success';
                $response['msg']    = 'Saved';
            }

        }
        else{
            $response['res']    = 'warning';
            $response['msg']    = 'Fill all required fields and select Stock!';
        }
        echo json_encode($response);
    }

    public function get_stocks($pro_id,$vendor_id)
    {
        $data['user'] = $user =  $this->checkShopLogin();
        $shop_id     = $user->id;
        $shop_role_id     = $user->role_id;
       if ($_SEVER['HTTP_REFERER'] = base_url('sale_return')) {
           if($stocks = $this->return_model->get_stocks_purchase($shop_id,$pro_id,$vendor_id)):
                $data['stocks'] = $stocks;
                $this->load->view('shop/purchase_return/stocks',$data);
           else:
            echo "<h2 class='text-center text-danger w-100'>Stock Not Available!</h2>";
           endif;
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


 ?>