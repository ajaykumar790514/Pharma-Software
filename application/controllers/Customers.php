<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        $data['user']  = $user         = checkLogin();
        $this->check_role_menu();
    }

    public function isLoggedIn(){
        $is_logged_in = $this->session->userdata('admin_logged_in');
        if(!isset($is_logged_in) || $is_logged_in!==TRUE)
        {
            redirect(base_url());
            exit;
        }
    } 
    public function check_role_menu(){
        $data['user']  = $user         = checkLogin();
        $admin_role_id = $user->role_id;
        $uri = $this->uri->segment(1);
        $role_menus = $this->admin_model->all_role_menu_data($admin_role_id);
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
        $data['user']  = $user         = checkLogin();
        $admin_role_id = $user->role_id;
        $user_id = $user->id;
        $data['dashboard'] = $this->admin_model->get_row_data('admin','id',$user_id);
        $data['admin_menus'] = $this->admin_model->get_role_menu_data($admin_role_id);
        $this->load->view('admin/includes/header',$data);
        $this->load->view($page);
        $this->load->view('admin/includes/footer');
    }


    public function customers_acquisition($action=null,$p1=null,$p2=null,$p3=null,$p4=null)
    {
        $data['user']  = $user         = checkLogin();
        switch ($action) {
            case null:
                $data['title']          = 'Customers';
                $data['tb_url']         = base_url().'customers-acquisition/tb';
                $page                   = 'admin/customers/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $this->header_and_footer($page, $data);
                break;

                case 'tb':
                    $data['search'] = '';
                    $data['from_date'] = '';
                    $data['to_date'] = '';

                    //below variable section used for models and other places
                    $from_date='null';
                    $to_date='null';
                    $search='null';

                    //get section intiliazation
                    if($p2!=null)
                    {
                        $data['from_date'] = $p1;
                        $data['to_date'] = $p2;
                        $from_date = $p1;
                        $to_date = $p2;
                    }
                    if($p3!=null)
                    {
                        $data['search'] = $p3;
                        $search = $p3;
                    }
                    //end of section

                    if (@$_POST['search']) {
                        $data['search'] = $_POST['search'];
                        $search = $_POST['search'];
                    }
                    if (@$_POST['to_date']) {
                        $data['from_date'] = $_POST['from_date'];
                        $data['to_date'] = $_POST['to_date'];
                        $from_date = $_POST['from_date'];
                        $to_date = $_POST['to_date'];
                    }
                    
                    $this->load->library('pagination');
                    $config = array();
                    $config["base_url"]         = base_url()."customers-acquisition/tb/".$from_date."/".$to_date."/".$search;
                    $config["total_rows"]       = $this->customers_model->get_customers_data($from_date,$to_date,$search);
                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 10;
                    $config["uri_segment"]      = 6;
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    $data['page']               = $page = ($p4!=null) ? $p4 : 0;
                    $data['address_url']             = base_url().'customers-acquisition/customer_addresses/';
                    $data['reward_url']             = base_url().'customers-acquisition/rewards/';
                    $data['per_page']           = $config["per_page"];
                    $data['customers']           = $this->customers_model->get_customers_data($from_date,$to_date,$search,$config["per_page"],$page);
                    $page                       = 'admin/customers/tb';
                    $this->load->view($page, $data);
                    break;
                
                case 'customer_addresses':
            
                $data['address']           = $this->customers_model->address($p1);
                $page                  = 'admin/customers/customer_addresses';
                $this->load->view($page, $data);

                break;
                case 'rewards':
                    $data['cust_id'] = $p1;
                    $admin_data = $this->customers_model->display_data('admin');
                    $customer_data = $this->customers_model->get_row_data1('customers','id',$p1);
                    $data['cust_reward'] = $customer_data->rewards;
                    $data['admin_reward'] = $admin_data->rewards;
                    $page                  = 'admin/customers/add_reward';
                    $this->load->view($page,$data);

                break;
                case 'add_reward_point':
                    
                    $cust_id =  $this->input->post('cust_id'); 
                    $cust_reward =  $this->input->post('cust_reward');
                    $total =  $cust_reward + $this->input->post('rewards');
                    $data['rewards'] = $total; 
                    if($this->master_model->edit_data('customers',$cust_id,$data)) 
                    {
                        $admin_data = $this->customers_model->display_data('admin');
                        $admin['rewards'] = $admin_data->rewards - $this->input->post('rewards');
                        $this->master_model->edit_data('admin','1',$admin);
                        echo $total;
                    }

                break;
                case 'change_cust_status':
                    
                    $id = $this->input->post('id');
                    $data['status_data'] = $this->customers_model->get_row_data('customers','id',$id);

                    if($data['status_data']->active == 1)
                    {
                        $data1 = array(
                            'active' => 0
                        );
                    }
                    else if($data['status_data']->active == 0)
                    {
                        $data1 = array(
                            'active' => 1
                        );
                    }
                    $this->customers_model->edit_data('customers',$id,$data1);
                    $this->load->view('admin/customers/cust_status_view',$data);

                break;
                case 'send_notifications':
                    
                    $title = $this->input->post('title');
                    $body = $this->input->post('body');

                    // set post fields
                    
                    $post['tokens']= $this->customers_model->get_customers();
                    foreach($post['tokens'] as $post)
                    {
                        $post = [
                            'title' => $title,
                            'body' => $body,
                        ];
                        
                        $ch = curl_init('http://3.12.154.83/shopzonews_cleaningkart/shopzone/index.php/Utility/remoteNotification');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                
                        // execute!
                        $response = curl_exec($ch);
                
                        // close the connection, release resources used
                        curl_close($ch);
                
                        // do anything you want with your response
                        // var_dump($response);
                    }
                    if($response == true)
                    {
                    echo('Notification Sent Successfully');
                    }

                break;
                default:
                # code...
                break;
        }
    }
    public function change_cust_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->customers_model->get_row_data('customers','id',$id);

        if($data['status_data']->isActive == 1)
        {
            $data1 = array(
                'isActive' => 0
            );
        }
        else if($data['status_data']->isActive == 0)
        {
            $data1 = array(
                'isActive' => 1
            );
        }
        $this->customers_model->edit_data('customers',$id,$data1);
        $this->load->view('admin/customers/cust_status_view',$data);
        
    }
   
    /**
     * display B2B customers list
     *
     * @return renderable
     **/
