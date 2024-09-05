<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Reports extends CI_Controller {

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

    
    public function tax()
    {
        $data['user'] = $user =  $this->checkShopLogin();
        $menu_id = $this->uri->segment(2);
        $data['menu_id'] = $menu_id;
        $role_id = $user->role_id;
        $data['sub_menus'] = $this->admin_model->get_submenu_data($menu_id,$role_id);
        $data['title'] = 'Sales & Purchase Data';
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
        $page = 'shop/tax/tax_data';
        $this->header_and_footer($page, $data);
    }
    public function header_and_footer($page, $data)
    {
        $data['user']  = $user         = $this->checkShopLogin();
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
        $data['user']  = $user         = $this->checkShopLogin();
        $data['title'] = 'Master';
        $menu_id = $this->uri->segment(2);
        $data['menu_id'] = $menu_id;
        $role_id = $user->role_id;
        $data['sub_menus'] = $this->admin_model->get_submenu_data($menu_id,$role_id);
        $page = 'shop/reports/reports_data';
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
        $this->header_and_footer($page, $data);
    }
    public function register()
    {
        $data['user']  = $user         = $this->checkShopLogin();
        $data['title'] = 'Master';
        $menu_id = $this->uri->segment(2);
        $data['menu_id'] = $menu_id;
        $role_id = $user->role_id;
        $data['sub_menus'] = $this->admin_model->get_submenu_data($menu_id,$role_id);
        $page = 'shop/reports/register_data';
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
        $this->header_and_footer($page, $data);
    }
    
    
    public function stock_report($action=null,$p1=null,$p2=null,$p3=null,$p4=null,$p5=null)
    {
        $data['user']  = $user         = $this->checkShopLogin();
        switch ($action) {
            case null:
                $data['title']          = 'Low Stock Report';
                $data['tb_url']         = base_url().'stock-report/tb';
                $page                   = 'shop/reports/stocks_report/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $this->header_and_footer($page, $data);
                break;

                case 'tb':
                    $data['search'] = '';
                    $data['cat_id'] = '';
                    $data['parent_id'] = '';
                    $data['child_cat_id'] = '';
                    //below variable section used for models and other places
                    $cat_id='null';
                    $parent_id='null';
                    $search='null';
                    $child_cat_id='null';
                    $pro_id = array();
                    //get section intiliazation

                    if($p1!=null)
                    {
                        $data['cat_id'] = $p2;
                        $data['parent_id'] = $p1;
                        $cat_id = $p2;
                        $parent_id = $p1;
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $p1 , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $p1])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                    }
                    if($p2!=null)
                    {
                    
                        $data['cat_id'] = $p2;
                        $data['parent_id'] = $p1;
                        $cat_id = $p2;
                        $parent_id = $p1;
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $p1 , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $p2])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                    }
                    if($p3!=null)
                    {
                      
                        $data['child_cat_id'] = $p3;
                        $child_cat_id = $p3;
                        $data['child_cat'] = $this->db->get_where('products_category',['is_parent' => $p2 , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $p3])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                    }
                    if($p4!=null)
                    {
                        $data['search'] = $p4;
                        $search = $p4;
                    }
                    //end of section
          
          
                    if (@$_POST['search']) {
                        $data['search'] = $_POST['search'];
                        $search=$_POST['search'];
                   
                    }
                    if (@$_POST['parent_id']) {
                        $data['cat_id'] = $_POST['cat_id'];
                        $data['parent_id'] = $_POST['parent_id'];
                        $cat_id = $_POST['cat_id'];
                        $parent_id = $_POST['parent_id'];
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $_POST['parent_id'] , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['parent_id']])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                        // print_r($get_proid); die;
                    }
                    if (@$_POST['cat_id']) {
                        $data['cat_id'] = $_POST['cat_id'];
                        $data['parent_id'] = $_POST['parent_id'];
                        $cat_id = $_POST['cat_id'];
                        $parent_id = $_POST['parent_id'];
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $_POST['parent_id'] , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['cat_id']])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                        // print_r($get_proid); die;
                    }
                    if (@$_POST['child_cat_id']) {
                        $data['child_cat_id'] = $_POST['child_cat_id'];
                        $child_cat_id = $_POST['child_cat_id'];
                        $data['child_cat'] = $this->db->get_where('products_category',['is_parent' => $_POST['cat_id'] , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['child_cat_id']])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                        // print_r($get_proid); die;
                    }
             
                    $this->load->library('pagination');
                    $config = array();
                    
                    $shop_id     = $user->id;
                    $config["base_url"]         = base_url()."stock-report/tb/".$parent_id."/".$cat_id."/".$child_cat_id."/".$search;
                    $config["total_rows"]       = $this->reports_model->get_stock_report($parent_id,$pro_id,$cat_id,$child_cat_id,$search,$shop_id);
                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 10;
                    $config["uri_segment"]      = $this->uri->total_segments();
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    $data['page']               = $page = ($p5!=null) ? $p5 : 0;
                    $data['per_page']           = $config["per_page"];
                    $data['parent_cat'] = $this->master_model->get_data('products_category','is_parent','0');
                    $data['stock_report']           = $this->reports_model->get_stock_report($parent_id,$pro_id,$cat_id,$child_cat_id,$search,$shop_id,$config["per_page"],$page);
                    $data['low_stock_result']           = $this->reports_model->get_stock_report_result($cat_id,$pro_id,$shop_id,$search);
                    $data['cat_pro_map']         = $this->master_model->get_cat_pro_map_for_product_list();
                    if (@$_POST['cat_id'] || @$_POST['child_cat_id']) {
                        if (empty($pro_id)) {
                            $config["total_rows"] = array();
                            $data['stock_report'] = array();
                        }
                    }
                    $page                       = 'shop/reports/stocks_report/tb';
                    $this->load->view($page, $data);
                    break;

                    case 'export_to_excel':
                
                        $shop_id     = $user->id;
                        $productData = $this->reports_model->export_stock_report($shop_id,$p1,$p2,$p3,$p4);
                        $cat_pro_map   =$this->master_model->get_cat_pro_map_for_product_list();
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();
                        $sheet->setCellValue('A1', 'S.No.');
                        $sheet->setCellValue('B1', 'Product Name');
                        $sheet->setCellValue('C1', 'Purchase Rate');
                        $sheet->setCellValue('D1', 'Sale Price');
                        $sheet->setCellValue('E1', 'Product Code');
                        $sheet->setCellValue('F1', 'Invoice No');
                        $sheet->setCellValue('G1', 'Pack Size');
                        $sheet->setCellValue('H1', 'Stock');
                        $sheet->setCellValue('I1', 'Parent Category');
                        $sheet->setCellValue('J1', 'Sub Category');
                        $sheet->setCellValue('K1', 'Category');
                        $count = 2; $i=1;
                        foreach($productData as $pData){
                            $cat_name = [];
                            foreach ($cat_pro_map as $cat) {
                               if($cat->pro_id == $pData->prod_id){
                                   $cat_name[] =  '('.$cat->name.') ';
                               } 
                               
                           }
                            $sheet->setCellValue('A'.$count, $i++);
                            $sheet->setCellValue('B'.$count, $pData->prod_name);
                            $sheet->setCellValue('C'.$count, $pData->purchase_rate);
                            $sheet->setCellValue('D'.$count, $pData->selling_rate);
                            $sheet->setCellValue('E'.$count, $pData->product_code);
                            $sheet->setCellValue('F'.$count, $pData->invoice_no);
                            $sheet->setCellValue('G'.$count, $pData->unit_value .' '.$pData->unit_type);
                            $sheet->setCellValue('H'.$count, $pData->qty .' '.$pData->unit_type);
                            $sheet->setCellValue('I'.$count, @$cat_name[0]);
                            $sheet->setCellValue('J'.$count, @$cat_name[1]);
                            $sheet->setCellValue('K'.$count, @$cat_name[2]);
                            $count++;
                        }
                        $writer = new Xlsx($spreadsheet);
                        $filename = 'Low_Stock_Report';
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="'. $filename .'.xls"');
                        header('Cache-Control: max-age=0');
                        $writer->save('php://output'); // download file
                        break;
                
                default:
                # code...
                break;
        }
    }
    public function product_stock_report($action=null,$p1=null,$p2=null,$p3=null,$p4=null,$p5=null)
    {
        $data['user']  = $user         = $this->checkShopLogin();
        switch ($action) {
            case null:
                $data['title']          = 'Stock Report';
                $data['tb_url']         = base_url().'product-stock-report/tb';
                $page                   = 'shop/reports/product_stocks_report/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $this->header_and_footer($page, $data);
                break;

                case 'tb':
                    $data['search'] = '';
                    $data['cat_id'] = '';
                    $data['parent_id'] = '';
                    $data['child_cat_id'] = '';
                    //below variable section used for models and other places
                    $cat_id='null';
                    $parent_id='null';
                    $search='null';
                    $child_cat_id='null';
                    $pro_id = array();
                    //get section intiliazation
                     
                    if($p1!=null)
                    {
                        $data['cat_id'] = $p2;
                        $data['parent_id'] = $p1;
                        $cat_id = $p2;
                        $parent_id = $p1;
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $p1 , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $p1])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                    }
                    if($p2!=null)
                    {
                    
                        $data['cat_id'] = $p2;
                        $data['parent_id'] = $p1;
                        $cat_id = $p2;
                        $parent_id = $p1;
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $p1 , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $p2])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                    }
                    if($p3!=null)
                    {
                      
                        $data['child_cat_id'] = $p3;
                        $child_cat_id = $p3;
                        $data['child_cat'] = $this->db->get_where('products_category',['is_parent' => $p2 , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $p3])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                    }
                    if($p4!=null)
                    {
                        $data['search'] = $p4;
                        $search = $p4;
                    }
                    //end of section
          
          
                    if (@$_POST['search']) {
                        $data['search'] = $_POST['search'];
                        $search=$_POST['search'];
                   
                    }
                    if (@$_POST['parent_id']) {
                        $data['cat_id'] = $_POST['cat_id'];
                        $data['parent_id'] = $_POST['parent_id'];
                        $cat_id = $_POST['cat_id'];
                        $parent_id = $_POST['parent_id'];
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $_POST['parent_id'] , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['parent_id']])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                        // print_r($get_proid); die;
                    }
                    if (@$_POST['cat_id']) {
                        $data['cat_id'] = $_POST['cat_id'];
                        $data['parent_id'] = $_POST['parent_id'];
                        $cat_id = $_POST['cat_id'];
                        $parent_id = $_POST['parent_id'];
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $_POST['parent_id'] , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['cat_id']])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                        // print_r($get_proid); die;
                    }
                    if (@$_POST['child_cat_id']) {
                        $data['child_cat_id'] = $_POST['child_cat_id'];
                        $child_cat_id = $_POST['child_cat_id'];
                        $data['child_cat'] = $this->db->get_where('products_category',['is_parent' => $_POST['cat_id'] , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['child_cat_id']])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                        // print_r($get_proid); die;
                    }    
                    $this->load->library('pagination');
                    $config = array();
                    
                    $shop_id     = $user->id;
                    $config["base_url"]         = base_url()."product-stock-report/tb/".$parent_id."/".$cat_id."/".$child_cat_id."/".$search;
                    $config["total_rows"]       = $this->reports_model->get_product_stock_report($parent_id,$pro_id,$cat_id,$child_cat_id,$search,$shop_id);
                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 10;
                    $config["uri_segment"]      = $this->uri->total_segments();
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    $data['page']               = $page = ($p5!=null) ? $p5 : 0;
                    $data['per_page']           = $config["per_page"];
                    $data['parent_cat'] = $this->master_model->get_data('products_category','is_parent','0');
                    $data['stock_report']           = $this->reports_model->get_product_stock_report($parent_id,$pro_id,$cat_id,$child_cat_id,$search,$shop_id,$config["per_page"],$page);
                     $data['stock_result']           = $this->reports_model->get_product_stock_report_result($cat_id,$pro_id,$shop_id,$search);
                    $data['cat_pro_map']         = $this->master_model->get_cat_pro_map_for_product_list();
                    if (@$_POST['cat_id'] || @$_POST['child_cat_id']) {
                        if (empty($pro_id)) {
                            $config["total_rows"] = array();
                            $data['stock_report'] = array();
                        }
                    }
                    $page                       = 'shop/reports/product_stocks_report/tb';
                    $this->load->view($page, $data);
                    break;

                    case 'export_to_excel':
                
                        $shop_id     = $user->id;
                        $productData = $this->reports_model->export_product_stock_report($shop_id,$p1,$p2,$p3,$p4);
                        $cat_pro_map   =$this->master_model->get_cat_pro_map_for_product_list();
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();
                        $sheet->setCellValue('A1', 'S.No');
                        $sheet->setCellValue('B1', 'Product Name');
                        $sheet->setCellValue('C1', 'Purchase Rate');
                        $sheet->setCellValue('D1', 'Sale Price');
                        $sheet->setCellValue('E1', 'Product Code');
                        $sheet->setCellValue('F1', 'Invoice No.');
                        $sheet->setCellValue('G1', 'Pack Size');
                        $sheet->setCellValue('H1', 'Stock');
                        $sheet->setCellValue('I1', 'Parent Category');
                        $sheet->setCellValue('J1', 'Sub Category');
                        $sheet->setCellValue('K1', 'Category');
                        $count = 2;$i=1;
                        foreach($productData as $pData){
                            $cat_name = [];
                            foreach ($cat_pro_map as $cat) {
                               if($cat->pro_id == $pData->prod_id){
                                   $cat_name[] =  '('.$cat->name.') ';
                               } 
                               
                           }
                            $sheet->setCellValue('A'.$count, $i++);
                            $sheet->setCellValue('B'.$count, $pData->prod_name);
                            $sheet->setCellValue('C'.$count, $pData->purchase_rate);
                            $sheet->setCellValue('D'.$count, $pData->selling_rate);
                            $sheet->setCellValue('E'.$count, $pData->product_code);
                            $sheet->setCellValue('F'.$count, $pData->invoice_no);
                            $sheet->setCellValue('G'.$count, $pData->unit_value .' '.$pData->unit_type);
                            $sheet->setCellValue('H'.$count, $pData->qty .' '.$pData->unit_type);
                            $sheet->setCellValue('I'.$count, @$cat_name[0]);
                            $sheet->setCellValue('J'.$count, @$cat_name[1]);
                            $sheet->setCellValue('K'.$count, @$cat_name[2]);
                            $count++;
                        }
                        $writer = new Xlsx($spreadsheet);
                        $filename = 'Stock_Report';
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="'. $filename .'.xls"');
                        header('Cache-Control: max-age=0');
                        $writer->save('php://output'); // download file
                        break;
                
                default:
                # code...
                break;
        }
    }

    public function sales_report_accounting($action=null,$p1=null,$p2=null,$p3=null,$p4=null,$p5=null)
    {
        $data['user']  = $user         = $this->checkShopLogin();
        switch ($action) {
            case null:
                $data['title']          = 'Sales Report Accounting';
                $data['tb_url']         = base_url().'sales-report-accounting/tb';
                $page                   = 'shop/reports/sales_report_accounting/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $this->header_and_footer($page, $data);
                break;

                case 'tb':
                
                    $data['from_date'] = '';
                    $data['to_date'] = '';
                    $data['group_by'] = 'Days';
                    $data['status_id'] = '';
                    //below variable section used for models and other places
                    $from_date='null';
                    $to_date='null';
                    $group_by='null';
                    $status_id='null';

                    //get section intiliazation
                    if($p2!=null)
                    {
                        $data['from_date'] = $p1;
                        $data['to_date'] = $p2;
                        $data['group_by'] = $p3;
                        $data['status_id'] = $p4;
                        $from_date = $p1;
                        $to_date = $p2;
                        $group_by=$p3;
                        $status_id=$p4;
                    }
                    if ($p3!=null) {
                        $data['group_by'] = $p3;
                        $group_by=$p3;
                    }
                    if ($p4!=null) {
                        $data['status_id'] = $p4;
                        $status_id=$p4;
                    }
                    //end of section

                    if (@$_POST['to_date']) {
                        $data['from_date'] = $_POST['from_date'];
                        $data['to_date'] = $_POST['to_date'];
                        $from_date = $_POST['from_date'];
                        $to_date = $_POST['to_date'];
                    }
                        if (@$_POST['group_by']) {
                            $data['group_by'] = $_POST['group_by'];
                            $group_by=$_POST['group_by'];
                        }
                        if (@$_POST['status_id']) {
                            $data['status_id'] = $_POST['status_id'];
                            $status_id=$_POST['status_id'];
                        }
                    if($data['to_date']!='')
                    {  
                    $this->load->library('pagination');
                    $config = array();
                    
                    $shop_id     = $user->id;
                    $config["base_url"]         = base_url()."sales-report-accounting/tb/".$from_date."/".$to_date."/".$group_by."/".$status_id;
                    $config["total_rows"]       = $this->reports_model->get_sales_report_accounting($shop_id,$from_date,$to_date,$group_by,$status_id);

                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 10;
                    $config["uri_segment"]      = $this->uri->total_segments();
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    $data['page']               = $page = ($p5!=null) ? $p5 : 0;
                    $data['per_page']           = $config["per_page"];
                    $data['sales_report']           = $this->reports_model->get_sales_report_accounting($shop_id,$from_date,$to_date,$group_by,$status_id,$config["per_page"],$page);
                     $data['cat_pro_map']         = $this->master_model->get_cat_pro_map_for_product_list();
                }  
                $data['order_status'] = $this->master_model->get_data1('order_status_master','active','1');
                $data['empty'] = "";
                    $page                       = 'shop/reports/sales_report_accounting/tb';
                    $this->load->view($page, $data);
                    break;
                case 'export_to_excel':
                    $from_date = $p1;
                    $to_date = $p2;

                    $shop_id     = $user->id;
                    $result = $this->reports_model->export_sales_report_accounting($shop_id,$from_date,$to_date,$p3,$p4);

                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->setCellValue('A1', 'Date Start');
                    $sheet->setCellValue('B1', 'Date End');
                    $sheet->setCellValue('C1', 'No. Orders');
                    $sheet->setCellValue('D1', 'No. Products');
                    $sheet->setCellValue('E1', 'Tax');
                    $sheet->setCellValue('F1', 'Total');
                    $count = 2;
                    foreach($result as $row){
                        $sheet->setCellValue('A'.$count, date_format_func($row->min_date));
                        $sheet->setCellValue('B'.$count, date_format_func($row->max_date));
                        $sheet->setCellValue('C'.$count, $row->order_count);
                        $sheet->setCellValue('D'.$count, $row->total_products);
                        $sheet->setCellValue('E'.$count, $row->total_tax);
                        $sheet->setCellValue('F'.$count, $row->total);
                        $count++;
                    }
                    $writer = new Xlsx($spreadsheet);
                    $filename = 'Sales_Report_Accounting';
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="'. $filename .'.xls"');
                    header('Cache-Control: max-age=0');
                    $writer->save('php://output'); // download file
                    break;
                
                default:
                # code...
                break;
        }
    }

    public function product_purchased_report($action=null,$p1=null,$p2=null,$p3=null)
    {
        $data['user']  = $user         = $this->checkShopLogin();
        switch ($action) {
            case null:
                $data['title']          = 'Product Sales Report';
                $data['tb_url']         = base_url().'product-purchased-report/tb';
                $page                   = 'shop/reports/product_purchased_report/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $this->header_and_footer($page, $data);
                break;

                case 'tb':
                  
                    $data['from_date'] = '';
                    $data['to_date'] = '';
                    //below variable section used for models and other places
                    $from_date='null';
                    $to_date='null';
                    //get section intiliazation
                    if($p2!=null)
                    {
                        $data['from_date'] = $p1;
                        $data['to_date'] = $p2;
                        $from_date = $p1;
                        $to_date = $p2;
                    }
                    //end of section
                    if (@$_POST['to_date']) {
                        $data['from_date'] = $_POST['from_date'];
                        $data['to_date'] = $_POST['to_date'];
                        $from_date = $_POST['from_date'];
                        $to_date = $_POST['to_date'];
                    }
                    
                    $this->load->library('pagination');
                    $config = array();
                    
                    $shop_id     = $user->id;
                    $config["base_url"]         = base_url()."product-purchased-report/tb/".$from_date."/".$to_date;
                    $config["total_rows"]       = $this->reports_model->get_product_purchased_report($shop_id,$from_date,$to_date);
                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 10;
                    $config["uri_segment"]      = $this->uri->total_segments();
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    $data['page']               = $page = ($p3!=null) ? $p3 : 0;
                    $data['per_page']           = $config["per_page"];
                    $data['product_purchased_report']           = $this->reports_model->get_product_purchased_report($shop_id,$from_date,$to_date,$config["per_page"],$page);
                    $data['order_status'] = $this->master_model->get_data1('order_status_master','active','1');
                    $data['cat_pro_map']         = $this->master_model->get_cat_pro_map_for_product_list();
                    $page                       = 'shop/reports/product_purchased_report/tb';
                    $this->load->view($page, $data);
                    break;

                    case 'export_to_excel':
                        $shop_id     = $user->id;
                        $result = $this->reports_model->export_product_purchased_report($shop_id,$p1,$p2);
    
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();
                        $sheet->setCellValue('A1', 'Product Name');
                        $sheet->setCellValue('B1', 'Model');
                        $sheet->setCellValue('C1', 'Quantity');
                        $sheet->setCellValue('D1', 'Total');
                        $sheet->setCellValue('E1', 'Parent Category');
                        $sheet->setCellValue('F1', 'Sub Category');
                        $sheet->setCellValue('G1', 'Category');
                        $count = 2;
                        $cat_pro_map =$this->master_model->get_cat_pro_map_for_product_list();
                        foreach($result as $row){
                            $cat_name = [];
                            foreach ($cat_pro_map as $cat) {
                               if($cat->pro_id == $row->prod_id){
                                   $cat_name[] =  '('.$cat->name.') ';
                               } 
                               
                           }
                            $sheet->setCellValue('A'.$count, $row->prod_name);
                            $sheet->setCellValue('B'.$count, $row->product_code);
                            $sheet->setCellValue('C'.$count, $row->quantity.' '.$row->unit_type);
                            $sheet->setCellValue('D'.$count, $row->total);
                            $sheet->setCellValue('E' . $count,@$cat_name[0]);
                            $sheet->setCellValue('F' . $count,@$cat_name[1]);
                            $sheet->setCellValue('G' . $count,@$cat_name[2]);
                            $count++;
                        }
                        $writer = new Xlsx($spreadsheet);
                        $filename = 'Product_Purchased_Report';
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="'. $filename .'.xls"');
                        header('Cache-Control: max-age=0');
                        $writer->save('php://output'); // download file
                        break;
                default:
                # code...
                break;
        }
    }

    public function tax_report($action=null,$p1=null,$p2=null,$p3=null,$p4=null,$p5=null)
    {
        $data['user']  = $user         = $this->checkShopLogin();
        switch ($action) {
            case null:
                $data['title']          = 'Tax Report';
                $data['tb_url']         = base_url().'tax-report/tb';
                $page                   = 'shop/reports/tax_report/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $this->header_and_footer($page, $data);
                break;

                case 'tb':
                
                    $data['from_date'] = '';
                    $data['to_date'] = '';
                    $data['status_id'] = '';
                    //below variable section used for models and other places
                    $from_date='null';
                    $to_date='null';
                    $status_id='null';

                    //get section intiliazation
                    if($p2!=null)
                    {
                        $data['from_date'] = $p1;
                        $data['to_date'] = $p2;
                        $data['status_id'] = $p3;
                        $from_date = $p1;
                        $to_date = $p2;
                        $status_id=$p3;
                    }
                    if ($p3!=null) {
                        $data['status_id'] = $p3;
                        $status_id=$p3;
                    }
                    //end of section

                    if (@$_POST['to_date']) {
                        $data['from_date'] = $_POST['from_date'];
                        $data['to_date'] = $_POST['to_date'];
                        $data['status_id'] = $_POST['status_id'];
                        $from_date = $_POST['from_date'];
                        $to_date = $_POST['to_date'];
                        // $status_id = $_POST['status_id'];
                    }
                    if (@$_POST['status_id']) {
                        $data['status_id'] = $_POST['status_id'];
                        $status_id=$_POST['status_id'];
                    }
                    if($data['to_date']!='')
                    { 
                    $this->load->library('pagination');
                    $config = array();
                    
                    $shop_id     = $user->id;
                    $config["base_url"]         = base_url()."tax-report/tb/".$from_date."/".$to_date."/".$status_id;
                    $config["total_rows"]       = $this->reports_model->get_tax_report($shop_id,$from_date,$to_date,$status_id);
                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 10;
                    $config["uri_segment"]      = $this->uri->total_segments();
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    $data['page']               = $page = ($p4!=null) ? $p4 : 0;
                    $data['per_page']           = $config["per_page"];
                    $data['tax_report']           = $this->reports_model->get_tax_report($shop_id,$from_date,$to_date,$status_id,$config["per_page"],$page);
                    
                }  
                $data['order_status'] = $this->master_model->get_data1('order_status_master','active','1');
                $data['empty'] = "";
                    $page                       = 'shop/reports/tax_report/tb';
                    $this->load->view($page, $data);
                    break;

                    case 'export_to_excel':
                        $from_date = $p1;
                        $to_date = $p2;
    
                        $shop_id     = $user->id;
                        $result = $this->reports_model->export_tax_report($shop_id,$from_date,$to_date,$p3);
    
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();
                        $sheet->setCellValue('A1', 'Date Start');
                        $sheet->setCellValue('B1', 'Date End');
                        $sheet->setCellValue('C1', 'Tax Title(IGST)');
                        $sheet->setCellValue('D1', 'Tax Title(CGST)');
                        $sheet->setCellValue('E1', 'Tax Title(SGST)');
                        $sheet->setCellValue('F1', 'No. Orders');
                        $sheet->setCellValue('G1', 'Total');
                        $count = 2;
                        $igst=0;$cgst = 0;$sgst = 0;$totaligst=0;$totalcgst=0;$totalsgst=0;$totalvalue=0;$totalorders=0;
                        foreach($result as $value){

                            if($value->is_igst == 1)
                            {
                                $igst = $igst + $value->order_tax;
                                $totaligst = $totaligst + $igst;
                            }
                            else if($value->is_igst == 0)
                            {
                                $cgst = $cgst + ($value->order_tax/2);
                                $sgst = $sgst + ($value->order_tax/2);
                                $totalcgst = $totalcgst + $cgst;
                                $totalsgst = $totalsgst + $sgst;
                            }
                            $totalorders = $totalorders + $value->order_count;
                            $totalvalue = $totalvalue + $value->total;

                            $sheet->setCellValue('A'.$count, date_format_func($value->min_date));
                            $sheet->setCellValue('B'.$count, date_format_func($value->max_date));
                            $sheet->setCellValue('C'.$count, round($igst, 2));
                            $sheet->setCellValue('D'.$count, round($cgst, 2));
                            $sheet->setCellValue('E'.$count, round($sgst, 2));
                            $sheet->setCellValue('F'.$count, $value->order_count);
                            $sheet->setCellValue('G'.$count, $value->total);
                            $count++;
                        }
                        
                        $writer = new Xlsx($spreadsheet);
                        $filename = 'Tax_Report';
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="'. $filename .'.xls"');
                        header('Cache-Control: max-age=0');
                        $writer->save('php://output'); // download file
                        break;
                default:
                # code...
                break;
        }
    }

    public function purchase_report($action=null,$p1=null,$p2=null,$p3=null,$p4=null,$p5=null,$p6=null,$p7=null,$p8=null,$p9=null)
    {
        $data['user']  = $user         = $this->checkShopLogin();
        $shop_id     = $user->id;
        switch ($action) {
            case null:
                $data['title']          = 'Purchase Report';
                $data['tb_url']         = base_url().'purchase-report/tb';
                $page                   = 'shop/reports/purchase_report/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $this->header_and_footer($page, $data);
                break;

                case 'tb':
                    $data['from_date'] = '';
                    $data['to_date'] = '';
                    $data['vendor_id'] = 'null';
                    $data['search'] = 'null';
                    $data['brand_id'] = 'null';
                    $data['parent_id'] = 'null';
                    $data['parent_cat_id'] = 'null';
                    $data['child_cat_id'] = 'null';
                    $data['pro_id']='null';
                    $data['prod_id']='null';
                
                    $from_date='null';
                    $to_date='null';
                    $vendor_id='null';
                    $search='null';
                    $brand_id='null';
                    $parent_cat_id='null';
                    $parent_id='null';
                    $child_cat_id='null';
                    $pro_id='';
                
                    //get section intiliazation
                    if($p2!=null)
                    {
                        $data['from_date'] = $p1;
                        $data['to_date'] = $p2;
                        $from_date = $p1;
                        $to_date = $p2;
                    }
                    if ($p3!=null) {
                        $data['vendor_id'] = $p3;
                        $vendor_id=$p3;
                    }
                    if ($p4!=null) {
                        $data['search'] = $p4;
                        $search=$p4;
                    }
                    if ($p5!=null) {
                        $data['brand_id'] = $p5;
                        $brand_id=$p5;
                    }
                    if ($p6!=null) {
                        
                        $data['parent_id'] = $p6;
                        $parent_id=$p6;
                    }
                    if ($p7!=null) {
                        $data['parent_cat_id'] = $p7;
                        $parent_cat_id=$p7;
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $parent_id , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['parent_cat_id']])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                        $data['pro_id']=$pro_id;
                        $data['prod_id']=$pro_id;
                    }
                    if ($p8!=null) {
                        $data['child_cat_id'] = $p8;
                        $child_cat_id=$p8;
                        $data['child_cat'] = $this->db->get_where('products_category',['is_parent' => $p7 , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['child_cat_id']])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                        $data['pro_id']=$pro_id;
                        $data['prod_id']=$pro_id;
                    }
                    //end of section
                
                    
                    //post section intiliazation
                    if (@$_POST['from_date']) {
                       
                        $data['from_date'] = $_POST['from_date'];
                        $data['to_date'] = $_POST['to_date'];
                        $from_date = $_POST['from_date'];
                        $to_date = $_POST['to_date'];
                    }
                     if (@$_POST['vendor_id']) {
                            $data['vendor_id'] = $_POST['vendor_id'];
                            $vendor_id=$_POST['vendor_id'];
                        }
                    if (@$_POST['brand_id']) {
                        $data['brand_id'] = $_POST['brand_id'];
                        $brand_id=$_POST['brand_id'];
                    
                    }
                    if (@$_POST['parent_cat_id']) {
                        $data['parent_cat_id'] = $_POST['parent_cat_id'];
                        $parent_cat_id=$_POST['parent_cat_id'];
                        $data['parent_id'] = $_POST['parent_id'];
                        $parent_id=$_POST['parent_id'];
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $parent_id , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['parent_cat_id']])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                        $data['pro_id']=$pro_id;
                        $data['prod_id']=$pro_id;
                    }
                    if (@$_POST['child_cat_id']) {
                        $data['child_cat_id'] = $_POST['child_cat_id'];
                        $child_cat_id=$_POST['child_cat_id'];
                        $data['child_cat'] = $this->db->get_where('products_category',['is_parent' => $parent_cat_id , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['child_cat_id']])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                        $data['pro_id']=$pro_id;
                        $data['prod_id']=$pro_id;
                    }
                        if (@$_POST['search']) {
                            $data['search'] = $_POST['search'];
                            $search=$_POST['search'];
                       
                        }
                    //end of section    
                
                
                  if($data['to_date']!='')
                  {  
                    $this->load->library('pagination');
                    $config = array();
                   
                    $shop_id     = $user->id;
                    
                    
                    $config["base_url"]         = base_url()."purchase-report/tb/".$from_date."/".$to_date."/".$vendor_id."/".$search."/".$brand_id."/".$parent_id."/".$parent_cat_id."/".$child_cat_id;
                    $config["total_rows"]       = $this->reports_model->getPurchaseReport($shop_id,$from_date,$to_date,$vendor_id,$search,$brand_id,$parent_cat_id,$child_cat_id,$pro_id);
                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 10;
                    $config["uri_segment"]      = $this->uri->total_segments();
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    $data['page']               = $page = ($p9!=null) ? $p9 : 0;
                    $data['per_page']           = $config["per_page"];
                    $data['purchase_report']    = $this->reports_model->getPurchaseReport($shop_id,$from_date,$to_date,$vendor_id,$search,$brand_id,$parent_cat_id,$child_cat_id,$pro_id,$config["per_page"],$page);
                    $data['categories']     = $this->reports_model->view_data('products_category');
                    $data['purchase_result']    = $this->reports_model->getPurchaseResult($shop_id,$from_date,$to_date,$vendor_id,$search,$brand_id,$parent_cat_id,$child_cat_id,$pro_id);
                    
                }
                $data['shop_details'] = $this->shops_model->get_shop_data($shop_id);
                $data['vendor_list']   = $this->master_model->getData('customers',['isActive'=>'1','user_type'=>'supplier','is_deleted'=>'NOT_DELETED']);
                $data['brands'] = $this->master_model->get_data('brand_master','active','1');
                $data['parent_cat'] = $this->master_model->get_data('products_category','is_parent','0');
                $data['empty'] = "";
                if (@$_POST['parent_cat_id'] || @$_POST['child_cat_id']) {
                    if (empty($pro_id)) {
                        $config["total_rows"] = array();
                        $data['stock_report'] = array();
                    }
                }
                $data['cat_pro_map']         = $this->master_model->get_cat_pro_map_for_product_list();
                    $page                       = 'shop/reports/purchase_report/tb';
                    $this->load->view($page, $data);
                    break;
                    case 'export_to_excel':
                        $from_date = $p1;
                        $to_date = $p2;
                        $shop_id     = $user->id;
                        $result = $this->reports_model->export_purchase_report($shop_id,$from_date,$to_date,$p3,$p4,$p5,$p6,$p7,$p8);
                        $categories     = $this->reports_model->view_data('products_category');
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();
                        $sheet->setCellValue('A1', 'S.No.');
                        $sheet->setCellValue('B1', 'Purchase Date');
                        $sheet->setCellValue('C1', 'Category');
                        $sheet->setCellValue('D1', 'Product Category');
                        $sheet->setCellValue('E1', 'Product Code');
                        $sheet->setCellValue('F1', 'Product Name');
                        $sheet->setCellValue('G1', 'Vendor Name');
                        $sheet->setCellValue('H1', 'Vendor Code');
                        $sheet->setCellValue('I1', 'GSTIN');
                        $sheet->setCellValue('J1', 'Address');
                        $sheet->setCellValue('K1', 'Invoice no');
                        $sheet->setCellValue('L1', 'Brand');
                        $sheet->setCellValue('M1', 'Hsn/Sac');
                        $sheet->setCellValue('N1', 'Quantity');
                        $sheet->setCellValue('O1', 'Unit Price without tax');
                        $sheet->setCellValue('P1', 'Unit Price with tax');
                        $sheet->setCellValue('Q1', 'UP/EXUP');
                        $sheet->setCellValue('R1', 'Tax rate');
                        $sheet->setCellValue('S1', 'Igst rate');
                        $sheet->setCellValue('T1', 'Cgst rate');
                        $sheet->setCellValue('U1', 'Sgst rate');
                        $sheet->setCellValue('V1', 'Igst value');
                        $sheet->setCellValue('W1', 'Cgst value');
                        $sheet->setCellValue('X1', 'Sgst value');
                        $sheet->setCellValue('Y1', 'Total without tax');
                        $sheet->setCellValue('Z1', 'Total tax');
                        $sheet->setCellValue('AA1', 'Total value with tax');
                        $count = 2;$i=1;
                        foreach($result as $value){

                            $purchase_rate = $value->purchase_rate;
                            $tax =  $value->tax_value;
                            $inclusive_tax = $purchase_rate - ($purchase_rate * (100/ (100 + $tax)));
                            $unit_price_without_tax =  $purchase_rate - $inclusive_tax;
                            $total_without_tax = $unit_price_without_tax * $value->qty;
                            
                            if($value->is_igst == 1)
                            {
                                $igst = $value->tax_value;
                                $cgst = 0;$sgst = 0;
                                $cgst_val = 0;$sgst_val = 0;
                                $igst_val = $inclusive_tax;
                                $up_exup = 'EXUP';
                            }
                            else if($value->is_igst == 0)
                            {
                                $cgst = $value->tax_value/2;
                                $sgst = $value->tax_value/2;
                                $cgst_val = $inclusive_tax/2;
                                $sgst_val = $inclusive_tax /2;
                                $igst=0;$igst_val=0;
                                $up_exup = 'UP';
                            }
                            $cat_name = "";
                            $subcat_name = "";
                            foreach ($categories as $cat) {
                                if($cat->id == $value->parent_cat_id){
                                    $cat_name = $cat->name;
                                } 
                            }
                            foreach ($categories as $cat) {
                                if($cat->id == $value->sub_cat_id){
                                    $subcat_name = $cat->name;
                                } 
                            }
                            $total_tax = $inclusive_tax*$value->qty;
                            $total_value_with_tax = $total_without_tax + $total_tax;

                            $sheet->setCellValue('A'.$count, $i++);
                            $sheet->setCellValue('B'.$count, date_format_func($value->invoice_date));
                            $sheet->setCellValue('C'.$count, $cat_name);
                            $sheet->setCellValue('D'.$count, $subcat_name);
                            $sheet->setCellValue('E'.$count, $value->product_code);
                            $sheet->setCellValue('F'.$count, $value->product_name);
                            $sheet->setCellValue('G'.$count, $value->vendor_name);
                            $sheet->setCellValue('H'.$count, $value->vendor_code);
                            $sheet->setCellValue('I'.$count, $value->gstin);
                            $sheet->setCellValue('J'.$count, $value->vendor_address);
                            $sheet->setCellValue('K'.$count, $value->invoice_no);
                            $sheet->setCellValue('L'.$count, $value->brand_name);
                            $sheet->setCellValue('M'.$count, $value->sku);
                            $sheet->setCellValue('N'.$count, $value->qty);
                            $sheet->setCellValue('O'.$count, round($unit_price_without_tax,2));
                            $sheet->setCellValue('P'.$count, round($value->purchase_rate,2));
                            $sheet->setCellValue('Q'.$count, $up_exup);
                            $sheet->setCellValue('R'.$count, round($value->tax_value, 2));
                            $sheet->setCellValue('S'.$count, round($igst, 2));
                            $sheet->setCellValue('T'.$count, round($cgst, 2));
                            $sheet->setCellValue('U'.$count, round($sgst, 2));
                            $sheet->setCellValue('V'.$count, round($igst_val, 2));
                            $sheet->setCellValue('W'.$count, round($cgst_val, 2));
                            $sheet->setCellValue('X'.$count, round($sgst_val, 2));
                            $sheet->setCellValue('Y'.$count, round($total_without_tax,2));
                            $sheet->setCellValue('Z'.$count, round($total_tax,2));
                            $sheet->setCellValue('AA'.$count, round($total_value_with_tax,2));
                            $count++;
                        }
                        $writer = new Xlsx($spreadsheet);
                        $filename = 'Purchase_Report';
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="'. $filename .'.xls"');
                        header('Cache-Control: max-age=0');
                        $writer->save('php://output'); // download file
                        break;

                 case 'export_to_excel_new':
                    $from_date = $this->input->post('from_date');
                    $to_date = $this->input->post('to_date');
                    $vendor_id = $this->input->post('vendor_id');
                    $brand_id = $this->input->post('brand_id');
                    $prod_id = $this->input->post('prod_id');
                    $search = $this->input->post('search');
                    $result = $this->reports_model->getPurchaseReportEcel($shop_id,$from_date, $to_date,$vendor_id,$search);
                    $shop_details = $this->shops_model->get_shop_data($shop_id);
                    // Initialize CSV data with column headers
                    $csvData = "Sr.No.,Invoice Date,Invoice No,Supplier Name,Supplier Code,GSTIN,Quantity,Total Without Tax,Total Tax,Total Value With Tax\n";
                
                    $i = 1;
                    foreach ($result as $row) {
                        $items = $this->reports_model->getAllItem($row->purchase_id,$brand_id, $prod_id,$search);
                        $total_tax = $row->total_tax;

                        $total_without_tax = $row->total_amount - $row->total_tax;
                        $total_value_with_tax = $row->total_amount;
                        $currencySymbol = htmlentities($shop_details->currency, ENT_COMPAT, 'UTF-8');
                        // Append row data to CSV data
                        $csvData .= ' ' . $i++ . ' ,' . ' '; // Increment alphabet for each row
                        $csvData .= ' ' . $row->purchase_order_date . ' ,' . ' ';
                        $csvData .= ' ' . $row->purchase_order_no . ' ,' . ' ';
                        $csvData .= ' "' . $row->vendor_name . '(' . $row->mobile . ')" ,' . ' ';
                        $csvData .= ' ' . $row->vendor_code . ' ,' . ' ';
                        $csvData .= ' ' . $row->gstin . ' ,' . ' ';
                        $csvData .= ' ' . $row->total_qty . ' ,' . ' ';
                        $csvData .= ' ' . $total_without_tax . ' ,' . ' ';
                        $csvData .= ' ' . $total_tax . ' ,' . ' ';
                        $csvData .= ' ' . $total_value_with_tax . ' ' . "\n";                        
                          // Append item rows
                          $csvData .= "\n";
                          foreach ($items as $item) {
                            // Check if item_props_value is a string
                                if (is_string($item->item_props_value)) {
                                    // Extract text content from HTML using regular expressions
                                    preg_match_all('/<span class=\'text-danger\'>(.*?)<\/span>/', $item->item_props_value, $matches);
                                    $itemPropsValue = implode(' ', $matches[1]);
                                } else {
                                    $itemPropsValue = '';
                                }
                              $csvData .= ' => ,' . ' ';
                              $csvData .= ' ' . $item->name . ' ,' . ' ';
                              $csvData .= $itemPropsValue . ' ,';
                              $csvData .= ' ' . $item->product_code . ' ,' . ' ';
                              $csvData .= ' ' . 'Qty - ' . $item->qty . ' ,' . ' ';
                              $csvData .= ' ' . 'Purchase Rate - ' . $item->unit_cost . ' ,' . ' ';
                              $csvData .= ' ' . 'Tax - ' . $item->total_tax . ' ,' . ' ';
                              $csvData .= ' ' . 'Discount - ' . $item->discount_value . ' ,' . ' ';
                              $csvData .= ' ' . 'Landing Cost - ' . $item->landing_cost . ' ,' . ' ';
                              $csvData .= ' ' . 'Total - ' . $item->total . ' ' . "\n";
                          }
                          $csvData .= "\n";
                          
                    }
                 
                    // Set headers
                    header('Content-Type: text/csv');
                    header('Content-Disposition: attachment;filename="Purchase_Report.csv"'); // Set the filename here
                    header('Cache-Control: max-age=0');
                
                    // Output CSV data
                    echo $csvData;
                 break;   
                default:
                # code...
                break;
        }
    }
    public function sales_report($action=null,$p1=null,$p2=null,$p3=null,$p4=null,$p5=null,$p6=null,$p7=null,$p8=null,$p9=null,$p10=null,$p11=null,$p12=null,$p13=null)
    {
        $data['user']  = $user         = $this->checkShopLogin();
        switch ($action) {
            case null:
                $data['title']          = 'Sales Report';
                $data['tb_url']         = base_url().'sales-report/tb';
                $page                   = 'shop/reports/sales_report/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                $this->header_and_footer($page, $data);
                break;

                case 'tb':
                    $data['from_date'] = '';
                    $data['to_date'] = '';
                    $data['pmid'] = 'null';
                    $data['search'] = 'null';
                    $data['status_id'] = 'null';
                    $data['brand_id'] = 'null';
                    $data['parent_id'] = 'null';
                    $data['parent_cat_id'] = 'null';
                    $data['child_cat_id'] = 'null';
                    $data['product_id'] = 'null';
                    $data['subscription'] = 'null';
                    $data['plan_type_id'] = 'null';
                    $pro_id = array();
                    //below variable section used for models and other places
                    $from_date='null';
                    $to_date='null';
                    $pm_id='null';
                    $search='null';
                    $status_id='null';
                    $brand_id='null';
                    $parent_cat_id='null';
                    $parent_id='null';
                    $child_cat_id='null';
                    $product_id='null';
                    $subscription='null';
                    $plan_type_id='null';

                    //get section intiliazation
                    if($p2!=null)
                    {
                        $data['from_date'] = $p1;
                        $data['to_date'] = $p2;
                        $from_date = $p1;
                        $to_date = $p2;
                    }
                    if ($p3!=null) {
                        $data['pmid'] = $p3;
                        $pm_id=$p3;
                    }
                    if ($p4!=null) {
                        $data['search'] = $p4;
                        $search=$p4;
                    }
                    if ($p5!=null) {
                        $data['status_id'] = $p5;
                        $status_id=$p5;
                    }
                    if ($p6!=null) {
                        $data['brand_id'] = $p6;
                        $brand_id=$p6;
                    }
                    if ($p7!=null) {
                        
                        $data['parent_id'] = $p7;
                        $parent_id=$p7;
                    }
                    if ($p8!=null) {
                        $data['parent_cat_id'] = $p8;
                        $parent_cat_id=$p8;
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $parent_id , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['parent_cat_id']])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                    }
                    if ($p9!=null) {
                        $data['child_cat_id'] = $p9;
                        $child_cat_id=$p9;
                        $data['child_cat'] = $this->db->get_where('products_category',['is_parent' => $p8 , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['child_cat_id']])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                    }
                    if ($p10!=null) {
                        $data['product_id'] = $p10;
                        $product_id=$p10;
                    }
                    if ($p11!=null) {
                        $data['subscription'] = $p11;
                        $subscription=$p11;
                    }
                    if ($p12!=null) {
                        $data['plan_type_id'] = $p12;
                        $plan_type_id=$p12;
                    }
                   
                    //end of section

                     //post section intiliazation
                     if (@$_POST['from_date']) {
                       
                        $data['from_date'] = $_POST['from_date'];
                        $data['to_date'] = $_POST['to_date'];
                        $from_date = $_POST['from_date'];
                        $to_date = $_POST['to_date'];
                        
                    }
                    if (@$_POST['pm_id']) {
                        $data['pmid'] = $_POST['pm_id'];
                        $pm_id=$_POST['pm_id'];
                    }
                    if (@$_POST['search']) {
                        $data['search'] = $_POST['search'];
                        $search=$_POST['search'];
                   
                    }
                    if (@$_POST['status_id']) {
                        $data['status_id'] = $_POST['status_id'];
                        $status_id=$_POST['status_id'];
                   
                    }
                    if (@$_POST['brand_id']) {
                        $data['brand_id'] = $_POST['brand_id'];
                        $brand_id=$_POST['brand_id'];
                   
                    }
                    if (@$_POST['parent_cat_id']) {
                        $data['parent_cat_id'] = $_POST['parent_cat_id'];
                        $parent_cat_id=$_POST['parent_cat_id'];
                        $data['parent_id'] = $_POST['parent_id'];
                        $parent_id=$_POST['parent_id'];
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $parent_id , 'is_deleted' => 'NOT_DELETED'])->result();
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['parent_cat_id']])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                    }

                    if (@$_POST['child_cat_id']) {
                        $data['child_cat_id'] = $_POST['child_cat_id'];
                        $child_cat_id=$_POST['child_cat_id'];
                        $data['child_cat'] = $this->db->get_where('products_category',['is_parent' => $parent_cat_id , 'is_deleted' => 'NOT_DELETED'])->result();

                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['child_cat_id']])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                    }
                    if (@$_POST['product_id']) {
                        $data['product_id'] = $_POST['product_id'];
                        $product_id = $_POST['product_id'];
                    }
                    if (@$_POST['subscription']) {
                        $data['subscription'] = $_POST['subscription'];
                        $subscription = $_POST['subscription'];
                    }
                    if (@$_POST['plan_type_id']) {
                        $data['plan_type_id'] = $_POST['plan_type_id'];
                        $plan_type_id = $_POST['plan_type_id'];
                    }
                if($data['to_date']!='')
                {  
                    $this->load->library('pagination');
                    $config = array();
                    
                    $shop_id     = $user->id;
                    $config["base_url"]         = base_url()."sales-report/tb/".$from_date."/".$to_date."/".$pm_id."/".$search."/".$status_id."/".$brand_id."/".$parent_id."/".$parent_cat_id."/".$child_cat_id."/".$product_id."/".$subscription."/".$plan_type_id;
                    $config["total_rows"]       = $this->reports_model->get_sales_report($pro_id,$shop_id,$from_date,$to_date,$pm_id,$search,$status_id,$brand_id,$parent_cat_id,$child_cat_id,$product_id,$subscription,$plan_type_id);

                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 10;
                    $config["uri_segment"]      = $this->uri->total_segments();
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    
        
                    $data['page']               = $page = ($p13!=null) ? $p13 : 0;
                    $data['per_page']           = $config["per_page"];
                    
                    $data['sales_report']           = $this->reports_model->get_sales_report($pro_id,$shop_id,$from_date,$to_date,$pm_id,$search,$status_id,$brand_id,$parent_cat_id,$child_cat_id,$product_id,$subscription,$plan_type_id,$config["per_page"],$page);
                    //$data['categories']     = $this->reports_model->view_data('products_category');
                    $data['sales_result'] = $this->reports_model->calculate_sales_report($pro_id,$shop_id,$from_date,$to_date,$pm_id,$search,$status_id,$brand_id,$parent_cat_id,$child_cat_id,$product_id,$subscription,$plan_type_id);
                    $data['cat_pro_map']         = $this->master_model->get_cat_pro_map_for_product_list();
                }  
                $data['order_status'] = $this->master_model->get_data1('order_status_master','active','1');
                $data['brands'] = $this->master_model->get_data('brand_master','active','1');
                $data['payment_mode'] = $this->master_model->view_data1('payment_mode_master');
                $data['empty'] = "";
                $data['parent_cat'] = $this->master_model->get_data('products_category','is_parent','0');
                $data['plan_types'] = $this->reports_model->get_data1('subscriptions_plan_types','active','1');
                // $data['child_cat'] = $this->db->get_where('products_category',['is_parent' => $parent_cat_id , 'is_deleted' => 'NOT_DELETED'])->result();
                if (@$_POST['parent_cat_id'] || @$_POST['child_cat_id']) {
                    if (empty($pro_id)) {
                        $config["total_rows"] = array();
                        $data['sales_report'] = array();
                    }
                }
                    $page                       = 'shop/reports/sales_report/tb';
                    $this->load->view($page, $data);
                    break;
                    case 'export_to_excel':
              
                        $from_date = $p1;
                        $to_date = $p2;
    
                        $shop_id     = $user->id;
                        $result = $this->reports_model->export_sales_report($shop_id,$from_date,$to_date,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12);
                        $categories     = $this->reports_model->view_data('products_category');
                        $cat_pro_map = $this->master_model->get_cat_pro_map_for_product_list();
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();
                        $sheet->setCellValue('A1', 'S.No.');
                        $sheet->setCellValue('B1', 'Order date');
                        $sheet->setCellValue('C1', 'Product code');
                        $sheet->setCellValue('D1', 'Product name');
                        $sheet->setCellValue('E1', 'Customer name');
                        $sheet->setCellValue('F1', 'Customer number');
                        $sheet->setCellValue('G1', 'Invoice No.');
                        $sheet->setCellValue('H1', 'Brand');
                        $sheet->setCellValue('I1', 'Hsn/Sac');
                        $sheet->setCellValue('J1', 'Qty');
                        $sheet->setCellValue('K1', 'Unit Price without tax');
                        $sheet->setCellValue('L1', 'Unit Price with tax');
                        $sheet->setCellValue('M1', 'UP/EXUP');
                        $sheet->setCellValue('N1', 'Tax rate');
                        $sheet->setCellValue('O1', 'Igst rate');
                        $sheet->setCellValue('P1', 'Cgst rate');
                        $sheet->setCellValue('Q1', 'Sgst rate');
                        $sheet->setCellValue('R1', 'Igst value');
                        $sheet->setCellValue('S1', 'Cgst value');
                        $sheet->setCellValue('T1', 'Sgst value');
                        $sheet->setCellValue('U1', 'Total without tax');
                        $sheet->setCellValue('V1', 'Total tax');
                        $sheet->setCellValue('W1', 'Total value with tax');
                        $sheet->setCellValue('X1', 'Payment Method');
                        $sheet->setCellValue('Y1', 'Bank Name');
                        $sheet->setCellValue('Z1', 'Razorpay Order ID');
                        $sheet->setCellValue('AA1', 'Parent Category');
                        $sheet->setCellValue('AB1', 'Sub Category');
                        $sheet->setCellValue('AC1', 'Category');
                        $count = 2;$i=1;
                        foreach($result as $value){
    
                            $sale_rate = $value->price_per_unit;
                            $tax =  $value->tax_value;
                            $inclusive_tax = $sale_rate - ($sale_rate * (100/ (100 + $tax)));
    
                            $unit_price_without_tax =  $sale_rate - $inclusive_tax;
                            $total_without_tax = $unit_price_without_tax * $value->qty;
                            
    
                            if($value->is_igst == 1)
                            {
                                $igst = $value->tax_value;
                                $cgst = 0;$sgst = 0;
                                $cgst_val = 0;$sgst_val = 0;
                                $igst_val = $inclusive_tax;
                            }
                            else if($value->is_igst == 0)
                            {
                                $cgst = $value->tax_value/2;
                                $sgst =$value->tax_value/2;
                                $cgst_val = $inclusive_tax/2;
                                $sgst_val = $inclusive_tax /2;
                                $igst=0;$igst_val=0;
                            }
    
                            $total_tax = $inclusive_tax*$value->qty;
                            $total_value_with_tax = $total_without_tax + $total_tax;
                            // print_r($cgst_val);
                            if($value->payment_method == 'cod')
                            {
                                $payment_method = 'COD';
                            }
                            else
                            {
                                $payment_method = 'Razorpay';
                            }
                             $cat_name = [];
                            foreach ($cat_pro_map as $cat) {
                               if($cat->pro_id == $value->prod_id){
                                   $cat_name[] =  '('.$cat->name.') ';
                               } 
                               
                           }
                            $sheet->setCellValue('A'.$count, $i++);
                            $sheet->setCellValue('B'.$count, date_format_func($value->order_date));
                            $sheet->setCellValue('C'.$count, $value->product_code);
                            $sheet->setCellValue('D'.$count, $value->product_name);
                            $sheet->setCellValue('E'.$count, $value->fname.' '.$value->mname.' '.$value->lname);
                            $sheet->setCellValue('F'.$count, $value->mobile);
                            $sheet->setCellValue('G'.$count, $value->orderid);
                            $sheet->setCellValue('H'.$count, $value->brand_name);
                            $sheet->setCellValue('I'.$count, $value->sku);
                            $sheet->setCellValue('J'.$count, $value->qty);
                            $sheet->setCellValue('K'.$count, round($unit_price_without_tax,2));
                            $sheet->setCellValue('L'.$count, round($value->price_per_unit,2));
                            $sheet->setCellValue('M'.$count, 'UP');
                            $sheet->setCellValue('N'.$count, round($value->tax_value, 2));
                            $sheet->setCellValue('O'.$count, round($igst, 2));
                            $sheet->setCellValue('P'.$count, round($cgst, 2));
                            $sheet->setCellValue('Q'.$count, round($sgst, 2));
                            $sheet->setCellValue('R'.$count, round($igst_val, 2));
                            $sheet->setCellValue('S'.$count, round($cgst_val, 2));
                            $sheet->setCellValue('T'.$count, round($sgst_val, 2));
                            $sheet->setCellValue('U'.$count, round($total_without_tax,2));
                            $sheet->setCellValue('V'.$count, round($total_tax,2));
                            $sheet->setCellValue('W'.$count, round($total_value_with_tax,2));
                            $sheet->setCellValue('X'.$count, $payment_method);
                            $sheet->setCellValue('Y'.$count, $value->bank_name);
                            $sheet->setCellValue('Z'.$count, $value->razorpay_order_id);
                            $sheet->setCellValue('AA'.$count, @$cat_name[0]);
                            $sheet->setCellValue('AB'.$count, @$cat_name[1]);
                            $sheet->setCellValue('AC'.$count, @$cat_name[2]);
                            $count++;
                        }
                        
                        // die();
                        $writer = new Xlsx($spreadsheet);
                        $filename = 'Sales_Report';
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="'. $filename .'.xls"');
                        header('Cache-Control: max-age=0');
                        $writer->save('php://output'); // download file
                        break;
                
                default:
                # code...
                break;
        }
    }

    //Fetch Product category
    public function fetch_category()
    {
        if($this->input->post('parent_id'))
        {
            $pid= $this->input->post('parent_id');
            $this->master_model->fetch_category($pid);
        }
    }
     //Fetch Product category
     public function fetch_cat()
     {
         if($this->input->post('parent_cat_id'))
         {
             $parent_cat_id= $this->input->post('parent_cat_id');
             $this->master_model->fetch_category($parent_cat_id);
         }
     }
     public function fetch_sub_categories()
     {
         if($this->input->post('parent_id'))
         {
             $parent_id= $this->input->post('parent_id');
             $this->master_model->fetch_sub_categories($parent_id);
         }
     }

     public function fill_products()
     {
         $data = $this->master_model->fill_products();
 
         // echo _prx($data);
         if (@$data) {
             echo "<option value=''>Select Product</option>";
             foreach($data as $val)
             {
                 echo "<option value='" . $val->id . "'>" . $val->name . "</option>";
             }
         }
         else{
             echo "<option value=''>Select Category/Vendor First</option>";
         }
     }


     public function pos_sales_report($action = null, $p1 = null, $p2 = null, $p3 = null, $p4 = null, $p5 = null, $p6 = null, $p7 = null, $p8 = null, $p9 = null, $p10 = null, $p11 = null, $p12 = null, $p13 = null)
     {
        $data['user']  = $user         = $this->checkShopLogin();
          $shop_id     = $user->id;
         $uri = $this->uri->segment_array();
         switch ($action) {
             case null:
                 $data['title']          = 'Pos-Sales Report';
                 $data['tb_url']         = base_url() . 'pos-sale-register/tb';
                 $data['parent_cat'] = $this->master_model->get_data('products_category', 'is_parent', '0');
                 $data['menu_url'] = $this->uri->segment(1);
                 $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                 $data['brands'] = $this->master_model->get_brands($shop_id);
                 $data['customers'] = $this->master_model->get_customers($shop_id);
                 $data['order_status'] = $this->master_model->get_data1('order_status_master', 'active', '1');
                 $page                   = 'shop/reports/pos_sales_report/index';
                 $this->header_and_footer($page, $data);
                 break;
 
             case 'tb':
 
                $p = $this->input->post();
                $data['from_date'] = !empty($p['from_date']) ? $p['from_date'] : '';  
                $data['to_date'] = !empty($p['to_date']) ? $p['to_date'] : '';  
                $data['customer_id'] = !empty($p['customer_id']) ? $p['customer_id'] : 'null';  
                $data['parent_id'] = !empty($p['parent_id']) ? $p['parent_id'] : 'null';  
                $data['parent_cat_id'] = !empty($p['parent_cat_id']) ? $p['parent_cat_id'] : 'null';  
                $data['sub_cat_id'] = !empty($p['sub_cat_id']) ? $p['sub_cat_id'] : 'null';  
                $data['product_id'] = !empty($p['product_id']) ? $p['product_id'] : 'null';  
                $data['brand_id'] = !empty($p['brand_id']) ? $p['brand_id'] : 'null';  
                $data['status_id'] = !empty($p['status_id']) ? $p['status_id'] : 'null';  
                $data['tb_search'] = !empty($p['search']) ? $p['search'] : ''; 
                if (@$_POST['parent_cat_id']) {
                    $data['parent_cat_id'] = $_POST['parent_cat_id'];
                    $parent_cat_id=$_POST['parent_cat_id'];
                    $data['parent_id'] = $_POST['parent_id'];
                    $parent_id=$_POST['parent_id'];
                    $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $parent_id , 'is_deleted' => 'NOT_DELETED'])->result();
                    $pro_id = array();
                    $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['parent_cat_id']])->result();
                    foreach($get_proid as $row){
                        $pro_id[] = $row->pro_id;
                    }
                }

                if (@$_POST['child_cat_id']) {
                    $data['child_cat_id'] = $_POST['child_cat_id'];
                    $child_cat_id=$_POST['child_cat_id'];
                    $data['child_cat'] = $this->db->get_where('products_category',['is_parent' => $parent_cat_id , 'is_deleted' => 'NOT_DELETED'])->result();

                    $pro_id = array();
                    $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['child_cat_id']])->result();
                    foreach($get_proid as $row){
                        $pro_id[] = $row->pro_id;
                    }
                }
                     $this->load->library('pagination');
                     $config = array();
 
                     $shop_id    = $user->id;
                     $config["base_url"]         = base_url() . "pos-sale-register  /tb/";
                     $config["total_rows"]       = $this->reports_model->get_pos_sales_report($shop_id);
 
                     $data['total_rows']         = $config["total_rows"];
                     $config["per_page"]         = 10;
                     $config["uri_segment"]      = $this->uri->total_segments();
                     $config['attributes']       = array('class' => 'pag-link');
                     $config['full_tag_open']    = "<div class='pag'>";
                     $config['full_tag_close']   = "</div>";
                     $config['first_link']       = '&lt;&lt;';
                     $config['last_link']        = '&gt;&gt;';
                     $this->pagination->initialize($config);
                     $data["links"]              = $this->pagination->create_links();
 
 
                     $data['page']               = $page = ($p1 != null) ? $p1 : 0;
                     $data['per_page']           = $config["per_page"];
 
                     $data['sales_report']           = $this->reports_model->get_pos_sales_report($shop_id,$config["per_page"], $page);
                     $data['categories']     = $this->reports_model->view_data('products_category');
                     $data['sales_result'] = $this->reports_model->calculate_pos_sales_report($shop_id,);
                     $data['cat_pro_map']         = $this->master_model->get_cat_pro_map_for_product_list();
                    //  $data['tb_url']         = base_url() . 'pos-sale-register/tb';
                     $data['parent_cat'] = $this->master_model->get_data('products_category', 'is_parent', '0');
                     $data['brands'] = $this->master_model->get_brands($shop_id);
                     $data['customers'] = $this->master_model->get_customers($shop_id);
                     $data['order_status'] = $this->master_model->get_data1('order_status_master', 'active', '1');
                 $page                       = 'shop/reports/pos_sales_report/tb';
                 $this->load->view($page, $data);
                 break;
             case 'export_to_excel':
 
                 $filter['from_date']      = $p1;
                 $filter['to_date']        = $p2;
                 $filter['customer_id']    = $p3;
                 $filter['parent_id']      = $p4;
                 $filter['parent_cat_id']  = $p5;
                 $filter['sub_cat_id']     = $p6;
                 $filter['product_id']     = $p7;
                 $filter['brand_id']       = $p8;
                 $filter['status_id']      = $p9;
                 $filter['tb_search']      = $p10;
                 $filter['shop_id']         = $user->id;
 
                 $result = $this->reports_model->export_pos_sales_report($filter);
                 $cat_pro_map = $this->master_model->get_cat_pro_map_for_product_list();
                 // echo _prx($result);
 
                 // die();
 
 
 
 
                 $categories     = $this->reports_model->view_data('products_category');
 
                 $spreadsheet = new Spreadsheet();
                 $sheet = $spreadsheet->getActiveSheet();
                 $sheet->setCellValue('A1', 'S.No.');
                 $sheet->setCellValue('B1', 'Invoice Date');
                 $sheet->setCellValue('C1', 'Invoice No.');
                 $sheet->setCellValue('D1', 'Product Code');
                 $sheet->setCellValue('E1', 'Brand');
                 $sheet->setCellValue('F1', 'Product Name');
                 $sheet->setCellValue('G1', 'Hsn/Sac');
                 $sheet->setCellValue('H1', 'Customer name');
                 $sheet->setCellValue('I1', 'Customer Code');
                 $sheet->setCellValue('J1', 'Qty');
                 $sheet->setCellValue('K1', 'Mrp');
                 $sheet->setCellValue('L1', 'Sche');
                 $sheet->setCellValue('M1', 'Ad Sche');
                 $sheet->setCellValue('N1', 'Free');
                 $sheet->setCellValue('O1', 'Unit Price without tax');
                 $sheet->setCellValue('P1', 'Unit Price with tax');
                 $sheet->setCellValue('Q1', 'UP/EXUP');
                 $sheet->setCellValue('R1', 'Tax rate');
                 $sheet->setCellValue('S1', 'Igst rate');
                 $sheet->setCellValue('T1', 'Cgst rate');
                 $sheet->setCellValue('U1', 'Sgst rate');
                 $sheet->setCellValue('V1', 'Igst value');
                 $sheet->setCellValue('W1', 'Cgst value');
                 $sheet->setCellValue('X1', 'Sgst value');
                 $sheet->setCellValue('Y1', 'Total without tax');
                 $sheet->setCellValue('Z1', 'Total tax');
                 $sheet->setCellValue('AA1', 'Total value with tax');
                 $sheet->setCellValue('AB1', 'Parent Category');
                 $sheet->setCellValue('AC1', 'Sub Category');
                 $sheet->setCellValue('AD1', 'Category');
                 $sheet->setCellValue('AE1', 'Customer number');
                 $count = 2;
                 $i = 1;
                 foreach ($result as $value) {
 
                     $sale_rate = $value->mrp;
                     $tax =  $value->tax_value;
                     $inclusive_tax = $sale_rate - ($sale_rate * (100 / (100 + $tax)));
 
                     $unit_price_without_tax =  $sale_rate - $inclusive_tax;
                     $total_without_tax = $unit_price_without_tax * $value->qty;
 
 
                     if ($value->is_igst == 1) {
                         $igst = $value->tax_value;
                         $cgst = 0;
                         $sgst = 0;
                         $cgst_val = 0;
                         $sgst_val = 0;
                         $igst_val = $inclusive_tax;
                     } else if ($value->is_igst == 0) {
                         $cgst = $value->tax_value / 2;
                         $sgst = $value->tax_value / 2;
                         $cgst_val = $inclusive_tax / 2;
                         $sgst_val = $inclusive_tax / 2;
                         $igst = 0;
                         $igst_val = 0;
                     }
 
                     $total_tax = $inclusive_tax * $value->qty;
                     $total_value_with_tax = $total_without_tax + $total_tax;
                     // print_r($cgst_val);
                     if ($value->payment_method == 'cod') {
                         $payment_method = 'COD';
                     } else {
                         $payment_method = 'Razorpay';
                     }
                     $cat_name = [];
                     foreach ($cat_pro_map as $cat) {
                        if($cat->pro_id == $value->prod_id){
                            $cat_name[] =  '('.$cat->name.') ';
                        } 
                        
                    }
                     $sheet->setCellValue('A' . $count, $i++);
                     $sheet->setCellValue('B' . $count, date_format_func($value->order_date));
                     $sheet->setCellValue('C' . $count, $value->orderid);
                     $sheet->setCellValue('D' . $count, $value->product_code);
                     $sheet->setCellValue('E' . $count, $value->brand_name);
                     $sheet->setCellValue('F' . $count, $value->product_name);
                     $sheet->setCellValue('G' . $count, $value->sku);
                     $sheet->setCellValue('H' . $count, $value->fname.' '.$value->lname);
                     $sheet->setCellValue('I' . $count, $value->vendor_code);
                     $sheet->setCellValue('J' . $count, $value->qty);
                     $sheet->setCellValue('K' . $count, $value->price_per_unit);
 
                     $offer_type1_1 = ($value->discount_type==0) ? '%' : '';
                     $offer_type1_2 = ($value->discount_type==1) ? '₹' : '';
                     $sheet->setCellValue('L' . $count, $offer_type1_2.' '.$value->offer_applied.' '.$offer_type1_1);
 
                     $offer_type2_1 = ($value->discount_type2==0) ? '%' : '';
                     $offer_type2_2 = ($value->discount_type2==1) ? '₹' : '';
                     $sheet->setCellValue('M' . $count, $offer_type2_2.' '.$value->offer_applied2.' '.$offer_type2_1);
 
                     $sheet->setCellValue('N' . $count, $value->free);
                     $sheet->setCellValue('O' . $count, round($unit_price_without_tax, 2));
                     $sheet->setCellValue('P' . $count, round($value->price_per_unit, 2));
                     $sheet->setCellValue('Q' . $count, 'UP');
                     $sheet->setCellValue('R' . $count, round($value->tax_value, 2));
                     $sheet->setCellValue('S' . $count, round($igst, 2));
                     $sheet->setCellValue('T' . $count, round($cgst, 2));
                     $sheet->setCellValue('U' . $count, round($sgst, 2));
                     $sheet->setCellValue('V' . $count, round($igst_val, 2));
                     $sheet->setCellValue('W' . $count, round($cgst_val, 2));
                     $sheet->setCellValue('X' . $count, round($sgst_val, 2));
                     $sheet->setCellValue('Y' . $count, round($total_without_tax, 2));
                     $sheet->setCellValue('Z' . $count, round($total_tax, 2));
                     $sheet->setCellValue('AA' . $count, round($total_value_with_tax, 2));
                     $sheet->setCellValue('AB' . $count, @$cat_name[0]);
                     $sheet->setCellValue('AC' . $count, @$cat_name[1]);
                     $sheet->setCellValue('AD' . $count, @$cat_name[2]);
                     $sheet->setCellValue('AE' . $count, $value->mobile);
                     $count++;
                 }
 
                 // die();
                 $writer = new Xlsx($spreadsheet);
                 $filename = 'Pos_Sales_Report';
                 header('Content-Type: application/vnd.ms-excel');
                 header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                 header('Cache-Control: max-age=0');
                 $writer->save('php://output'); // download file
                 break;
 
             default:
                 # code...
                 break;
         }
     }

     public function date_wise_product_stock_report($action=null,$p1=null,$p2=null)
     {
        $data['user']  = $user         = $this->checkShopLogin();
        $shop_id     = $user->id;
         switch($action){
             case null:
                 $data['title']          = 'Product Stoct Report';
                 $data['tb_url']         = base_url() . 'date-wise-stock-report/tb';
                 $data['brands'] = $this->master_model->get_brands($shop_id);
                 $data['vendors'] = $this->master_model->get_vendors($shop_id);
                 $page                   = 'shop/reports/date_wise_product_stock_report/index';
                 $data['menu_url'] = $this->uri->segment(1);
                 $data['breadcrumb']    = generate_breadcrumb($data['menu_url']); 
                 $this->header_and_footer($page, $data);
                 break;
 
             case 'tb':
 
                 $products = $this->master_model->fill_products_new();
                 $rows = $this->reports_model->date_wise_product_stock_report($products);
               
                 // echo _prx($rows);
                 // die();
 
                 $this->load->library('table');
                 $template = array(
                         'table_open'  => '<div class="table-responsive"><table class="table table-bordered table-striped table-hover table-sm products-wise-stock-report">',
                         'table_close'  => '</table></div>',
                     );
 
                 $this->table->set_template($template);
                     
                 
                 if (@$rows) {
                     $this->table->set_heading(array_keys($rows[0]));
                     foreach ($rows as $key => $value) {
                         $this->table->add_row(array_values($value));
                     }
                 }
                 else{
                     $cell = array('data' => 'Data Not Found!', 'class' => 'text-center text-danger', 'colspan' => 5);
                     $this->table->add_row($cell);
                 }
                 
 
                 echo $this->table->generate();
                 
 
                 break;
         }
     }
 


}