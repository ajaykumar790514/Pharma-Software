<?php 
/**
 * 
 */
class Ledger extends CI_Controller
{
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('ladger_model','ladger_m');
        $this->load->model('cash_register_model');  
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

    public function index()
    {
     
        $data['user'] = $user =  $this->checkShopLogin();
        $menu_id = $this->uri->segment(2);
        $data['menu_id'] = $menu_id;
        $role_id = $user->role_id;
        $data['sub_menus'] = $this->admin_model->get_submenu_data($menu_id,$role_id);
        $data['title'] = 'Shop Master Data';
        $page = 'shop/ledger/ledger_data';
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
        $this->header_and_footer($page, $data);
    }

    public function ledger()
    {
     
        $data['user'] = $user =  $this->checkShopLogin();
        $menu_id = $this->uri->segment(2);
        $data['menu_id'] = $menu_id;
        $role_id = $user->role_id;
        $data['sub_menus'] = $this->admin_model->get_submenu_data($menu_id,$role_id);
        $data['title'] = 'Shop Master Data';
        $page = 'shop/ledger/ledger_data';
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
        $this->header_and_footer($page, $data);
    }

    

    public function cash($action=null)
    {
    	switch ($action) {
    		case null:
    			$data['menu_id'] 		= $this->uri->segment(2);
                $data['title']          = 'Cash Report';
                $data['tb_url']			= base_url().'cash-ledger/tb';
                $page                   = 'shop/ledger/cash/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $this->header_and_footer($page, $data);
    			break;

    		case 'tb':
                $data['rows'] = $this->ladger_m->cash();
                $data['shop'] = $this->ladger_m->shop_details();
                $data['cash_account']  = $this->ladger_m->cash_account();
    			$this->load->view('shop/ledger/cash/tb',$data);
    			break;
    		
    		default:
    			// code...
    			break;
    	}
    }


    public function bank($action=null)
    {
        $data['user'] = $user =  $this->checkShopLogin();
        $shop_id     = $user->id;    
    	switch ($action) {
            case null:
                $data['menu_id']        = $this->uri->segment(2);
                $data['title']          = 'Bank Report';
                $data['tb_url']         = base_url().'bank-ledger/tb';
                $data['bank_accounts']  = $this->shops_model->shop_bank_accounts($shop_id);
                $page                   = 'shop/ledger/bank/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $this->header_and_footer($page, $data);
                break;

            case 'tb':
                $data['rows'] = $this->ladger_m->bank();
                $data['shop'] = $this->ladger_m->shop_details();
                $data['bank_account']  = $this->ladger_m->bank_account();
                // echo _prx($data['bank_account']); die;
                $this->load->view('shop/ledger/bank/tb',$data);
                break;
            
            default:
                // code...
                break;
        }
    }

    public function partywise($action=null)
    {
        switch ($action) {
            case null:
                $data['menu_id']        = $this->uri->segment(2);
                $data['title']          = 'Cash Report';
                $data['vendor']         = $this->cash_register_model->getvendor();
                $data['customer']       = $this->cash_register_model->getcustomer();
                $data['tb_url']         = base_url().'party-ledger/tb';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $data['accounts']        = $this->cash_register_model->getaccounts();
                $page                   = 'shop/ledger/partywise/index';
                $this->header_and_footer($page, $data);
                break;

            case 'tb':
                
                $data['rows']    = $this->ladger_m->party();
                $data['shop']    = $this->ladger_m->shop_details();
                $this->load->view('shop/ledger/partywise/tb',$data);
                break;
            
            default:
                // code...
                break;
        }
    }

    public function monthly_report($action=null)
    {
        switch ($action) {
            case null:
                $data['menu_id']        = $this->uri->segment(2);
                $data['title']          = 'Monthly Ledger Report';
                $data['vendor']         = $this->cash_register_model->getvendor();
                $data['customer']       = $this->cash_register_model->getcustomer();
                $data['tb_url']         = base_url().'monthly-ledger-report/tb';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $page                   = 'shop/ledger/monthly_report/index';
                $this->header_and_footer($page, $data);
                break;

            case 'tb':

                $_POST['from_date'] = date('Y-m-d',strtotime($_POST['month'].'-01'));
                $_POST['to_date'] = date('Y-m-t',strtotime($_POST['month'].'-01'));

                // echo _prx($_POST);
                // die();
                // $data['opening'] = $this->ladger_m->party_opening();
                $data['rows']    = $this->ladger_m->monthly_report();
                // echo _prx($data['rows']); die;
                $data['shop']    = $this->ladger_m->shop_details();
                $this->load->view('shop/ledger/monthly_report/tb',$data);
                break;
            
            default:
                // code...
                break;
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