//     public function customers_b2b_list()
//     {
//         $customersB2BList = $this->customers_model->get_b2b_customers_list();
//         $data = ['customers' => $customersB2BList];

//         $data['menu_id'] = $this->uri->segment(2);
//                 $data['title']          = 'Customers B2B';
//                 $data['tb_url']         = base_url().'products/tb';
//                 $data['new_url']        = base_url().'products/ create';
//                 $page                   = 'admin/customers/b2b/list';
//         $this->header_and_footer($page, $data);
    

// }

    /**
     * get b2b customer's business details
     *
     * @return json
     **/
    public function customer_b2b_business_details()
    {
        echo "Hello";
       // $details = $this->customers_model->get_b2b_customer_business_details($this->input->post());
        //print_r($details);
        //echo json_encode($details);
    }

    /**
     * get b2b customer's pan details
     *
     * @return json
     **/
    public function customer_b2b_pan_details()
    {
        $details = $this->customers_model->get_b2b_customer_pan_details($this->input->post());
        echo json_encode($details);
    }

    /**
     * get b2b customer's license details
     *
     * @return json
     **/
    public function customer_b2b_license_details()
    {
        $details = $this->customers_model->get_b2b_customer_license_details($this->input->post());
        echo json_encode($details);
    }

    /**
     * change b2b customer's status
     *
     * @return json
     **/
    public function customer_b2b_change_status()
    {
        $status = $this->customers_model->get_b2b_customer_change_status($this->input->post());
        echo json_encode($status);
    }
    public function customer_b2b_verify_status()
    {
        $status = $this->customers_model->get_b2b_customer_verify_status($this->input->post());
        echo json_encode($status);
    } 
    public function customers_b2b_list($action=null,$p1=null,$p2=null,$p3=null)
    {
        $data['user']  = $user         = checkLogin();
        $view_dir = 'admin/customers/b2b/';
        switch ($action) {
            case null:
                $data['menu_id'] =$menu_id= $this->uri->segment(2);
                $data['title']          = 'Customer B2B';
                $data['tb_url']         = base_url().'customers-b2b/tb/'.$menu_id;
                $page                   = 'admin/customers/b2b/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $this->header_and_footer($page, $data);
                break;

            case 'tb':
                $data['menu_id']=$p1;
                $data['search'] = '';
                $search='null';
                if($p2!=null)
                {
                    $data['search'] = $p2;
                    $search = $p2;
                }
                $data['search'] = '';
                if (@$_POST['search']) {
                    $data['search'] = $_POST['search'];
                }
            
                $this->load->library('pagination');
                $config = array();
                $config["base_url"]         = base_url()."customers-b2b/tb/".$search;
                $config["total_rows"]       = $this->customers_model->Customers_Data($search);
                $data['total_rows']         = $config["total_rows"];
                $config["per_page"]         = 10;
                $config["uri_segment"]      = 1;
                $config['attributes']       = array('class' => 'pag-link');
                $config['full_tag_open']    = "<div class='pag'>";
                $config['full_tag_close']   = "</div>";
                $config['first_link']       = '&lt;&lt;';
                $config['last_link']        = '&gt;&gt;';
                $this->pagination->initialize($config);
                $data["links"]              = $this->pagination->create_links();
                $data['page']               = $page = ($p2!=null) ? $p2 : 0;
                $data['per_page']           = $config["per_page"];
                $data['customers']           = $this->customers_model->Customers_Data($search,$config["per_page"],$page);
               
                $page                       = 'admin/customers/b2b/tb';

                
                $this->load->view($page, $data);
                break;
                case 'busines-detail':
                    $details = $this->customers_model->get_b2b_customer_business_details($this->input->post());
                    echo json_encode($details);
                    break;
                case 'pan-detail':
                    $details = $this->customers_model->get_b2b_customer_pan_details($this->input->post());
                    echo json_encode($details);
                    break;
                case 'license-detail' :
                    $details = $this->customers_model->get_b2b_customer_license_details($this->input->post());
                    echo json_encode($details);
                    break;
                case 'change_status':
                    $status = $this->customers_model->get_b2b_customer_change_status($this->input->post());
                    echo json_encode($status);
                    break;
                case 'add-credit-limit':
                   $details = $this->customers_model->get_b2b_customer_add_credit($this->input->post());
                    echo json_encode($details);
                 
                break;    
                case 'set_credit_limit':
                    $status = $this->customers_model->get_b2b_customer_credit_limit_post($this->input->post());
                    echo json_encode($status);
               break;
               case 'license':
                $data['menu_id'] = $p2;
                $data['title']          = 'Customer B2B';
                $data['tb_url']         = base_url().'customers-b2b/tb2/'.$p1;
                $page                   = 'admin/customers/b2b/license-details';
                $data['customer']       = $this->master_model->getRow('customer_personal_details',['customer_id'=>$p1]);
                $this->header_and_footer($page, $data);
                break;
                case 'tb2':

                    $data['search'] = '';
                    $search='null';
                    if($p1!=null)
                    {
                        $data['search'] = $p1;
                        $search = $p1;
                    }
                    $data['search'] = '';
                    if (@$_POST['search']) {
                        $data['search'] = $_POST['search'];
                    }
                
                    $this->load->library('pagination');
                    $config = array();
                    $config["base_url"]         = base_url()."customers-b2b/tb2/".$search;
                    $config["total_rows"]       = $this->customers_model->Customers_Data_license($p1,$search);
                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 20;
                    $config["uri_segment"]      = 1;
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    $data['page']               = $page = ($p2!=null) ? $p2 : 0;
                    $data['per_page']           = $config["per_page"];
                    $data['statur_url']           = base_url().'customers-b2b/change-license-status/';
                    $data['customers']           = $this->customers_model->Customers_Data_license($p1,$search,$config["per_page"],$page);
                    $page                       = 'admin/customers/b2b/tb2';
                    $this->load->view($page, $data);
                    break;
                     case 'change-license-status':
                        if ($this->input->server('REQUEST_METHOD')=='POST') { 
                            $return['res'] = 'error';
                            $return['msg'] = 'Not Saved!';
                            if(!empty($_POST['rejected_comment'])){
                             $data = array(
                                'status'=>$_POST['status'],
                                'rejected_comment'=>$_POST['rejected_comment']
                             );
                            }else{
                                $data = array(
                                    'status'=>$_POST['status'],
                                 ); 
                            }
                            if($this->master_model->Update('customer_license_details',$data,['id'=>$p1]))
                            {
                                $return['res'] = 'success';
                                $return['msg'] = 'Saved.';
                            }
                            echo json_encode($return);
                        }else{   
                          $data['action_url']    = base_url().'customers-b2b/change-license-status/'.$p1;
                          $data['form_id']       = uniqid();
                          $data['status']   = $this->master_model->getRow('customer_license_details',['id'=>$p1]);
                          $page                  = 'admin/customers/b2b/change_license_status';
                          $this->load->view($page, $data);
                        }
                     break; 
                    case 'lazer':
                        $data['menu_id'] = $p2;
                        $data['title']          = 'Customer B2B';
                        $data['tb_url']         = base_url().'customers-b2b/tb_lazer/'.$p1;
                        $page                   = 'admin/customers/b2b/customer_lazer';
                        $data['customer']       = $this->master_model->getRow('customer_personal_details',['customer_id'=>$p1]);
                        $this->header_and_footer($page, $data);
                   break;   
                   case 'tb_lazer':
                    $cus_id = $p1;
                    $data['search'] = '';
                    $search='null';
                    if($p2!=null)
                    {
                        $data['search'] = $p2;
                        $search = $p2;
                    }
                    $data['search'] = '';
                    if (@$_POST['search']) {
                        $data['search'] = $_POST['search'];
                    }
                
                    $this->load->library('pagination');
                    $config = array();
                    $config["base_url"]         = base_url()."customers-b2b/tb_lazer/".$search;
                    $config["total_rows"]       = count($this->customers_model->hisab_kitab_customer($cus_id,$search));
                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 20;
                    $config["uri_segment"]      = 1;
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    $data['page']               = $page = ($p3!=null) ? $p3 : 0;
                    $data['per_page']           = $config["per_page"];
                    $data['new_transaction']		= base_url('customers-b2b/create/');
                    $data['update_url']		= base_url('customers-b2b/upadte_tr/');
                    $data['delete_url']		= base_url('customers-b2b/delete/');
                    $data['statur_url']           = base_url().'customers-b2b/change-license-status/';
                    $data['customer']         = $this->customers_model->getRowValue($cus_id);
                    $data['rows']    		= $this->customers_model->hisab_kitab_customer($cus_id,$search,$config["per_page"],$page);
                    $data['opening']    		= $this->customers_model->opening_hisab_kitab_customer($cus_id);
                    $page                       = 'admin/customers/b2b/tb_lazer';
                    $this->load->view($page, $data);
                    break;  
                    case 'create':
                        $data['customer_id']    =$p1;
                        $data['title'] 		  	= 'New Transaction';
                        $data['action_url']	  	= base_url('customers-b2b/save');
                        $data['form_id']= uniqid();
                        $page                       = $view_dir.'create_lazer';
                        $this->load->view($page, $data);
                        break;
                        case 'save':
                            $id=$p1;
                            $return['res'] = 'error';
                            $return['msg'] = 'Not Saved!';
                            $saved = 0;
                            if ($this->input->server('REQUEST_METHOD')=='POST') {
                               
                                    $data = array(
                                        'customer_id'     => $this->input->post('customer_id'),
                                        'tr_date'     => $this->input->post('date'),
                                        'remark'     => $this->input->post('remark'),
                                        'credit'     => $this->input->post('credit'),
                                        'debit'     => $this->input->post('debit'),
                                      );
                                    if($this->master_model->Save('customer_transaction',$data)){
                                        $saved = 1;
                                    }
                                if ($saved == 1 ) {
                                    $return['res'] = 'success';
                                    $return['msg'] = 'Saved.';
                                }
                            }
                    
                            echo json_encode($return);
                break;
                case 'upadte_tr':
                    $data['customer_id']    =$p1;
                    $data['title'] 		  	= 'New Transaction';
                    $data['contant']      	= $view_dir.'update';
                    $data['action_url']     = base_url().'customers-b2b/update/'.$p1;
                    $data['value']          = $this->customers_model->getRowValue2('customer_transaction',$p1);
                    $data['form_id']= uniqid();
                    $page                       = $view_dir.'update_lazer';
                    $this->load->view($page, $data);
                    break;
                    case 'update':
                        $id=$p1;
                        $return['res'] = 'error';
                        $return['msg'] = 'Not Saved!';
                        $saved = 0;
                        if ($this->input->server('REQUEST_METHOD')=='POST') {
                           
                                $data = array(
                                    'tr_date'     => $this->input->post('date'),
                                    'remark'     => $this->input->post('remark'),
                                    'credit'     => $this->input->post('credit'),
                                    'debit'     => $this->input->post('debit'),
                                  );
                                if($this->master_model->Update('customer_transaction',$data,['id'=>$id])){
                                    $saved = 1;
                                }
                            if ($saved == 1 ) {
                                $return['res'] = 'success';
                                $return['msg'] = 'Saved.';
                            }
                        }
                
                        echo json_encode($return);
                             break;
                            
                            case 'delete':
                                $return['res'] = 'error';
                                $return['msg'] = 'Not Deleted!';
                                if ($p1!=null) {
                                if($this->customers_model->_delete('customer_transaction',['id'=>$p1])){
                                        $saved = 1;
                                        $return['res'] = 'success';
                                        $return['msg'] = 'Successfully deleted.';
                                    }
                                }
                                echo json_encode($return);
                                break;
                   default:
                # code...
                break;
        }
    }

 
    public function customers_license($action=null,$p1=null,$p2=null,$p3=null,$p4=null)
    {
        echo $action;die();
        $data['user']  = $user         = checkLogin();
        switch ($action) {
            case null:
                $data['title']          = 'Customers';
                $data['tb_url']         = base_url().'customers-acquisition/tb';
                $page                   = 'admin/customers/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $this->header_and_footer($page, $data);
                break;

                case 'tb':
                    $data['search'] = '';
                    $data['from_date'] = '';
                    $data['to_date'] = '';

                    //below variable section used for models and other places
                    $from_date='null';
                    $to_date='null';
                    $search='null';

                    //get section intiliazation
                    if($p2!=null)
                    {
                        $data['from_date'] = $p1;
                        $data['to_date'] = $p2;
                        $from_date = $p1;
                        $to_date = $p2;
                    }
                    if($p3!=null)
                    {
                        $data['search'] = $p3;
                        $search = $p3;
                    }
                    //end of section

                    if (@$_POST['search']) {
                        $data['search'] = $_POST['search'];
                        $search = $_POST['search'];
                    }
                    if (@$_POST['to_date']) {
                        $data['from_date'] = $_POST['from_date'];
                        $data['to_date'] = $_POST['to_date'];
                        $from_date = $_POST['from_date'];
                        $to_date = $_POST['to_date'];
                    }
                    
                    $this->load->library('pagination');
                    $config = array();
                    $config["base_url"]         = base_url()."customers-acquisition/tb/".$from_date."/".$to_date."/".$search;
                    $config["total_rows"]       = $this->customers_model->get_customers_data($from_date,$to_date,$search);
                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 10;
                    $config["uri_segment"]      = 6;
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    $data['page']               = $page = ($p4!=null) ? $p4 : 0;
                    $data['address_url']             = base_url().'customers-acquisition/customer_addresses/';
                    $data['reward_url']             = base_url().'customers-acquisition/rewards/';
                    $data['per_page']           = $config["per_page"];
                    $data['customers']           = $this->customers_model->get_customers_data($from_date,$to_date,$search,$config["per_page"],$page);
                    $page                       = 'admin/customers/tb';
                    $this->load->view($page, $data);
                    break;
                
                case 'customer_addresses':
            
                $data['address']           = $this->customers_model->address($p1);
                $page                  = 'admin/customers/customer_addresses';
                $this->load->view($page, $data);

                break;
                case 'rewards':
                    $data['cust_id'] = $p1;
                    $admin_data = $this->customers_model->display_data('admin');
                    $customer_data = $this->customers_model->get_row_data1('customers','id',$p1);
                    $data['cust_reward'] = $customer_data->rewards;
                    $data['admin_reward'] = $admin_data->rewards;
                    $page                  = 'admin/customers/add_reward';
                    $this->load->view($page,$data);

                break;
                case 'add_reward_point':
                    
                    $cust_id =  $this->input->post('cust_id'); 
                    $cust_reward =  $this->input->post('cust_reward');
                    $total =  $cust_reward + $this->input->post('rewards');
                    $data['rewards'] = $total; 
                    if($this->master_model->edit_data('customers',$cust_id,$data)) 
                    {
                        $admin_data = $this->customers_model->display_data('admin');
                        $admin['rewards'] = $admin_data->rewards - $this->input->post('rewards');
                        $this->master_model->edit_data('admin','1',$admin);
                        echo $total;
                    }

                break;
                case 'change_cust_status':
                    
                    $id = $this->input->post('id');
                    $data['status_data'] = $this->customers_model->get_row_data('customers','id',$id);

                    if($data['status_data']->active == 1)
                    {
                        $data1 = array(
                            'active' => 0
                        );
                    }
                    else if($data['status_data']->active == 0)
                    {
                        $data1 = array(
                            'active' => 1
                        );
                    }
                    $this->customers_model->edit_data('customers',$id,$data1);
                    $this->load->view('admin/customers/cust_status_view',$data);

                break;
                case 'send_notifications':
                    
                    $title = $this->input->post('title');
                    $body = $this->input->post('body');

                    // set post fields
                    
                    $post['tokens']= $this->customers_model->get_customers();
                    foreach($post['tokens'] as $post)
                    {
                        $post = [
                            'title' => $title,
                            'body' => $body,
                        ];
                        
                        $ch = curl_init('http://3.12.154.83/shopzonews_cleaningkart/shopzone/index.php/Utility/remoteNotification');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                
                        // execute!
                        $response = curl_exec($ch);
                
                        // close the connection, release resources used
                        curl_close($ch);
                
                        // do anything you want with your response
                        // var_dump($response);
                    }
                    if($response == true)
                    {
                    echo('Notification Sent Successfully');
                    }

                break;
                default:
                # code...
                break;
        }
    }
    


}