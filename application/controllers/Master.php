<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

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
        $data['role_id'] = $admin_role_id;
        $data['dashboard'] = $this->admin_model->get_row_data('admin','id',$user_id);
        $data['admin_menus'] = $this->admin_model->get_role_menu_data($admin_role_id);
        $this->load->view('admin/includes/header',$data);
        $this->load->view($page);
        $this->load->view('admin/includes/footer');
    }

    public function index()
    {
        $data['user']  = $user         = checkLogin();
        $menu_id = $this->uri->segment(2);
        $data['menu_id'] = $menu_id;
        $role_id = $user->role_id;
        $data['sub_menus'] = $this->admin_model->get_submenu_data($menu_id,$role_id);
        $data['title'] = 'Master';
        $page = 'admin/master/master_data';
        $this->header_and_footer($page, $data);
    }



    public function remote($type,$id=null,$column='name')
    {
        if ($type=='category') {
            $tb = 'products_category';
        }
        elseif ($type=='tax') {
            $tb ='tax_slabs';
        }
        elseif ($type=='products') {
            $tb ='products_subcategory';
        }
        elseif ($type=='products') {
            $tb ='products_subcategory';
        }
        elseif ($type=='brand') {
            $tb ='brand_master';
        }
        elseif ($type=='vendor') {
            $tb ='supplier_details';
        }
        else{
            
        }
        $this->db->where($column,$_GET[$column]);
        if($id!=NULL){
            $this->db->where('id != ',$id)->where('is_deleted','NOT_DELETED');
        }
        $count=$this->db->count_all_results($tb);
        if($count>0)
        {
            echo "false";
        }
        else
        {
            echo "true";
        }        
    }
    public function society_remote($type,$id=null,$column='name')
    {
        if ($type=='societyr') {
            $tb = 'society_master';
        }
        else{

        }
        $this->db->where($column,$_GET[$column]);
        if($id!=NULL){
            $this->db->where('socity_id != ',$id);
        }
        $count=$this->db->count_all_results($tb);
        if($count>0)
        {
            echo "false";
        }
        else
        {
            echo "true";
        }        
    }

    public function delete_category()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        if ($this->master_model->delete_data('products_category',$id)) {
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    } 

    
    //ingredient
    
    public function ingredient($action=null,$p1=null,$p2=null,$p3=null,$p4=null)
    {
        $data['user']  = $user         = checkLogin();
        switch($action)
        {
            case null:
                $data['menu_id'] = $this->uri->segment(2);
                $data['title']          = 'Ingredients';
                $data['tb_url']         = base_url().'ingredient/tb';
                $data['new_url']        = base_url().'ingredient/create';
                $page                   = 'admin/master/ingredients/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
                $this->header_and_footer($page, $data);
                break;
            case 'tb':
                $data['search'] = '';
                $search='null';
                if (@$_POST['search']) {
                        $data['search'] = $_POST['search'];
                        $search=$_POST['search'];
                   
                }
                 $this->load->library('pagination');
                    $config = array();
                    $config["base_url"]         = base_url()."ingredient/tb/".$search;
                    $config["total_rows"]       = $this->master_model->ingredients($search);
                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 20;
                    $config["uri_segment"]      = 7;
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    $data['page']               = $page = ($p4!=null) ? $p4 : 0; 
                    $data['per_page']           = $config["per_page"];
                    $data['ingredients']           = $this->master_model->ingredients($search,$config["per_page"],$page);
                    $data['update_url']         = base_url().'ingredient/create/';
                    $page                       = 'admin/master/ingredients/tb';
                    
//                    if (@$_POST['cat_id'] || @$_POST['child_cat_id']) {
//                        if (empty($pro_id)) {
//                            $config["total_rows"] = array();
//                            $data['ingredients'] = array();
//                        }
//                    }
                    $this->load->view($page, $data);
                    break;
                case 'create':
                    $data['action_url']         = base_url().'ingredient/save';
                    $page                       = 'admin/master/ingredients/create';
                    if ($p1!=null) {
                        $data['action_url']     = base_url().'ingredient/save/'.$p1;
                        $page                   = 'admin/master/ingredients/update';
                        $data['value']          = $this->master_model->get_data1('product_ingredients','id',$p1);
                        // print_r($data['cat_pro_map']); die;
                    }
                   
                    // print_r($data['parent_cat']); die;
                    // $data['categories']         = $this->master_model->view_data('products_category');
                    $data['form_id']= uniqid();
                    $this->load->view($page, $data);
                break;
                case 'save':
                    $id = $p1;
                    $return['res'] = 'error';
                    $return['msg'] = 'Not Saved!';
    
                    if ($this->input->server('REQUEST_METHOD')=='POST') { 
                      
                         if ($id!=null) {
                           
                            $data = array(
                                    'title'              => $this->input->post('name'),
                                    'description'       => $this->input->post('description'),
                                );
                            if ($result = $this->master_model->edit_data('product_ingredients', $id, $data)){
                               
                                $return['res'] = 'success';
                                $return['msg'] = 'Saved.';
                            }
                        }
                        else{ 
                            
                            $data = array(
                                    'title'              => $this->input->post('name'),
                                    'description'       => $this->input->post('description'),
                                );
                               
                            if ($result = $this->master_model->add_ingredient($data)) {

                                $return['res'] = 'success';
                                $return['msg'] = 'Saved.';
                            }
                        }
                    }
                    echo json_encode($return);
                    break;
                case 'delete_product':
                     $data['search'] = '';
                    $search='null';
                    $id = $p1;
                    if($this->master_model->delete_ingredient($id))
                    {
                        $data['search'] = '';
                        
                        if (@$_POST['search']) {
                            $data['search'] = $_POST['search'];
                            $data['parent_id'] = $_POST['parent_id'];
                            $data['cat_id'] = $_POST['cat_id'];
                            $cat_id = $_POST['cat_id'];
                            $parent_id = $_POST['parent_id'];
                            $search=$_POST['search'];

                        }
                      
                        $this->load->library('pagination');
                        $config = array();
                        $config["base_url"]         = base_url()."ingredient/tb/".$search;
                        $config["total_rows"]       = $this->master_model->ingredients($search);
                        $data['total_rows']         = $config["total_rows"];
                        $config["per_page"]         = 20;
                        $config["uri_segment"]      = 7;
                        $config['attributes']       = array('class' => 'pag-link');
                        $config['full_tag_open']    = "<div class='pag'>";
                        $config['full_tag_close']   = "</div>";
                        $config['first_link']       = '&lt;&lt;';
                        $config['last_link']        = '&gt;&gt;';
                        $this->pagination->initialize($config);
                        $data["links"]              = $this->pagination->create_links();
                        $data['page']               = $page = ($p4!=null) ? $p4 : 0; 
                        $data['per_page']           = $config["per_page"];
                        $data['ingredients']           = $this->master_model->ingredients($search,$config["per_page"],$page);
                        $data['update_url']         = base_url().'ingredient/create/';
                        $page                       = 'admin/master/ingredients/tb';

    //                    if (@$_POST['cat_id'] || @$_POST['child_cat_id']) {
    //                        if (empty($pro_id)) {
    //                            $config["total_rows"] = array();
    //                            $data['ingredients'] = array();
    //                        }
    //                    }
                        $this->load->view($page, $data);
                        
                        }
                    break;
        }
    }
    
    //Products

    // Start::collection_manager 
    public function products($action=null,$p1=null,$p2=null,$p3=null,$p4=null,$p5=null,$p6=null,$p7=null)
    {
        $data['user']  = $user         = checkLogin();
        switch ($action) {
            case null:
                $data['menu_id'] = $this->uri->segment(2);
                $data['title']          = 'Products';
                $data['unit_type']      = $this->master_model->view_data('unit_master');
                $data['categories']     = $this->master_model->view_data('products_category');
                $data['tb_url']         = base_url().'products/tb';
                $data['new_url']        = base_url().'products/create';
                $page                   = 'admin/master/products/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
                $this->header_and_footer($page, $data);
                break;

                case 'tb':
                    $data['search'] = '';
                
                    $data['cat_id'] = '';
                    $data['parent_id'] = '';
                    $data['child_cat_id'] = '';
                    $data['dpco_id'] = '';
                    $data['company_id'] = '';
                    //below variable section used for models and other places
                    $cat_id='null';
                    $parent_id='null';
                    $search='null';
                    $child_cat_id='null';
                    $dpco_id='null';
                    $company_id='null';
                    $pro_id = array();
                    //get section intiliazation

                    if($p1!=null)
                    {
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $p1])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }
                    }
                    if($p2!=null)
                    {
                        $pro_id = array();
                        $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $p2])->result();
                        foreach($get_proid as $row){
                            $pro_id[] = $row->pro_id;
                        }

                        $data['parent_id'] = $p1;
                        $data['cat_id'] = $p2;
                        $parent_id = $p1;
                        $cat_id = $p2;
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $p1 , 'is_deleted' => 'NOT_DELETED'])->result();
                    }
                    if($p3!=null)
                    {
                        $data['child_cat_id'] = $p3;
                        $child_cat_id = $p3;
                        $data['child_cat'] = $this->db->get_where('products_category',['is_parent' => $p2 , 'is_deleted' => 'NOT_DELETED'])->result();
                    }
                    if($p4!=null)
                    {
                        $data['company_id'] = $p4;
                        $company_id = $p4;
                    }
                    if($p5!=null)
                    {
                        $data['dpco_id'] = $p5;
                        $dpco_id = $p5;
                    }
                    if($p6!=null)
                    {
                        $data['search'] = $p6;
                        $search = $p6;
                    }
                    //end of section
                    if (@$_POST['search']) {
                        $data['search'] = $_POST['search'];
                        $search=$_POST['search'];
                    }

                    if (@$_POST['dpco_id']) {
                        $data['dpco_id'] = $_POST['dpco_id'];
                        $dpco_id=$_POST['dpco_id'];
                    }

                    if (@$_POST['company_id']) {
                        $data['company_id'] = $_POST['company_id'];
                        $company_id=$_POST['company_id'];
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
                    $config["base_url"]         = base_url()."products/tb/".$parent_id."/".$cat_id."/".$child_cat_id."/".$company_id."/".$dpco_id."/".$search;
                    $config["total_rows"]       = $this->master_model->products($parent_id,$pro_id,$cat_id,$child_cat_id,$company_id,$dpco_id,$search);
                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 20;
                    $config["uri_segment"]      = 7;
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    $data['page']               = $page = ($p5!=null) ? $p5 : 0; 
                    $data['per_page']           = $config["per_page"];
                    $data['products']           = $this->master_model->products($parent_id,$pro_id,$cat_id,$child_cat_id,$company_id,$dpco_id,$search,$config["per_page"],$page);
                    $data['update_url']         = base_url().'products/create/';
                    $data['image_url']          = base_url().'products/view_image/';
                    $data['duplicate_url']      = base_url().'products/duplicate/';
                    $data['pv_url']             = base_url().'products/add-property-value/';
                    $data['map_url']            = base_url().'products/map-product/';
                    $data['recommend_url']      = base_url().'products/recommend_product/';
                    $data['pf_url']             = base_url().'products/flags/';
                    $data['plan_type_url']      = base_url().'products/plan_type/';
                    $data['pimg_url']           = base_url().'products/property-image/';
                    $data['p_multi_map_url']    = base_url().'products/add-multi-buy/';
                    $page                       = 'admin/master/products/tb';
                    $data['companies']          = $this->master_model->get_data('brand_master','active','1');
                    $data['ingredients']          = $this->master_model->get_data('product_ingredients','active','1');
                    $data['dpco']          = $this->master_model->get_data('dpco','active','1');
                    
                    $data['properties']         = $this->master_model->view_data('product_props_master');
                    $data['unit_type']          = $this->master_model->view_data('unit_master');
                    $data['categories']         = $this->master_model->view_data('products_category');
                    $data['parent_cat'] = $this->master_model->get_data('products_category','is_parent','0');

                    $data['cat_pro_map']         = $this->master_model->get_cat_pro_map_for_product_list();
                    // print_r($get_proid); die;
                    if (@$_POST['cat_id'] || @$_POST['child_cat_id']) {
                        if (empty($pro_id)) {
                            $config["total_rows"] = array();
                            $data['products'] = array();
                        }
                    }
                    $this->load->view($page, $data);
                    break;

                    case 'recommend_product':
                        $data['product_id']     = $p1;
                        $data['parent_cat']     = $this->master_model->get_data('products_category','is_parent','0');
                        $data['products']       = $this->master_model->get_recommend_products($p1);
                        $page                   = 'admin/master/products/recommend_product';
                        $this->load->view($page,$data);
                    break;

                    case 'recommend_map_product':
                        $pid= $this->input->post('pid');
                        $product_id= $this->input->post('product_id');
                
                        $data1['flg'] = '1';

                        $data1['pid'] = $pid;
                        $data = array(
                            'pro_id'     => $product_id,
                            'map_pro_id'     => $pid,
                        );
                        // $rev_data = array(
                        //     'pro_id'     => $pid,
                        //     'map_pro_id'     => $product_id,
                        // );
                        // $getmapdata =  $this->db->where(['pro_id' =>$product_id])->get('recommend_p_map')->result();
                        // if(!empty($getmapdata))
                        // {
                        //     foreach($getmapdata as $map_data)
                        //     {
                        //         // $rev_map_data = array(
                        //         //     'map_pro_id'     => $map_data->map_pro_id,
                        //         //     'pro_id'     => $pid,
                        //         // );
                        //         $map_data = array(
                        //             'pro_id'     => $map_data->pro_id,
                        //             'map_pro_id'     => $pid,
                        //         );
                        //         $this->master_model->add_data('recommend_p_map',$map_data);
                        //         //$this->master_model->add_data('recommend_p_map',$rev_map_data);
                        //     }
                        // }
                        if($this->master_model->add_data('recommend_p_map',$data))          
                        {
                            $this->load->view('admin/master/products/recommend_map_unmap',$data1);
                
                        }
                    break;

                    case 'fetch_recommend_products':
                        $psearch= 'null';
                        $data['psearch'] = '';
                        $parent_id= 'null';
                        $data['parent_id'] = '';
                        $pro_id = array();
    
                        if (@$_POST['psearch']) {
                            $data['psearch'] = $_POST['psearch'];
                            $psearch=$_POST['psearch'];
                       
                        }

                        if(@$_POST['parent_id'])
                        {
                            $data['parent_id'] = $_POST['parent_id'];
                            $parent_id=$_POST['parent_id'];

                            $pro_id = array();
                            $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['parent_id']])->result();
                            foreach($get_proid as $row){
                                $pro_id[] = $row->pro_id;
                            }
                            //print_r($pro_id); die;
                        }
                        if(@$_POST['parent_cat_id'])
                        {
                            $data['parent_id'] = $_POST['parent_cat_id'];
                            $parent_id=$_POST['parent_cat_id'];

                            $pro_id = array();
                            $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['parent_cat_id']])->result();
                            foreach($get_proid as $row){
                                $pro_id[] = $row->pro_id;
                            }
                            //print_r($pro_id); die;
                        }

                        if(@$_POST['cat_id'])
                        {
                            $pro_id = array();
                            $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['cat_id']])->result();
                            foreach($get_proid as $row){
                                $pro_id[] = $row->pro_id;
                            }                            
                        }

                        // print_r($pro_id); die;
    
                            $product_id= $this->input->post('product_id');
    
                            $data['product_id'] = $product_id;
                            $data['available_products'] = $this->master_model->get_products($pro_id,$psearch);
                            $data['product_mapping'] = $this->master_model->get_recommend_data($product_id);

                            if (@$_POST['parent_cat_id'] || @$_POST['cat_id']) {
                                if (empty($pro_id)) {
                                    $data["available_products"] = array();
                                    //$data['products'] = array();
                                }
                            }
                            $this->load->view('admin/master/products/recommend_available_products',$data);    
                        break;

                        case 'delete_recommend_map_product':
                            $pid = $this->input->post('pid');
                            $product_id = $this->input->post('product_id');
                            
                            if ($this->db->where(['pro_id'=>$product_id, 'map_pro_id'=>$pid])->delete('recommend_p_map')) {
                                $data['product_id'] = $product_id;
                                $data['parent_cat'] = $this->master_model->get_data('products_category','is_parent','0');
                                $data['products'] = $this->master_model->get_recommend_products($product_id);
                                $page                       = 'admin/master/products/recommend_product';
                                $this->load->view($page,$data);
                            }        
                        break;

                        case 'remove_recommend_map_product':                        
                            $pid = $this->input->post('pid');
                            $product_id = $this->input->post('product_id');
                            $data['pid'] = $pid;
                            $data['flg'] = '0';
                            if ($this->db->where(['pro_id'=>$product_id, 'map_pro_id'=>$pid])->delete('recommend_p_map')) {
                                //$this->db->where(['map_pro_id' => $pid,'pro_id' =>$product_id])->delete('recommend_p_map');
                                $this->load->view('admin/master/products/recommend_map_unmap',$data);
                            }                            
                        break;
                
                    case 'map-product':
                        $data['product_id'] = $p1;
                        $data['parent_cat'] = $this->master_model->get_data('products_category','is_parent','0');
                        $data['products'] = $this->master_model->get_map_products($p1);
                        $page                       = 'admin/master/products/map_product';
                        $this->load->view($page,$data);
                    break;                    

                    case 'fetch_products':
                        $psearch= 'null';
                        $data['psearch'] = '';
                        $parent_id= 'null';
                        $data['parent_id'] = '';
                        $pro_id = array();
    
                        if (@$_POST['psearch']) {
                            $data['psearch'] = $_POST['psearch'];
                            $psearch=$_POST['psearch'];
                       
                        }

                        if(@$_POST['parent_id'])
                        {
                            $data['parent_id'] = $_POST['parent_id'];
                            $parent_id=$_POST['parent_id'];

                            $pro_id = array();
                            $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['parent_id']])->result();
                            foreach($get_proid as $row){
                                $pro_id[] = $row->pro_id;
                            }
                            //print_r($pro_id); die;
                        }
                        if(@$_POST['parent_cat_id'])
                        {
                            $data['parent_id'] = $_POST['parent_cat_id'];
                            $parent_id=$_POST['parent_cat_id'];

                            $pro_id = array();
                            $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['parent_cat_id']])->result();
                            foreach($get_proid as $row){
                                $pro_id[] = $row->pro_id;
                            }
                            //print_r($pro_id); die;
                        }

                        if(@$_POST['cat_id'])
                        {
                            $pro_id = array();
                            $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['cat_id']])->result();
                            foreach($get_proid as $row){
                                $pro_id[] = $row->pro_id;
                            }                            
                        }

                        // print_r($pro_id); die;
    
                            $product_id= $this->input->post('product_id');
    
                            $data['product_id'] = $product_id;
                            $data['available_products'] = $this->master_model->get_products($pro_id,$psearch);
                            $data['product_mapping'] = $this->master_model->get_mapped_data($product_id);

                            if (@$_POST['parent_cat_id'] || @$_POST['cat_id']) {
                                if (empty($pro_id)) {
                                    $data["available_products"] = array();
                                    //$data['products'] = array();
                                }
                            }
                            $this->load->view('admin/master/products/available_products',$data);
    
    
                        break;
                        case 'delete_map_product':
                            $pid = $this->input->post('pid');
                            $product_id = $this->input->post('product_id');
                            
                            if ($this->db->where('map_pro_id', $pid)->delete('products_mapping') && $this->db->where('pro_id', $pid)->delete('products_mapping')) {
                                $data['product_id'] = $product_id;
                                $data['parent_cat'] = $this->master_model->get_data('products_category','is_parent','0');
                                $data['products'] = $this->master_model->get_map_products($product_id);
                                $page                       = 'admin/master/products/map_product';
                                $this->load->view($page,$data);
                            } 
        
                            break;
                        case 'remove_map_product':
						
                            $pid = $this->input->post('pid');
                            $product_id = $this->input->post('product_id');
                            $data['pid'] = $pid;
                            $data['flg'] = '0';
                            if ($this->db->where('map_pro_id', $pid)->delete('products_mapping') && $this->db->where('pro_id', $pid)->delete('products_mapping')) {
                                $this->db->where(['map_pro_id' => $pid,'pro_id' =>$product_id])->delete('products_mapping');
                                $this->load->view('admin/master/products/map_unmap',$data);
                            } 
                            
                            break;

            case 'view_image':
                $data['product'] = $this->master_model->get_row_data1('products_photo','id',$p1);
                $page                       = 'admin/master/products/view_image';
                
                $this->load->view($page,$data);
                break;

            case 'create':
                $data['remote']             = base_url().'master-data/remote/products/';
                $data['action_url']         = base_url().'products/save';
                $data['tax_slabs'] = $this->master_model->get_data('tax_slabs','active','1');
                $page                       = 'admin/master/products/create';
                if ($p1!=null) {
                    $data['action_url']     = base_url().'products/save/'.$p1;
                    $data['value']          = $this->master_model->product($p1);
                    $data['cat_pro_map']    = $this->master_model->get_cat_pro_map($p1);
                    $data['remote']         = base_url().'master-data/remote/products/'.$p1;
                    $page                   = 'admin/master/products/update';
                    // print_r($data['cat_pro_map']); die;
                    $data['shops_inventory'] = $this->master_model->get_data2_row('shops_inventory',['is_deleted'=>'NOT_DELETED','product_id'=>$data['value']->id]);
                    $data['applyoffer'] = $this->master_model->getRow('shops_coupons_offers',['product_id'=>$p1,'is_deleted'=>'NOT_DELETED','shop_id'=>'6']);
                }
                $data['offers']           = $this->master_model->getOffers();
                $data['properties']         = $this->master_model->view_data('product_props_master');
                $data['unit_type']          = $this->master_model->get_data('unit_master','active','1');
                $data['brands']          = $this->master_model->get_data('brand_master','active','1');
                $data['ingredients']          = $this->master_model->get_data('product_ingredients','active','1');
                $data['dpco']          = $this->master_model->get_data('dpco','active','1');
                
                $data['parent_cat'] = $this->master_model->get_parent_category();
                $data['parent_id'] = $this->master_model->get_parent_id();
                $data['categories'] = $this->master_model->get_data('products_category','is_parent !=','0');
                $data['all_categories'] = $this->db->get_where('products_category',['active' =>'1', 'is_deleted' => 'NOT_DELETED'])->result();
                // print_r($data['parent_cat']); die;
                // $data['categories']         = $this->master_model->view_data('products_category');
                $data['form_id']            = uniqid();
                
               
                $this->load->view($page, $data);
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
                case 'duplicate':
                    $data['tax_slabs'] = $this->master_model->get_data('tax_slabs','active','1');
                    $data['properties']         = $this->master_model->view_data('product_props_master');
                    $data['unit_type']          = $this->master_model->get_data('unit_master','active','1');
                    $data['brands']          = $this->master_model->get_data('brand_master','active','1');
                    $data['flavours']          = $this->master_model->get_data('flavour_master','active','1');
                    $data['duplicate_id']     =$p1;
                    $data['ingredients']          = $this->master_model->get_data('product_ingredients','active','1');
                    $data['dpco']          = $this->master_model->get_data('dpco','active','1');
                    $data['parent_cat'] = $this->master_model->get_parent_category();
                    $data['parent_id'] = $this->master_model->get_parent_id();
                    $data['categories'] = $this->master_model->get_data('products_category','is_parent !=','0');
                    $data['value']          = $this->master_model->product($p1);
                    $data['cat_pro_map']    = $this->master_model->get_cat_pro_map($p1);
                    $data['remote']         = base_url().'master-data/remote/products';
                    $data['shops_inventory'] = $this->master_model->get_data2_row('shops_inventory',['is_deleted'=>'NOT_DELETED','product_id'=>$data['value']->id]);
                    $data['offers']           = $this->master_model->getOffers();
                    $data['action_url']     = base_url().'products/duplicatesave';
                    $data['value']          = $this->master_model->product($p1);
                    $data['applyoffer'] = $this->master_model->getRow('shops_coupons_offers',['product_id'=>$p1,'is_deleted'=>'NOT_DELETED','shop_id'=>'6']);
                    $data['remote']         = base_url().'master-data/remote/products/';
                    $page                   = 'admin/master/products/duplicate';
                $this->load->view($page, $data);
                break;
                case 'duplicatesave':
                    $id = $p1;
                    $return['res'] = 'error';
                    $return['msg'] = 'Not Saved!';
    
                    if ($this->input->server('REQUEST_METHOD')=='POST') { 
                      
                         if ($id!=null) {
                            $tax_slab = explode(",",$this->input->post('tax_id'));
                            $unit_type = explode(",",$this->input->post('unit_type_id'));

                            $cat_id = count($this->input->post('cat_id'));
                            $this->db->delete('cat_pro_maps', array('pro_id' => $id));
                            logs($user->id,$id,'DELETE','Delete Cat Pro Maps');
                            $data = array(
                                'tax_id'     => $tax_slab[0],
                                'tax_value'     => $tax_slab[1],
                                // 'parent_cat_id'     => $this->input->post('parent_cat_id'),
                                // 'sub_cat_id'     => $this->input->post('cat_id'),
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
                                logs($user->id,$id,'DULPICATE_EDIT','Edit Duplicate Product');
                                for ($i=0; $i < $cat_id; $i++) { 
                                    $data_cat_id = array(
                                        'pro_id'=>$id,
                                        'cat_id'=>$this->input->post('cat_id')[$i],
                                    );
                                    $mapid=$this->master_model->add_cat_pro_map($data_cat_id);
                                    $msg = 'Cat Pro Maps'.$id.'-'.$this->input->post('cat_id')[$i];
                                    logs($user->id,$mapid,'ADD',$msg);
                                }
                                 //  $return['res'] = 'success';
                                 // $return['msg'] = 'Saved.';
                                $shop_inventry_id = $this->input->post('shop_inventry_id');

                                if (!empty($shop_inventry_id)) {
                                    $data_shop_inventry = array(
                                        'qty'=>$this->input->post('s_qty'),
                                        'purchase_rate'=>$this->input->post('purchase_rate'),
                                        'mrp'=>$this->input->post('mrp'),
                                        'selling_rate'=>$this->input->post('mrp'),
                                        'shop_id'=>'6',
                                        'vendor_id'=>'17',
                                    );

                                    if ($this->master_model->edit_data('shops_inventory', $shop_inventry_id, $data_shop_inventry)) {
                                        logs($user->id,$shop_inventry_id,'EDIT','Edit Shops Inventory');
                                        $data_shop_inventry['action']="UPDATE";
                                        $data_shop_inventry['shops_inventory_id'] = $shop_inventry_id;

                                        if ($shop_inventry_ids=$this->master_model->add_data('shop_inventory_logs', $data_shop_inventry)) {
                                            logs($user->id,$shop_inventry_ids,'ADD','Add Shops Inventory');
                                            $return['res'] = 'success';
                                            $return['msg'] = 'Saved.';
                                        }
                                    }  
                                }
                                else{
                                    $data_shop_inventry = array(
                                        'product_id'=>$id,
                                        'qty'=>$this->input->post('s_qty'),
                                        'purchase_rate'=>$this->input->post('purchase_rate'),
                                        'mrp'=>$this->input->post('mrp'),
                                        'selling_rate'=>$this->input->post('mrp'),
                                        'shop_id'=>'6',
                                        'vendor_id'=>'17',
                                        
                                    );

                                    if ($insert_stock = $this->master_model->add_data('shops_inventory', $data_shop_inventry)) {
                                        logs($user->id,$insert_stock,'ADD','Add Shops Inventory');
                                        $data_shop_inventry['action']="LATEST_UPDATE"; $data_shop_inventry['shops_inventory_id']=$insert_stock;

                                        if ($insert_id = $this->master_model->add_data('shop_inventory_logs', $data_shop_inventry)) {
                                            $return['res'] = 'success';
                                            $return['msg'] = 'Saved.';
                                        }
                                    }                                    
                                }

                            }
                        }

                         else{ 
                           $tax_slab = explode(",",$this->input->post('tax_id'));
                            $unit_type = explode(",",$this->input->post('unit_type_id'));

                            $cat_id = count($this->input->post('cat_id'));
                            $duplicate_id = $this->input->post('duplicate_id');
                           
                            $namepro = $this->input->post('name');
                            $data = array(
                                    'tax_id'     => $tax_slab[0],
                                    'tax_value'     => $tax_slab[1],
                                    // 'parent_cat_id'     => $this->input->post('parent_cat_id'),
                                    // 'sub_cat_id'     => $this->input->post('cat_id'),
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

                                $margn=$margnper=0;
                                $NewPurchaseRate = $this->input->post('NewPurchaseRate');
                                $NewLandingCost = $this->input->post('NewLandingCost');
                                $NewMrp = $this->input->post('NewMrp');
                                $NewSellingRate = $this->input->post('NewSellingRate');
                                $NewTotalTax = $this->input->post('NewTotalTax');
                                $NewTotalValue = $this->input->post('NewTotalValue');
                                $NewQty = $this->input->post('NewQty');
                                $purchase = array(
                                    'supplier_id' => '17',  //for static only anonymous supplier
                                    'shop_id' => '6',
                                    'purchase_order_date' => date('Y-m-d'),
                                    'shipping_date' =>date('Y-m-d'),
                                    'total_amount' => $NewTotalValue,
                                    'total_tax' => $NewTotalTax,
                                    'gross_total' => $NewTotalValue,
                                    'total_with_tax' => $NewTotalValue,
                                    'total_qty' => $NewQty,
                                    'status'=>'3',
                                    );
                                    $this->db->insert('purchase',$purchase);
                                    $margn=$NewMrp-$NewSellingRate;
                                    $margnper = ($margn/$NewSellingRate)*100;
                                    $insert_id = $this->db->insert_id();
                                    if($insert_id){

                                         // Add case register
                                  $new_purchase_order_no = $this->generate_purchase_order_no('17');
                                  $this->db->update('purchase',['purchase_order_no'=>$new_purchase_order_no],['id'=>$insert_id]);
                                            $ledger['type']         = 'Purchase';
                                            $ledger['customer_id']  = '17';  //for static only anonymous supplier
                                            $ledger['order_id']     = NULL;
                                            $ledger['inventory_id'] = $insert_id;
                                            $ledger['dr']           = $NewTotalValue;
                                            $ledger['reference_no'] = $new_purchase_order_no;
                                            $ledger['PaymentDate']  =  date('Y-m-d');
                                            $ledger['shop_id']      = '6';
                                            $ledger['txn_type']     = 4;
                                            $this->load->model('cash_register_model');
                                            $this->cash_register_model->insertRow($ledger);

                                            $dataitem = array(
                                            'purchase_id' => $insert_id,
                                            'item_id' => $result,
                                            'qty' => $NewQty,
                                            'unit_cost' => $NewPurchaseRate,
                                            'mrp' => $NewMrp,
                                            'tax' => $tax_slab[1],
                                            'tax_value' => $NewTotalTax,
                                            'landing_cost' => $NewLandingCost,
                                            'margin' => bcdiv($margnper, 1, 2),
                                            'total' => $NewTotalValue,
                                        );
                                        $this->db->insert('purchase_items',$dataitem);
                                        $insert_id2 = $this->db->insert_id();
                                        $data_shop_inventry = array(
                                            'product_id'=>$result,
                                            'qty'=>$NewQty,
                                            'purchase_rate'=>$NewPurchaseRate,
                                            'mrp'=>$NewMrp,
                                            'shop_id'=>'6',
                                            'selling_rate'=>$NewMrp,
                                            'tax_value'=>$tax_slab[1],
                                            'total_value'=>$NewTotalValue,
                                            'total_tax'=>$NewTotalTax,
                                            'purchase_item_id'=>$insert_id2,
                                            'vendor_id'=>'17',
                                        );
                                        $data_shop_inventry_log = array(
                                            'product_id'=>$result,
                                            'qty'=>$NewQty,
                                            'purchase_rate'=>$NewPurchaseRate,
                                            'mrp'=>$NewMrp,
                                            'shop_id'=>'6',
                                            'selling_rate'=>$NewMrp,
                                            'tax_value'=>$tax_slab[1],
                                            'total_value'=>$NewTotalValue,
                                            'total_tax'=>$NewTotalTax,
                                            'vendor_id'=>'17',
                                        );

                                        if ($insert_stock = $this->master_model->add_data('shops_inventory', $data_shop_inventry)) {
                                            logs($user->id,$insert_stock,'Add','Add Shop Inventory');
                                            $data_shop_inventry_log['action']="ADD_INVENTORY"; $data_shop_inventry_log['shops_inventory_id']=$insert_stock;

                                            if ($insert_id = $this->master_model->add_data('shop_inventory_logs', $data_shop_inventry_log)) {
                                                logs($user->id,$insert_id,'Edit','Edit Shop Inventory');
                                                $return['res'] = 'success';
                                                $return['msg'] = 'Saved.';
                                            }
                                        }  
                                    }  
                                  // Apply offer code
                                  $getOffer = $this->master_model->getRow('coupons_and_offers',['id'=>$this->input->post('NewOffer')]);
                                   $offerdata = array(
                                    'offer_assosiated_id'     => $this->input->post('NewOffer'),
                                    'product_id'     => $result,
                                    'shop_id'     => '6',
                                    'offer_associated'     => $getOffer->value,
                                    'offer_upto'     => $getOffer->value,
                                    'discount_type'     => $getOffer->discount_type,
                                );
                                $shops_coupons_offers_id = $this->offers_model->add_data('shops_coupons_offers',$offerdata);
                                logs($user->id,$shops_coupons_offers_id,'ADD','shops_coupons_offers');
                                 $return['res'] = 'success';
                                 $return['msg'] = 'Saved.';


                            }
                        }
                    }
                    echo json_encode($return);
                    break;
                case 'delete_image': 
                $id = $p2;
                if($this->master_model->delete_pro_image($id))
                {
           
                    $data['pid']           = $p1;
                    $data['images']        = $this->master_model->product_img($p1);
                    $data['action_url']    = base_url().'products/property-image/'.$p1;
                    $data['form_id']       = uniqid();
                    $page                  = 'admin/master/products/property_images';

                    $this->load->view($page, $data);
                    
                }
                break;
                case 'delete_product':
                    $return['res'] = 'error';
                    $return['msg'] = 'Not Deleted!';
                    if ($p1!=null) {
                        if($this->master_model->_delete('products_subcategory',['id'=>$p1])){
                            $in = $this->master_model->getRow('shops_inventory',['product_id'=>$p1]);
                            $this->master_model->delete_inventory($in->id);
                            $data = array(
                                'vendor_id' => @$in->vendor_id,
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
                            
                            $this->master_model->delete_inventory_log($data);
                            $saved = 1;
                            $return['res'] = 'success';
                            $return['msg'] = 'Successfully deleted.';
                        }
                    }
                    echo json_encode($return);
                    break;
                case 'delete_product1':
                    $id = $p1;
                    if($this->master_model->delete_product($id))
                    {
                        $data['search'] = '';
                $data['cat_id'] = '';
                $data['parent_id'] = '';
                //below variable section used for models and other places
                $cat_id='null';
                $parent_id='null';
                //get section intiliazation
                if($p2!=null)
                {
                    $data['parent_id'] = $p1;
                    $data['cat_id'] = $p2;
                    $parent_id = $p1;
                    $cat_id = $p2;
                    $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $p1 , 'is_deleted' => 'NOT_DELETED'])->result();
                }
                //end of section
                if (@$_POST['search']) {
                    $data['search'] = $_POST['search'];
                    $data['parent_id'] = $_POST['parent_id'];
                    $data['cat_id'] = $_POST['cat_id'];
                    $cat_id = $_POST['cat_id'];
                    $parent_id = $_POST['parent_id'];
                    $search=$_POST['search'];
               
                }
                if (@$_POST['cat_id']) {
                    $data['search'] = $_POST['search'];
                    $data['cat_id'] = $_POST['cat_id'];
                    $data['parent_id'] = $_POST['parent_id'];
                    $cat_id = $_POST['cat_id'];
                    $parent_id = $_POST['parent_id'];
                    $search=$_POST['search'];
                    $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $_POST['parent_id'] , 'is_deleted' => 'NOT_DELETED'])->result();
                }

                $this->load->library('pagination');
                $config = array();
                $config["base_url"]         = base_url()."products/tb/".$parent_id."/".$cat_id;
                $config["total_rows"]       = $this->master_model->products($parent_id,$cat_id);
                $data['total_rows']         = $config["total_rows"];
                $config["per_page"]         = 20;
                $config["uri_segment"]      = 5;
                $config['attributes']       = array('class' => 'pag-link');
                $config['full_tag_open']    = "<div class='pag'>";
                $config['full_tag_close']   = "</div>";
                $config['first_link']       = '&lt;&lt;';
                $config['last_link']        = '&gt;&gt;';
                $this->pagination->initialize($config);
                $data["links"]              = $this->pagination->create_links();
                $data['page']               = $page = ($p3!=null) ? $p3 : 0;
                $data['per_page']           = $config["per_page"];
                $data['products']           = $this->master_model->products($parent_id,$cat_id,$config["per_page"],$page);
                $data['update_url']         = base_url().'products/create/';
                $data['image_url']         = base_url().'products/view_image/';
                $data['duplicate_url']         = base_url().'products/duplicate/';
                $data['map_url']             = base_url().'products/map-product/';
                $data['pv_url']             = base_url().'products/add-property-value/';
                $data['pf_url']             = base_url().'products/flags/';
                $data['pimg_url']           = base_url().'products/property-image/';
                $page                       = 'admin/master/products/tb';

                
                $data['properties']         = $this->master_model->view_data('product_props_master');
                $data['unit_type']          = $this->master_model->view_data('unit_master');
                $data['categories']         = $this->master_model->view_data('products_category');
                $data['parent_cat'] = $this->master_model->get_data('products_category','is_parent','0');
                
                $this->load->view($page, $data);
                    }
                    break;
            case 'make_cover': 
                $id = $p2;
                if($this->master_model->remove_product_cover($p1) && $this->master_model->make_product_cover($id))
                {
                    $data['pid']           = $p1;
                    $data['images']        = $this->master_model->product_img($p1);
                    $data['action_url']    = base_url().'products/property-image/'.$p1;
                    $data['form_id']       = uniqid();
                    $page                  = 'admin/master/products/property_images';

                    $this->load->view($page, $data);
                    
                }
                break;
            case 'update_prod_seq':
                $id = $p2;
                $seq = $p3;
                $data = array(
                    'seq'     => $seq,
                );
                if($this->master_model->update_prod_seq($id,$data))
                {
                    $data['pid']           = $p1;
                    $data['images']        = $this->master_model->product_img($p1);
                    $data['action_url']    = base_url().'products/property-image/'.$p1;
                    $data['form_id']       = uniqid();
                    $page                  = 'admin/master/products/property_images';

                    $this->load->view($page, $data);
                    
                }
                else
                {
                    echo('Sequence Already Exists!!');
                }
                break;

                case 'save':
                    $id = $p1;
                    $return['res'] = 'error';
                    $return['msg'] = 'Not Saved!';
    
                    if ($this->input->server('REQUEST_METHOD')=='POST') { 
                      
                         if ($id!=null) {
                            $tax_slab = explode(",",$this->input->post('tax_id'));
                            $unit_type = explode(",",$this->input->post('unit_type_id'));

                            $cat_id = count($this->input->post('cat_id'));
                            $this->db->delete('cat_pro_maps', array('pro_id' => $id));
                            $data = array(
                                    'tax_id'     => $tax_slab[0],
                                    'tax_value'     => $tax_slab[1],
                                    // 'parent_cat_id'     => $this->input->post('parent_cat_id'),
                                    // 'sub_cat_id'     => $this->input->post('cat_id'),
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
                                
                                for ($i=0; $i < $cat_id; $i++) { 
                                    $data_cat_id = array(
                                        'pro_id'=>$id,
                                        'cat_id'=>$this->input->post('cat_id')[$i],
                                    );
                                    $mapid= $this->master_model->add_cat_pro_map($data_cat_id);
                                    $msg = 'Cat Pro Maps'.$id.'-'.$this->input->post('cat_id')[$i];
                                    logs($user->id,$mapid,'ADD',$msg);
                                }

                                $shop_inventry_id = $this->input->post('shop_inventry_id');
                                $exist_qty = $this->input->post('exist_qty');
                               
                                if (!empty($shop_inventry_id)) {
                                    // $add_qty = $this->input->post('add_qty');
                                    // $sub_qty   = $this->input->post('sub_qty');
                                       
                                    // if(!empty($add_qty))
                                    // {
                                    //     $final_qty = $exist_qty + $add_qty;
                                    //     $action="ADD_INVENTORY";
                                    //     $log_qty = $add_qty;
                                    // }else if(!empty($sub_qty))
                                    // {
                                    //     $final_qty = $exist_qty - $sub_qty;
                                    //     $action="SUB_INVENTORY";
                                    //     $log_qty = $sub_qty;
                                    // }else
                                    // {
                                    //     $final_qty = $exist_qty;
                                    //     $log_qty = '';
                                    // }
                                    $NewPurchaseRate = $this->input->post('NewPurchaseRate');
                                    $NewLandingCost = $this->input->post('NewLandingCost');
                                    $NewMrp = $this->input->post('NewMrp');
                                    $NewSellingRate = $this->input->post('NewSellingRate');
                                    $NewTotalTax = $this->input->post('NewTotalTax');
                                    $NewTotalValue = $this->input->post('NewTotalValue');
                                    $NewQty = $this->input->post('NewQty');
                                    $diff_qty = $this->input->post('diff_qty');
                                    $purchase_item_id = $this->input->post('purchase_item_id');
                                    $diffQty = $NewQty-$diff_qty;
                                    $PUR =  $this->master_model->getRow('purchase_items',['id'=>$purchase_item_id]);
                                    $PURCHASE =  $this->master_model->getRow('purchase',['id'=>$PUR->purchase_id]);
                                    $prevTotal = $PUR->total;
                                    $prevTotalTax = $PUR->tax_value;
                                    $DiffTotal = $NewTotalValue-$prevTotal;
                                    $FinalTotal = $PURCHASE->total_amount+($DiffTotal);
                                    $DiffTax = $NewTotalTax-$prevTotalTax;
                                    $FinalTax = $PURCHASE->total_tax+($DiffTax);
                                    $prevTotalQty=$PUR->qty;
                                    $DiffQty = $NewQty-$prevTotalQty;
                                    $FinalQty = $PURCHASE->total_qty+($DiffQty);
                                    $margn=$NewMrp-$NewSellingRate;
                                    $margnper = ($margn/$NewSellingRate)*100;
                                      $purchase = array(
                                        // 'supplier_id' => '17',
                                        // 'shop_id' => '6',
                                        'total_amount' => $FinalTotal,
                                        'total_tax' => $FinalTax,
                                        'gross_total' => $FinalTotal-$FinalTax,
                                        'total_with_tax' => $FinalTotal-$FinalTax,
                                        'total_qty' => $FinalQty,
                                        );
                                       
                                        $this->db->update('purchase',$purchase,['id'=>$PUR->purchase_id]);
                                        $affected_rows = $this->db->affected_rows();
                                         if ($affected_rows > 0) {
                                            $ledger_cond['txn_type']        = 4;
                                            $ledger_cond['inventory_id']    = $PUR->purchase_id;
                                            $ledger['dr']           = $FinalTotal;
                                            $this->load->model('cash_register_model');
                                            $this->cash_register_model->updateRow($ledger_cond,$ledger);
                                               $dataitem = array(
                                                'qty' => $NewQty,
                                                'unit_cost' => $NewPurchaseRate,
                                                'mrp' => $NewMrp,
                                                'tax' => $tax_slab[1],
                                                'tax_value' => $NewTotalTax,
                                                'landing_cost' => $NewLandingCost,
                                                'total' => $NewTotalValue,
                                                'margin'=>$margnper,
                                            );
                                        $this->db->update('purchase_items',$dataitem,['id'=>$purchase_item_id]);
                                    }

                                    $data_shop_inventry = array(
                                        'product_id'=>$id,
                                        'qty'=>$diffQty,
                                        'purchase_rate'=>$NewPurchaseRate,
                                        'mrp'=>$NewMrp,
                                        'selling_rate'=>$NewMrp,
                                        'tax_value'=>$tax_slab[1],
                                        'total_value'=>$NewTotalValue,
                                        'total_tax'=>$NewTotalTax,
                                        // 'vendor_id'=>'17',
                                        );
                                        $data_shop_inventry_log = array(
                                            'product_id'=>$id,
                                            'qty'=>$NewQty,
                                            'purchase_rate'=>$NewPurchaseRate,
                                            'mrp'=>$NewMrp,
                                            'selling_rate'=>$NewMrp,
                                            'tax_value'=>$tax_slab[1],
                                            'total_value'=>$NewTotalValue,
                                            'total_tax'=>$NewTotalTax,
                                            'vendor_id'=>$PURCHASE->supplier_id,
                                            'shop_id' => '6',
                                            );

                                    if ($this->master_model->edit_data('shops_inventory', $shop_inventry_id, $data_shop_inventry)) {
                                        logs($user->id,$shop_inventry_id,'EDIT','Edit Shop Inventory');
                                        $data_shop_inventry_log['action']=$action;
                                        $data_shop_inventry_log['shops_inventory_id'] = $shop_inventry_id;
                                        if ($shop_inventry_ids=$this->master_model->add_data('shop_inventory_logs', $data_shop_inventry_log)) {
                                            logs($user->id,$shop_inventry_ids,'Add','Add Shop Inventory');
                                            $return['res'] = 'success';
                                            $return['msg'] = 'Saved.';
                                        }
                                   }
                                  
                                   $return['res'] = 'success';
                                   $return['msg'] = 'Saved.';


                                }
                                else{
                                    $NewPurchaseRate = $this->input->post('NewPurchaseRate');
                                    $NewLandingCost = $this->input->post('NewLandingCost');
                                    $NewMrp = $this->input->post('NewMrp');
                                    $NewSellingRate = $this->input->post('NewSellingRate');
                                    $NewTotalTax = $this->input->post('NewTotalTax');
                                    $NewTotalValue = $this->input->post('NewTotalValue');
                                    $NewQty = $this->input->post('NewQty');
                                    $data_shop_inventry = array(
                                        'product_id'=>$id,
                                        'qty'=>$NewQty,
                                        'purchase_rate'=>$NewPurchaseRate,
                                        'mrp'=>$NewMrp,
                                        'shop_id'=>'6',
                                        'selling_rate'=>$NewMrp,
                                        'tax_value'=>$tax_slab[1],
                                        'total_value'=>$NewTotalValue,
                                        'total_tax'=>$NewTotalTax,
                                        'vendor_id'=>'17',
                                    );

                                    if ($insert_stock = $this->master_model->add_data('shops_inventory', $data_shop_inventry)) {
                                        logs($user->id,$insert_stock,'Add','Add Shop Inventory');
                                        $data_shop_inventry['action']="ADD_INVENTORY"; $data_shop_inventry['shops_inventory_id']=$insert_stock;

                                        if ($insert_id = $this->master_model->add_data('shop_inventory_logs', $data_shop_inventry)) {
                                            logs($user->id,$insert_id,'Edit','Edit Shop Inventory');
                                            $return['res'] = 'success';
                                            $return['msg'] = 'Saved.';
                                        }
                                    }                                    
                                }
                                // Apply offer code
                                if($this->offers_model->remove_offer_product($id,'6')){
                                    logs($user->id,$id,'DELETE','Remove Offer Product');
                                $getOffer = $this->master_model->getRow('coupons_and_offers',['id'=>$this->input->post('NewOffer')]);
                                $offerdata = array(
                                 'offer_assosiated_id'     => $this->input->post('NewOffer'),
                                 'product_id'     => $id,
                                 'shop_id'     => '6',
                                 'offer_associated'     => $getOffer->value,
                                 'offer_upto'     => $getOffer->value,
                                 'discount_type'     => $getOffer->discount_type,
                             );
                             $id=$this->offers_model->add_data('shops_coupons_offers',$offerdata);
                             logs($user->id,$id,'ADD','Add Offer Product');
                            }

                            }
                        }
                        else{ 
                            $tax_slab = explode(",",$this->input->post('tax_id'));
                            $unit_type = explode(",",$this->input->post('unit_type_id'));

                            $cat_id = count($this->input->post('cat_id'));

                            $data = array(
                                    'tax_id'     => $tax_slab[0],
                                    'tax_value'     => $tax_slab[1],
                                    // 'parent_cat_id'     => $this->input->post('parent_cat_id'),
                                    // 'sub_cat_id'     => $this->input->post('cat_id'),
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
                               
                            if ($result = $this->master_model->add_product($data)) {

                                for ($i=0; $i < $cat_id; $i++) { 
                                    $data_cat_id = array(
                                        'pro_id'=>$result,
                                        'cat_id'=>$this->input->post('cat_id')[$i],
                                    );
                                    $mapid=$this->master_model->add_cat_pro_map($data_cat_id);
                                    $msg = 'Cat Pro Maps'.$id.'-'.$this->input->post('cat_id')[$i];
                                    logs($user->id,$mapid,'ADD',$msg);
                                }

                                $margn=$margnper=0;
                                $NewPurchaseRate = $this->input->post('NewPurchaseRate');
                                $NewLandingCost = $this->input->post('NewLandingCost');
                                $NewMrp = $this->input->post('NewMrp');
                                $NewSellingRate = $this->input->post('NewSellingRate');
                                $NewTotalTax = $this->input->post('NewTotalTax');
                                $NewTotalValue = $this->input->post('NewTotalValue');
                                $NewQty = $this->input->post('NewQty');
                                $purchase = array(
                                    'supplier_id' => '17',  //for static only anonymous supplier
                                    'shop_id' => '6',
                                    'purchase_order_date' => date('Y-m-d'),
                                    'shipping_date' =>date('Y-m-d'),
                                    'total_amount' => $NewTotalValue,
                                    'total_tax' => $NewTotalTax,
                                    'gross_total' => $NewTotalValue,
                                    'total_with_tax' => $NewTotalValue,
                                    'total_qty' => $NewQty,
                                    'status'=>'3',
                                    );
                                    $this->db->insert('purchase',$purchase);
                                    $margn=$NewMrp-$NewSellingRate;
                                    $margnper = ($margn/$NewSellingRate)*100;
                                    $insert_id = $this->db->insert_id();
                                    if($insert_id){
                                            // Add case register
                                     $new_purchase_order_no = $this->generate_purchase_order_no('17');
                                     $this->db->update('purchase',['purchase_order_no'=>$new_purchase_order_no],['id'=>$insert_id]);
                                            $ledger['type']         = 'Purchase';
                                            $ledger['customer_id']  = '17';  //for static only anonymous supplier
                                            $ledger['order_id']     = NULL;
                                            $ledger['inventory_id'] = $insert_id;
                                            $ledger['dr']           = $NewTotalValue;
                                            $ledger['reference_no'] = $new_purchase_order_no;
                                            $ledger['PaymentDate']  =  date('Y-m-d');
                                            $ledger['shop_id']      = '6';
                                            $ledger['txn_type']     = 4;
                                            $this->load->model('cash_register_model');
                                            $this->cash_register_model->insertRow($ledger);


                                            $dataitem = array(
                                            'purchase_id' => $insert_id,
                                            'item_id' => $result,
                                            'qty' => $NewQty,
                                            'unit_cost' => $NewPurchaseRate,
                                            'mrp' => $NewMrp,
                                            'tax' => $tax_slab[1],
                                            'tax_value' => $NewTotalTax,
                                            'landing_cost' => $NewLandingCost,
                                            'margin' => bcdiv($margnper, 1, 2),
                                            'total' => $NewTotalValue,
                                        );
                                        $this->db->insert('purchase_items',$dataitem);
                                        $insert_id2 = $this->db->insert_id();
                                        $data_shop_inventry = array(
                                            'product_id'=>$result,
                                            'qty'=>$NewQty,
                                            'purchase_rate'=>$NewPurchaseRate,
                                            'mrp'=>$NewMrp,
                                            'shop_id'=>'6',
                                            'selling_rate'=>$NewMrp,
                                            'tax_value'=>$tax_slab[1],
                                            'total_value'=>$NewTotalValue,
                                            'total_tax'=>$NewTotalTax,
                                            'purchase_item_id'=>$insert_id2,
                                            'vendor_id'=>'17',
                                        );
                                        $data_shop_inventry_log = array(
                                            'product_id'=>$result,
                                            'qty'=>$NewQty,
                                            'purchase_rate'=>$NewPurchaseRate,
                                            'mrp'=>$NewMrp,
                                            'shop_id'=>'6',
                                            'selling_rate'=>$NewMrp,
                                            'tax_value'=>$tax_slab[1],
                                            'total_value'=>$NewTotalValue,
                                            'total_tax'=>$NewTotalTax,
                                            'vendor_id'=>'17',
                                        );

                                        if ($insert_stock = $this->master_model->add_data('shops_inventory', $data_shop_inventry)) {
                                            logs($user->id,$insert_stock,'Add','Add Shop Inventory');
                                            $data_shop_inventry_log['action']="ADD_INVENTORY"; $data_shop_inventry_log['shops_inventory_id']=$insert_stock;

                                            if ($insert_id = $this->master_model->add_data('shop_inventory_logs', $data_shop_inventry_log)) {
                                                logs($user->id,$insert_id,'Edit','Edit Shop Inventory');
                                                $return['res'] = 'success';
                                                $return['msg'] = 'Saved.';
                                            }
                                        }  
                                    }

                                // Apply offer code
                                  $getOffer = $this->master_model->getRow('coupons_and_offers',['id'=>$this->input->post('NewOffer')]);
                                   $offerdata = array(
                                    'offer_assosiated_id'     => $this->input->post('NewOffer'),
                                    'product_id'     => $result,
                                    'shop_id'     => '6',
                                    'offer_associated'     => $getOffer->value,
                                    'offer_upto'     => $getOffer->value,
                                    'discount_type'     => $getOffer->discount_type,
                                );
                                $this->offers_model->add_data('shops_coupons_offers',$offerdata);
                                logs($user->id,$result,'ADD','Apply Offer Product');
                            }
                        }
                    }
                    echo json_encode($return);
                    break;

            case 'add-property-value':
                if ($this->input->server('REQUEST_METHOD')=='POST') {

                    // if (!empty($_FILES['photo']['name'])) {
                    //     $config['file_name'] = rand(10000, 10000000000);
                    //     $config['upload_path']          = UPLOAD_PATH.'product/';
                    //     $config['allowed_types']        = 'gif|jpg|png';

                    //     $this->load->library('upload', $config);
                    //     $this->upload->initialize($config);
                    //     if ( ! $this->upload->do_upload('photo'))
                    //     {
                    //         $data = array('error'=>$this->upload->display_errors());
                    //         print_r($data); die;
                    //     }
                    //     else
                    //     {
                    //         $file = $this->upload->data();
                    //         $file_name = 'product/'.$file['file_name'];
                    //     }
                    // }
                    // else{
                    //     $file_name = '';
                    // }
                    $product_id = $this->input->post('pid');
                    $props_id = $this->input->post('props_id');
                    $data       = array(
                                    'product_id'    => $product_id,
                                    'props_id'      => $this->input->post('props_id'),
                                    'value'         => $this->input->post('value'),
                                    'value_id'      => $this->input->post('value_id'),
                                );
                    $data1['get_product_props']    = '';//$this->master_model->get_product_props($product_id,$props_id);
                    if($data1['get_product_props'])
                    {
                        if ($this->master_model->update_product_props($product_id,$props_id,$data)) {
                            $data['pid'] = $this->input->post('pid');
                            $pid = $this->input->post('pid');
                            $data['property_val']    = $this->master_model->get_property_val($pid);
                            $data['properties']    = $this->master_model->get_data('product_props_master','active','1');
                            $data['action_url']    = base_url().'products/add-property-value/'.$pid;
                            $data['form_id']       = uniqid();
                            $page                  = 'admin/master/products/property_value';
                            $this->load->view($page, $data);
                        }
                    }
                    else
                    {
                        if ($this->master_model->add_product_props($data)) {
                            $data['pid'] = $this->input->post('pid');
                            $pid = $this->input->post('pid');
                            $data['property_val']    = $this->master_model->get_property_val($pid);
                            $data['properties']    = $this->master_model->get_data('product_props_master','active','1');
                            $data['action_url']    = base_url().'products/add-property-value/'.$pid;
                            $data['form_id']       = uniqid();
                            $page                  = 'admin/master/products/property_value';
                            $this->load->view($page, $data);
                        }
                    }
                }
                else{
                    $data['pid'] = $p1;
                    $data['property_val']    = $this->master_model->get_property_val($p1);
                    $data['properties']    = $this->master_model->get_data('product_props_master','active','1');
                    $data['action_url']    = base_url().'products/add-property-value/'.$p1;
                    $data['form_id']       = uniqid();
                    $page                  = 'admin/master/products/property_value';
                
                    $this->load->view($page, $data);
                }

                break;

            case 'add-multi-buy':
                if ($this->input->server('REQUEST_METHOD')=='POST') {
                    
                    $product_id = $this->input->post('pid');
                    $data = array(
                        'product_id'    => $product_id,
                        'qty'           => $this->input->post('qty'),
                        'price'         => $this->input->post('price'),
                    );

                    if ($this->master_model->add_data('multi_buy',$data)) {
                        $data['pid'] = $this->input->post('pid');
                        $pid = $this->input->post('pid');
                        $data['multi_buys']    = $this->master_model->get_data('multi_buy','product_id',$pid);
                        $data['action_url']    = base_url().'products/add-multi-buy/'.$pid;
                        $data['form_id']       = uniqid();
                        $page                  = 'admin/master/products/product_multi_buy';
                        $this->load->view($page, $data);                            
                    }
                }
                else{
                    $data['pid'] = $p1;
                    $data['multi_buys']    = $this->master_model->get_data('multi_buy','product_id',$p1);
                    $data['action_url']    = base_url().'products/add-multi-buy/'.$p1;
                    $data['form_id']       = uniqid();
                    $page                  = 'admin/master/products/product_multi_buy';

                    $this->load->view($page, $data);
                }

                break;

            case 'update_multi_buy':                    

                $id = $this->input->post('id');
                $pid = $this->input->post('pid');
                $data       = array(
                    'qty'      => $this->input->post('qty'),
                    'price'    => $this->input->post('price'),
                );
                if($this->master_model->edit_data('multi_buy',$id,$data))
                {
                    $data['pid'] = $pid;                    
                    $data['multi_buys']    = $this->master_model->get_data('multi_buy','product_id',$pid);
                    $data['action_url']    = base_url().'products/add-multi-buy/'.$pid;
                    $data['form_id']       = uniqid();
                    $page                  = 'admin/master/products/product_multi_buy';
                
                    $this->load->view($page, $data);
                }
                break;

            case 'delete_multi_buy':
                $id = $p1;                    
                if($this->master_model->delete_data('multi_buy',$id))
                {                        
                    $data['pid']    = $p2;
                    $data['multi_buys']    = $this->master_model->get_data('multi_buy','product_id',$p2);
                    $data['action_url']    = base_url().'products/add-multi-buy/'.$p2;
                        $data['form_id']       = uniqid();
                        $page                  = 'admin/master/products/product_multi_buy';
                
                    $this->load->view($page, $data);
                }
                break;

                case 'set-property-default':
                if ($this->input->server('REQUEST_METHOD')=='POST') {                    
                    $id = $this->input->post('id');
                    $props_id = $this->input->post('props_id');
                    $pid = $this->input->post('pid');
                    
                    $data1['get_default_product_props'] = $this->master_model->get_default_product_props($pid,$props_id);
                    if($data1['get_default_product_props'])
                    {
                        $old_id = $data1['get_default_product_props'][0]->id;
                        $data = array(
                            'is_default' => 0,
                        );
                        $this->master_model->update_default_product_props($old_id,$data);
                        $data2 = array(
                            'is_default' => 1,
                        );
                        if ($this->master_model->update_default_product_props($id,$data2)) {
                            $data['pid'] = $this->input->post('pid');
                            $pid = $this->input->post('pid');
                            $data['property_val']    = $this->master_model->get_property_val($pid);
                            $data['properties']    = $this->master_model->get_data('product_props_master','active','1');
                            $data['action_url']    = base_url().'products/add-property-value/'.$pid;
                            $data['form_id']       = uniqid();
                            $page                  = 'admin/master/products/property_value';
                            $this->load->view($page, $data);
                        }
                    }
                    else
                    {
                        $data = array(
                            'is_default' => 1,
                        );
                        if ($this->master_model->update_default_product_props($id,$data)) {
                            $data['pid'] = $this->input->post('pid');
                            $pid = $this->input->post('pid');
                            $data['property_val']    = $this->master_model->get_property_val($pid);
                            $data['properties']    = $this->master_model->get_data('product_props_master','active','1');
                            $data['action_url']    = base_url().'products/add-property-value/'.$pid;
                            $data['form_id']       = uniqid();
                            $page                  = 'admin/master/products/property_value';
                            $this->load->view($page, $data);
                        }
                    }
                }
                else{
                    $data['pid'] = $p1;
                    $data['property_val']    = $this->master_model->get_property_val($p1);
                    $data['properties']    = $this->master_model->get_data('product_props_master','active','1');
                    $data['action_url']    = base_url().'products/add-property-value/'.$p1;
                    $data['form_id']       = uniqid();
                    $page                  = 'admin/master/products/property_value';
                
                    $this->load->view($page, $data);
                }

                break;
           
                        case 'flags':
                            $data['pid'] = $p1;
                            // $shop_id  = $this->input->post('shop_id');
                            // $data['flags']    = $this->master_model->get_flags_data($p1,$shop_id);
                            // print_r($data['flags']);
                            // // $data['flags']    = $this->master_model->get_flags_data1($p1);
                            // if(!empty($data['flags_data']))
                            // {
                            //     $business_id = $data['flags']->business_id;  
                            //     $data['shops']  = $this->master_model->get_data('shops','business_id',$business_id);
                                
                            // }
                            $data['business']  = $this->master_model->view_data('business');
                            $data['action_url']    = base_url().'products/add-flags/'.$p1;
                            $data['form_id']       = uniqid();
                            $page                  = 'admin/master/products/product_flags';
                            $this->load->view($page, $data);
                        
                        break;
                        case 'check_flag_existence':
                            $pid = $this->input->post('pid');
                            $shop_id  = $this->input->post('shop_id');
                            $data['flags']    = $this->master_model->get_flags_data($pid,$shop_id);
                            echo json_encode(array('data'=>$data['flags']));
                           
                            // // $data['flags']    = $this->master_model->get_flags_data1($p1);
                            // if(!empty($data['flags_data']))
                            // {
                            //     $business_id = $data['flags']->business_id;  
                            //     $data['shops']  = $this->master_model->get_data('shops','business_id',$business_id);
                                
                            // }
                        
                        break;
                    case 'add-flags':
                        $return['res'] = 'error';
                        $return['msg'] = 'Not Saved.';
                        if ($this->input->server('REQUEST_METHOD')=='POST') {
                            $product_id = $this->input->post('pid');
                            $shop_id = $this->input->post('shop_id');
                            if($this->input->post('is_cod'))
                            {
                                $is_cod = '1';
                            }
                            else
                            {
                                $is_cod = '0';
                            }
                            if($this->input->post('is_cancellation'))
                            {
                                $is_cancellation = '1';
                            }
                            else
                            {
                                $is_cancellation = '0';
                            }
                            if($this->input->post('is_featured'))
                            {
                                $is_featured = '1';
                            }
                            else
                            {
                                $is_featured = '0';
                            }
                            $data       = array(
                                            'product_id'    => $product_id,
                                            'shop_id'    => $shop_id,
                                            'is_cod'      => $is_cod,
                                            'is_cancellation'         => $is_cancellation,
                                            'is_featured'         => $is_featured,
                                            'delivery_period'         => $this->input->post('delivery_period'),
                                            'cancellation_period'         => $this->input->post('cancellation_period'),
                                            'cancellation_content'         => $this->input->post('cancellation_content'),
                                        );
                            $flag = $this->input->post('flag');
                            if($flag == '1')
                            {
                                if ($this->master_model->edit_product_flag($product_id,$shop_id,$data)) {
                                    $return['res'] = 'success';
                                    $return['msg'] = 'Updated.';
                                }
                                
                            }
                            else if($flag == '0')
                            {
                                if ($this->master_model->add_data('product_flags',$data)) {
                                    $return['res'] = 'success';
                                    $return['msg'] = 'Saved.';
                                }
                            }
                        }
                        
                        echo json_encode($return);
                        break;
                    
                        case 'plan_type':
                            $data['pid'] = $p1;
                            $data['business']  = $this->master_model->view_data('business');
                            $data['plan_types']  = $this->master_model->get_data('subscriptions_plan_types','active','1');
                            $data['action_url']    = base_url().'products/add-plan-type/'.$p1;
                            $page                  = 'admin/master/products/plan_type';
                            $this->load->view($page, $data);
                        
                        break;
                        case 'check_plan_type_existence':
                            $plan_id_data = array();
                            $plan_type_id_data = array();
                            $pid = $this->input->post('pid');
                            $shop_id  = $this->input->post('shop_id');
                            $plans    = $this->subscription_model->get_plan_data($pid,$shop_id);
                            $plan_types  = $this->master_model->get_data('subscriptions_plan_types','active','1');
                            // print_r($plan_types);
                            foreach($plans as $plan)
                            {
                                $data['plan_id'] = $plan['plan_id'];
                                array_push($plan_id_data,$data);
                            }
                            foreach($plan_types as $plan_type)
                            {
                                $data1['plan_type_id'] = $plan_type->id;
                                array_push($plan_type_id_data,$data1);
                            }
                            echo json_encode(array('data'=>$plan_id_data,'data2'=>$plan_type_id_data));
                        
                        break;
                        case 'add-plan-type':
                            $return['res'] = 'error';
                            $return['msg'] = 'Not Saved.';
                            if ($this->input->server('REQUEST_METHOD')=='POST') {
                                $product_id = $this->input->post('pid');
                                $shop_id = $this->input->post('shop_id');
                                $plan_ids = $this->input->post('plan_id');
                                $data  = array(
                                        'product_id'    => $product_id,
                                        'shop_id'    => $shop_id,
                                    );
                                $flag = $this->input->post('flag');
                                if($flag == '1')
                                {
                                    if ($this->subscription_model->edit_product_plan_type($product_id,$shop_id,$plan_ids)) {
                                        $return['res'] = 'success';
                                        $return['msg'] = 'Updated.';
                                    }
                                    
                                }
                                else if($flag == '0')
                                {
                                    if ($this->subscription_model->add_product_plan_type($product_id,$shop_id,$plan_ids)) {
                                        $return['res'] = 'success';
                                        $return['msg'] = 'Saved.';
                                    }
                                }
                            }
                            
                            echo json_encode($return);
                            break;
                case 'update_prop_value':                    

                    $id =     $this->input->post('id');
                    $pid =     $this->input->post('pid');
                    $data       = array(
                        'props_id'      => $this->input->post('props_id'),
                        'value'         => $this->input->post('propvalue'),
                        'value_id'      => $this->input->post('value_id'),
                    );
                    if($this->master_model->edit_data('product_props',$id,$data))
                    {
                        $data['pid']    = $pid;
                        $data['property_val']    = $this->master_model->get_property_val($pid);
                        // $data['property_val']    = $this->master_model->get_data('product_props','product_id',$p2);
                        $data['properties']    = $this->master_model->view_data('product_props_master');
                        $data['action_url']    = base_url().'products/add-property-value/'.$p2;
                        $data['form_id']       = uniqid();
                        $page                  = 'admin/master/products/property_value';
                    
                        $this->load->view($page, $data);
                    }
                    break;

                case 'delete_prop_val':
                    $id = $p1;
                    // $data_prop = $this->master_model->get_property_val_by_id($id);
                    // unlink(UPLOAD_PATH.$data_prop[0]->pic);
                    // print_r($data_prop[0]->pic); die;
                    if($this->master_model->delete_prop_val($id))
                    {                        
                        $data['pid']    = $p2;
                        $data['property_val']    = $this->master_model->get_property_val($p2);
                        // $data['property_val']    = $this->master_model->get_data('product_props','product_id',$p2);
                        $data['properties']    = $this->master_model->view_data('product_props_master');
                        $data['action_url']    = base_url().'products/add-property-value/'.$p2;
                        $data['form_id']       = uniqid();
                        $page                  = 'admin/master/products/property_value';
                    
                        $this->load->view($page, $data);
                    }
                    break;
    


            case 'property-image':

                if ($this->input->server('REQUEST_METHOD')=='POST') {
                    $return['res'] = 'error';
                    $return['msg'] = 'Not Saved!';
                    
                    $product_id = $p1;
                    $data       = array(
                                    'product_id'    => $product_id,
                                    'props_id'      => $this->input->post('props_id'),
                                    'value'         => $this->input->post('value'),
                                );
                    if ($this->master_model->product_img_upload($p1)) {
                        $return['res'] = 'success';
                        $return['msg'] = 'Saved.';
                    } 
                    echo json_encode($return);
                }
                else{
                    $data['pid'] = $p1;
                    $data['images']        = $this->master_model->product_img($p1);
                    $data['action_url']    = base_url().'products/property-image/'.$p1;
                    $data['form_id']       = uniqid();
                    $page                  = 'admin/master/products/property_images';
                    
                    // echo "<pre>";
                    // print_r($p1);
                    // echo "</pre>";
                    // die();

                    $this->load->view($page, $data);
                }

                break;
                case 'add_image':
                    $id = $this->input->post('pid');
                    $imageCount = count($_FILES['file']['name']);
                    if (!empty($imageCount)) {
                        for ($i = 0; $i < $imageCount; $i++) {
                            $config['file_name'] = date('Ymd') . rand(1000, 1000000);
                            $config['upload_path'] = UPLOAD_PATH.'product/';
                            $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            $_FILES['files']['name'] = $_FILES['file']['name'][$i];
                            $_FILES['files']['type'] = $_FILES['file']['type'][$i];
                            $_FILES['files']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                            $_FILES['files']['size'] = $_FILES['file']['size'][$i];
                            $_FILES['files']['error'] = $_FILES['file']['error'][$i];

                            if ($this->upload->do_upload('files')) {
                                $imageData = $this->upload->data();
                               if($_FILES['files']['type']=='image/webp')
                                    {
                                       echo UPLOAD_PATH.'product/'.$imageData['file_name'];
                                            $img =  imagecreatefromwebp(UPLOAD_PATH.'product/'. $imageData['file_name']);
                                            imagepalettetotruecolor($img);
                                            imagealphablending($img, true);
                                            imagesavealpha($img, true);
                                            imagewebp($img, UPLOAD_PATH.'product/thumbnail/'. $imageData['file_name'], 60);
                                            imagedestroy($img);
                                    }
                                    else
                                    {
                                        $config2 = array(
                                            'image_library' => 'gd2', //get original image
                                            'source_image' =>   UPLOAD_PATH.'product/'. $imageData['file_name'],
                                            'width' => 640,
                                            'height' => 360,
                                            'new_image' =>  UPLOAD_PATH.'product/thumbnail/'. $imageData['file_name'],
                                        );
                                        $this->load->library('image_lib');
                                        $this->image_lib->initialize($config2);
                                        $this->image_lib->resize();
                                        $this->image_lib->clear();
                                    }
                                $images[] = "product/" . $imageData['file_name'];
                                $images2[] = "product/thumbnail/" . $imageData['file_name'];
                            }
                        }
                    }
                    if (!empty($images)) {      
                        foreach (array_combine($images, $images2) as $file => $file2) {
                            $file_data = array(
                                'img' => $file,
                                'thumbnail' => $file2,
                                'item_id' => $id
                            );
                            $this->db->insert('products_photo', $file_data);
                        }                        
                    }
                break;

            case 'delete_product':
                $id = $p1;
                // $data1['images']        = $this->master_model->product_img($id);
                // print_r($data1['images']->img);
                // die();
                if($this->master_model->delete_product($id))
                {
                    $data['search'] = '';
                if (@$_POST['search']) {
                    $data['search'] = $_POST['search'];
                }
                $data['cat_id'] = '';
                if (@$_POST['cat_id']) {
                    $data['cat_id'] = $_POST['cat_id'];
                }
                
                $this->load->library('pagination');
                $config = array();
                $config["base_url"]         = base_url()."products/tb/";
                $config["total_rows"]       = count($this->master_model->products());
                $data['total_rows']         = $config["total_rows"];
                $config["per_page"]         = 20;
                $config["uri_segment"]      = 3;
                $config['attributes']       = array('class' => 'pag-link');
                $config['full_tag_open']    = "<div class='pag'>";
                $config['full_tag_close']   = "</div>";
                $config['first_link']       = '&lt;&lt;';
                $config['last_link']        = '&gt;&gt;';
                $this->pagination->initialize($config);
                $data["links"]              = $this->pagination->create_links();
                $data['page']               = $page = ($p2!=null) ? $p2 : 0;
                $data['per_page']           = $config["per_page"];
                $data['products']           = $this->master_model->products($config["per_page"],$page);
                $data['update_url']         = base_url().'products/create/';
                $data['duplicate_url']         = base_url().'products/duplicate/';
                $data['map_url']             = base_url().'products/map-product/';
                $data['pv_url']             = base_url().'products/add-property-value/';
                $data['pimg_url']           = base_url().'products/property-image/';
                $page                       = 'admin/master/products/tb';


                $data['properties']         = $this->master_model->view_data('product_props_master');
                $data['unit_type']          = $this->master_model->view_data('unit_master');
                $data['categories']         = $this->master_model->view_data('products_category');

                $data['parent_cat'] = $this->master_model->get_data('products_category','is_parent','0');
                $this->load->view($page, $data);
                }
                break;

                case 'map_product':
					$pid= $this->input->post('pid');

					$product_id= $this->input->post('product_id');
			
                    $data1['flg'] = '1';

                    $data1['pid'] = $pid;
					$data = array(
						'pro_id'     => $product_id,
						'map_pro_id'     => $pid,
					);
					$rev_data = array(
						'pro_id'     => $pid,
						'map_pro_id'     => $product_id,
					);
                    $getmapdata =  $this->db->where(['pro_id' =>$product_id])->get('products_mapping')->result();
                    if(!empty($getmapdata))
                    {
                        foreach($getmapdata as $map_data)
                        {
                            $rev_map_data = array(
                                'map_pro_id'     => $map_data->map_pro_id,
                                'pro_id'     => $pid,
                            );
                            $map_data = array(
                                'pro_id'     => $map_data->map_pro_id,
                                'map_pro_id'     => $pid,
                            );
                            $this->master_model->add_data('products_mapping',$map_data);
                            $this->master_model->add_data('products_mapping',$rev_map_data);
                        }
                    }
                    if($this->master_model->add_data('products_mapping',$data) && $this->master_model->add_data('products_mapping',$rev_data))			
                    {
                        $this->load->view('admin/master/products/map_unmap',$data1);
            
                    }
					break;
               case 'upload-products':
                $data['menu_id']        = $p1;
                $data['title']          = 'Add New Purchase';
                $data['action_url']         = base_url().'products/import_excel';
                $page                       = 'admin/master/products/upload-products';
                $this->header_and_footer($page, $data); 
                break;
                // case 'import_excel':
                //     $return['res'] = 'error';
                //     $return['msg'] = 'Not Saved!';
                //      if ($this->input->server('REQUEST_METHOD')=='POST') { 
                //     $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                //     if(isset($_FILES['import_file']['name']) && in_array($_FILES['import_file']['type'],    $file_mimes)) {
                //         $arr_file = explode('.', $_FILES['import_file']['name']);
                //         $extension = end($arr_file);
                //         if('csv' == $extension) {
                //             $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                //         } else {
                //             $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                //         }
                //         $spreadsheet = $reader->load($_FILES['import_file']['tmp_name']);
                //         $sheetData = $spreadsheet->getActiveSheet()->toArray();
                //         $flag = TRUE;
                //         $createdProductIds = array(); 
                //         $newCount = $count = $count2=0;
                //         foreach ($sheetData as $data) {
                //             if ($flag) {
                //                 $flag = false; 
                //                 continue; 
                //             }
                //             if (!empty($data[1]) && !empty($data[2])&& !empty($data[8])&& !empty($data[9])&& !empty($data[10]) && $newCount > 3) {
                //                 $productName = trim($data[1]);
                //                 $UnitName = trim($data[2]);
                //                 $CompanyName = trim($data[8]);
                //                 $dpco_nondpco = trim($data[9]);
                //                 $hsn = trim($data[10]);
                //                 $checkExistCompany=$this->master_model->checkExistCompany($CompanyName);
                //                 if($checkExistCompany)
                //                 {
                //                     $CompanyID = $this->master_model->getRow('brand_master',['name'=>$CompanyName]);
                //                     $Company_id = $CompanyID->id;
                //                 }else{
                //                     $dataCompany = array(
                //                      'name'=>$CompanyName,
                //                      'active'=>'1',
                //                     );
                //                     $Company_id = $this->master_model->Save('brand_master',$dataCompany);
                //                 }
                               
                //                 $UnitID = $this->master_model->getRow('unit_master',['name'=>$UnitName]);
                //                 $dpcoID = $this->master_model->getRow('dpco',['title'=>$dpco_nondpco]);
                                 
                //                 $checkExistProduct=$this->master_model->checkExistProduct($productName);
                //                 if($checkExistProduct)
                //                 {
                //                     $product_array1 = array(
                //                         'name'=>$productName,
                //                         'search_keywords'=>$productName,
                //                         'unit_type'=>$UnitID->name,
                //                         'unit_type_id'=>$UnitID->id,
                //                         'unit_value'=>'1',
                //                         'sku'=>$hsn,
                //                         'brand_id'=>$Company_id,
                //                         'dpco'=>$dpcoID->id,
                //                      );
     
                //                      $this->db->update('products_subcategory',$product_array1,['id'=>$checkExistProduct]);
                //                      if($checkExistProduct)
                //                      {
                //                         $prod_id = $checkExistProduct;
                //                          $inventory_array1 = array(
                //                              'product_id'=>$checkExistProduct,
                //                              'qty'=>'0',
                //                              'purchase_rate'=>'0.00',
                //                              'tax_value'=>'0',
                //                              'total_value'=>'0.00',
                //                              'total_tax'=>'0.00',
                //                              'mrp'=>'0.00',
                //                              'selling_rate'=>'0.00',
                //                              'shop_id'=>'6',
                //                           );
                //                           $Inv = $this->master_model->getRow('shops_inventory',['product_id'=>$checkExistProduct]);
                //                           $this->db->update('shops_inventory',$inventory_array1,['id'=>@$Inv->id]);
                //                           $inventory__logs_array1 = array(
                //                              'product_id'=>$checkExistProduct,
                //                              'qty'=>'0',
                //                              'purchase_rate'=>'0.00',
                //                              'tax_value'=>'0',
                //                              'total_value'=>'0.00',
                //                              'total_tax'=>'0.00',
                //                              'mrp'=>'0.00',
                //                              'selling_rate'=>'0.00',
                //                              'shop_id'=>'6',
                //                              'shops_inventory_id'=>@$Inv->id,
                //                              'action'=>'ADD_INVENTORY'
                //                           );
                //                           $this->db->insert('shop_inventory_logs',$inventory__logs_array1);
                //                      }
                //                      $count2++;
                //                 }{ 
                //                 $productCode = $this->generateProductCode();
                //                 $product_array = array(
                //                    'name'=>$productName,
                //                    'search_keywords'=>$productName,
                //                    'unit_type'=>$UnitID->name,
                //                    'unit_type_id'=>$UnitID->id,
                //                    'unit_value'=>'1',
                //                    'sku'=>$hsn,
                //                    'brand_id'=>$Company_id,
                //                    'dpco'=>$dpcoID->id,
                //                    'product_code' => $productCode
                //                 );

                //                 $this->db->insert('products_subcategory',$product_array);
                //                 $insert_id = $this->db->insert_id();
                //                 $prod_id = $insert_id;
                //                 if($insert_id)
                //                 {
                //                     $inventory_array = array(
                //                         'product_id'=>$insert_id,
                //                         'qty'=>'0',
                //                         'purchase_rate'=>'0.00',
                //                         'tax_value'=>'0',
                //                         'total_value'=>'0.00',
                //                         'total_tax'=>'0.00',
                //                         'mrp'=>'0.00',
                //                         'selling_rate'=>'0.00',
                //                         'shop_id'=>'6',
                //                      );
                //                      $this->db->insert('shops_inventory',$inventory_array);
                //                      $inventory_id = $this->db->insert_id();
                //                      $inventory__logs_array = array(
                //                         'product_id'=>$insert_id,
                //                         'qty'=>'0',
                //                         'purchase_rate'=>'0.00',
                //                         'tax_value'=>'0',
                //                         'total_value'=>'0.00',
                //                         'total_tax'=>'0.00',
                //                         'mrp'=>'0.00',
                //                         'selling_rate'=>'0.00',
                //                         'shop_id'=>'6',
                //                         'shops_inventory_id'=>$inventory_id,
                //                         'action'=>'ADD_INVENTORY'
                //                      );
                //                      $this->db->insert('shop_inventory_logs',$inventory__logs_array);
                //                 }
                //                 $count++;
                //             }
                //               array_push($createdProductIds,$prod_id);
                             
                //             }
                            
                //             $newCount++;
                //         }


                //         $table = '<div class="row">

                //         <div id="datatable">

                //         <div id="grid_table">

                //             <table class="jsgrid-table">

                //                 <tr class="jsgrid-header-row">

                //                     <th class="jsgrid-header-cell jsgrid-align-center">S.No.</th>

                //                     <th class="jsgrid-header-cell jsgrid-align-center">Product Name</th>

                //                     <th class="jsgrid-header-cell jsgrid-align-center">Unit</th>

                //                     <th class="jsgrid-header-cell jsgrid-align-center">Company</th>

                //                     <th class="jsgrid-header-cell jsgrid-align-center">DPCO/NON DPCO</th>
                //                     <th class="jsgrid-header-cell jsgrid-align-center">HSN CODE</th>

                //                 </tr>';

                //     $x = 1;

                //     foreach ($createdProductIds as $ID) {
                //         $rs = $this->master_model->getRow('products_subcategory', ['id' => $ID]);
                //         $dpco = $this->master_model->getRow('dpco', ['id' => @$rs->dpco]);
                //         $brans_master = $this->master_model->getRow('brand_master', ['id' => @$rs->brand_id]);
                //         $table .= '<tr class="jsgrid-filter-row">
                //                         <th class="jsgrid-cell jsgrid-align-center">' . $x . '</th>

                //                         <td class="jsgrid-cell jsgrid-align-center">' . @$rs->name . '</td>

                //                         <td class="jsgrid-cell jsgrid-align-center">' . @$rs->unit_type . '</td>

                //                         <td class="jsgrid-cell jsgrid-align-center">' . @$brans_master->name . '</td>

                //                         <td class="jsgrid-cell jsgrid-align-center">' . @$dpco->title . '</td>
                //                           <td class="jsgrid-cell jsgrid-align-center">' . @$rs->sku . '</td>

                //                     </tr>';

                //         $x++;

                //     }



                //     $table .= '</div>';
                //        $return['table']=$table;
                //        $return['res']='success';
                //        $return['msg'] = $count . ' Product Items have been imported successfully! or '. $count2.' Product has been updated! .';
                //     }
                //         echo json_encode($return);
                //     }
                // break; 

                    case 'import_excel':
                        $return = ['res' => 'error', 'msg' => 'Not Saved!'];
                        
                        if ($this->input->server('REQUEST_METHOD') == 'POST') { 
                            $file_mimes = [
                                'text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream',
                                'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv',
                                'application/excel', 'application/vnd.msexcel', 'text/plain', 
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            ];
                            
                            if (isset($_FILES['import_file']['name']) && in_array($_FILES['import_file']['type'], $file_mimes)) {
                                $arr_file = explode('.', $_FILES['import_file']['name']);
                                $extension = end($arr_file);
                                $reader = ($extension == 'csv') ? new \PhpOffice\PhpSpreadsheet\Reader\Csv() : new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                                
                                $spreadsheet = $reader->load($_FILES['import_file']['tmp_name']);
                                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                                $flag = true;
                                $createdProductIds = [];
                                $newCount = $count = $count2 = 0;

                                foreach ($sheetData as $row) {
                                    if ($flag) {
                                        $flag = false;
                                        continue;
                                    }

                                    if (!empty($row[1]) && !empty($row[2]) && !empty($row[8]) && !empty($row[9]) && !empty($row[10]) && $newCount > 3) {
                                        $productName = trim($row[1]);
                                        $unitName = trim($row[2]);
                                        $companyName = trim($row[8]);
                                        $dpcoNondpco = trim($row[9]);
                                        $hsn = trim($row[10]);

                                        // Check and get or create Company ID
                                        $companyID = $this->master_model->getOrCreateCompany($companyName);

                                        // Check and get Unit ID
                                        $unitID = $this->master_model->getRow('unit_master', ['name' => $unitName]);

                                        // Check and get DPCO ID
                                        $dpcoID = $this->master_model->getRow('dpco', ['title' => $dpcoNondpco]);

                                        // Check if product exists
                                        $existingProductID = $this->master_model->checkExistProduct($productName);

                                        if ($existingProductID) {
                                            $this->updateExistingProduct($existingProductID, $productName, $unitID, $hsn, $companyID, $dpcoID);
                                            $prod_id = $existingProductID;
                                            $count2++;
                                        } else {
                                            $prod_id = $this->createNewProduct($productName, $unitID, $hsn, $companyID, $dpcoID);
                                            $count++;
                                        }

                                        $createdProductIds[] = $prod_id;
                                    }

                                    $newCount++;
                                }

                                $return['table'] = $this->generateProductTable($createdProductIds);
                                $return['res'] = 'success';
                                $return['msg'] = "$count Product Items have been imported successfully! or $count2 Product has been updated!";
                            }
                        }

                        echo json_encode($return);
                        break;
               case 'export_to_excel':
                        $parent_id = 'null';
                        $parent_cat_id = 'null';
                        $child_cat_id = 'null';
                        $dpco_id = 'null';
                        $company_id = 'null';
                        $search = 'null';
                        $pro_id = array();
                        if($this->input->post('parent_id'))
                        {
                            $parent_id = $this->input->post('parent_id');
                        }
                        if($this->input->post('parent_cat_id'))
                        {
                            $parent_cat_id = $this->input->post('parent_cat_id');
                        }
                        if($this->input->post('child_cat_id'))
                        {
                            $child_cat_id = $this->input->post('child_cat_id');
                        }
                        if($this->input->post('dpco_id'))
                        {
                            $dpco_id = $this->input->post('dpco_id');
                        }
                        if($this->input->post('company_id'))
                        {
                            $company_id = $this->input->post('company_id');
                        }
                        if($this->input->post('search'))
                        {
                            $search = $this->input->post('search');
                        }

                        if (@$_POST['parent_id']) {
                            $pro_id = array();
                            $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['parent_id']])->result();
                            foreach($get_proid as $row){
                                $pro_id[] = $row->pro_id;
                            }
                        }
    
                        if (@$_POST['parent_cat_id']) {
                            $pro_id = array();
                            $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['parent_cat_id']])->result();
                            foreach($get_proid as $row){
                                $pro_id[] = $row->pro_id;
                            }
                        }
                        if (@$_POST['child_cat_id']) {
                            $pro_id = array();
                            $get_proid = $this->db->get_where('cat_pro_maps',['cat_id' => $_POST['child_cat_id']])->result();
                            foreach($get_proid as $row){
                                $pro_id[] = $row->pro_id;
                            }
                        } 
                        $result = $this->master_model->report_excel($pro_id,$company_id,$dpco_id,$search);
                        // Initialize CSV data with column headers
                        $csvData = "CODE,Product Name,Unit,Company,DPCO/NON DPCO,HSN CODE\n";

                        $i = 1;
                        foreach ($result as $row) {

                            $csvData .= $row->product_code . ',';
                            $csvData .= $row->name . ',';
                            $csvData .= $row->unit_type . ',';
                            $csvData .= $row->compamy_name . ',';
                            $csvData .= $row->title . ',';
                            $csvData .= $row->sku . "\n";
                        }

                        // Set headers
                        header('Content-Type: text/csv');
                        header('Content-Disposition: attachment;filename="Product_Data_Export.csv"'); // Set the filename here
                        header('Cache-Control: max-age=0');

                        // Output CSV data
                        echo $csvData;
                    break;

            default:
                # code...
                break;
        }
    }

    
    public function updateExistingProduct($productID, $productName, $unitID, $hsn, $companyID, $dpcoID) {
        $productData = [
            'name' => $productName,
            'search_keywords' => $productName,
            'unit_type' => $unitID->name,
            'unit_type_id' => $unitID->id,
            'unit_value' => '1',
            'sku' => $hsn,
            'brand_id' => $companyID,
            'dpco' => $dpcoID->id
        ];
        $this->db->update('products_subcategory', $productData, ['id' => $productID]);
    
        $inventoryData = [
            'product_id' => $productID,
            'qty' => '0',
            'purchase_rate' => '0.00',
            'tax_value' => '0',
            'total_value' => '0.00',
            'total_tax' => '0.00',
            'mrp' => '0.00',
            'selling_rate' => '0.00',
            'shop_id' => '6',
        ];
        $existingInventory = $this->master_model->getRow('shops_inventory', ['product_id' => $productID]);
        $this->db->update('shops_inventory', $inventoryData, ['id' => $existingInventory->id]);
    
        $inventoryLogData = [
            'product_id' => $productID,
            'qty' => '0',
            'purchase_rate' => '0.00',
            'tax_value' => '0',
            'total_value' => '0.00',
            'total_tax' => '0.00',
            'mrp' => '0.00',
            'selling_rate' => '0.00',
            'shop_id' => '6',
            'shops_inventory_id' => $existingInventory->id,
            'action' => 'ADD_INVENTORY'
        ];
        $this->db->insert('shop_inventory_logs', $inventoryLogData);
    }
    
    public function createNewProduct($productName, $unitID, $hsn, $companyID, $dpcoID) {
        $productCode = $this->generateProductCode();
        $productData = [
            'name' => $productName,
            'search_keywords' => $productName,
            'unit_type' => $unitID->name,
            'unit_type_id' => $unitID->id,
            'unit_value' => '1',
            'sku' => $hsn,
            'brand_id' => $companyID,
            'dpco' => $dpcoID->id,
            'product_code' => $productCode
        ];
        $this->db->insert('products_subcategory', $productData);
        $insert_id = $this->db->insert_id();
    
        $inventoryData = [
            'product_id' => $insert_id,
            'qty' => '0',
            'purchase_rate' => '0.00',
            'tax_value' => '0',
            'total_value' => '0.00',
            'total_tax' => '0.00',
            'mrp' => '0.00',
            'selling_rate' => '0.00',
            'shop_id' => '6',
        ];
        $this->db->insert('shops_inventory', $inventoryData);
        $inventory_id = $this->db->insert_id();
    
        $inventoryLogData = [
            'product_id' => $insert_id,
            'qty' => '0',
            'purchase_rate' => '0.00',
            'tax_value' => '0',
            'total_value' => '0.00',
            'total_tax' => '0.00',
            'mrp' => '0.00',
            'selling_rate' => '0.00',
            'shop_id' => '6',
            'shops_inventory_id' => $inventory_id,
            'action' => 'ADD_INVENTORY'
        ];
        $this->db->insert('shop_inventory_logs', $inventoryLogData);
    
        return $insert_id;
    }
    
    public function generateProductTable($productIds) {
        $table = '<div class="row">
                    <div id="datatable">
                        <div id="grid_table">
                            <table class="jsgrid-table">
                                <tr class="jsgrid-header-row">
                                    <th class="jsgrid-header-cell jsgrid-align-center">S.No.</th>
                                    <th class="jsgrid-header-cell jsgrid-align-center">Product Name</th>
                                    <th class="jsgrid-header-cell jsgrid-align-center">Unit</th>
                                    <th class="jsgrid-header-cell jsgrid-align-center">Company</th>
                                    <th class="jsgrid-header-cell jsgrid-align-center">DPCO/NON DPCO</th>
                                    <th class="jsgrid-header-cell jsgrid-align-center">HSN CODE</th>
                                </tr>';
    
        $x = 1;
    
        foreach ($productIds as $ID) {
            $product = $this->master_model->getRow('products_subcategory', ['id' => $ID]);
            $dpco = $this->master_model->getRow('dpco', ['id' => @$product->dpco]);
            $brand = $this->master_model->getRow('brand_master', ['id' => @$product->brand_id]);
    
            $table .= '<tr class="jsgrid-filter-row">
                            <th class="jsgrid-cell jsgrid-align-center">' . $x . '</th>
                            <td class="jsgrid-cell jsgrid-align-center">' . @$product->name . '</td>
                            <td class="jsgrid-cell jsgrid-align-center">' . @$product->unit_type . '</td>
                            <td class="jsgrid-cell jsgrid-align-center">' . @$brand->name . '</td>
                            <td class="jsgrid-cell jsgrid-align-center">' . @$dpco->title . '</td>
                            <td class="jsgrid-cell jsgrid-align-center">' . @$product->sku . '</td>
                        </tr>';
            $x++;
        }
    
        $table .= '</table></div></div></div>';
        return $table;
    }

    private function generateProductCode() {
        $this->db->select('product_code');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('products_subcategory');
    
        if ($query->num_rows() > 0) {
            $last_code = $query->row()->product_code;
            $number = intval(substr($last_code, 2)) + 1;
            return 'RM' . str_pad($number, 5, '0', STR_PAD_LEFT);
        } else {
            return 'RM00001';
        }
    }
    
    public function categories($action=null,$p1=null,$p2=null,$p3=null)
    {
        $data['user']  = $user         = checkLogin();
        switch ($action) {
            case null:
                $data['menu_id'] = $this->uri->segment(2);
                $data['title'] = 'Categories';
                $data['parent_cat'] = $this->master_model->get_data('products_category','is_parent','0');
                $data['tax_slabs'] = $this->master_model->view_data('tax_slabs');
                $data['tb_url']         = base_url().'categories/tb';
                $data['new_url']        = base_url().'categories/create';
                $page = 'admin/master/categories/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
                $this->header_and_footer($page, $data);
                break;

                case 'tb':
                    $data['cat_id'] = '';
                    $data['parent_id'] = '';
                    //below variable section used for models and other places
                    $cat_id='null';
                    $parent_id='null';
                    //get section intiliazation
                    if($p2!=null)
                    {
                        $data['parent_id'] = $p1;
                        $data['cat_id'] = $p2;
                        $parent_id = $p1;
                        $cat_id = $p2;
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $p1 , 'is_deleted' => 'NOT_DELETED'])->result();
                    }
                    //end of section
    
                    if (@$_POST['parent_id']) {
                        $data['parent_id'] = $_POST['parent_id'];
                        $parent_id = $_POST['parent_id'];
                    }
                    if (@$_POST['cat_id']) {
                        $data['cat_id'] = $_POST['cat_id'];
                        $cat_id = $_POST['cat_id'];
                        $data['sub_cat'] = $this->db->get_where('products_category',['is_parent' => $_POST['parent_id'] , 'is_deleted' => 'NOT_DELETED'])->result();
                    }
                    $this->load->library('pagination');
                    $config = array();
                    $config["base_url"]         = base_url()."categories/tb/";
                    $config["total_rows"]       = count($this->master_model->get_parent_cat($parent_id,$cat_id));
                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 5;
                    $config["uri_segment"]      = 3;
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    $data['page']               = $page = ($p1!=null) ? $p1 : 0;
                    
                    $data['parent_cat'] = $this->master_model->get_parent_cat($parent_id,$cat_id,$config["per_page"],$page);
                    $data['parent_cat_list'] = $this->master_model->get_parent_cat_list();
                    $data['categories'] = $this->master_model->get_categories($parent_id,$cat_id);
                    $data['per_page']           = $config["per_page"];
                    $data['update_url']         = base_url().'categories/create/';
                    $data['image_url']         = base_url().'categories/view_image/';
                    $page                       = 'admin/master/categories/tb';
    
    
    
                    $data['tax_slabs'] = $this->master_model->view_data('tax_slabs');
    
                    $this->load->view($page, $data);
                    break;
                case 'create':
                    $data['remote']             = base_url().'master-data/remote/category/';
                    $data['action_url']         = base_url().'categories/save';
                    $data['tax_slabs'] = $this->master_model->get_data('tax_slabs','active','1');
                    $page                       = 'admin/master/categories/create';
                    if ($p1!=null) {
                        $data['remote']             = base_url().'master-data/remote/category/';
                        $data['action_url']     = base_url().'categories/save/'.$p1;
                        $data['value']          = $this->master_model->category($p1);
                        $data['tax_slabs'] = $this->master_model->view_data('tax_slabs');

                        $data['remote']         = base_url().'master-data/remote/products/'.$p1;
                        $page                   = 'admin/master/categories/update';
                    }
                    $data['properties']         = $this->master_model->view_data('product_props_master');
                    $data['unit_type']          = $this->master_model->view_data('unit_master');
                    
                    $data['parent_cat'] = $this->master_model->get_parent_category();
                    $data['parent_id'] = $this->master_model->get_parent_id();
                    $data['form_id']            = uniqid();
                    
                   
                    $this->load->view($page, $data);
                    break;
                case 'view_image':
                    $data['category'] = $this->master_model->get_row_data('products_category','id',$p1);
                    $page                       = 'admin/master/categories/view_image';
                    
                    $this->load->view($page,$data);
                    break;

                    case 'save':
                        $id = $p1;
                        $return['res'] = 'error';
                        $return['msg'] = 'Not Saved!';

                        if ($this->input->server('REQUEST_METHOD')=='POST') {
                            if ($id!=null) {
                                $tax_slab = explode(",",$this->input->post('tax_id'));
                                if($this->input->post('tax_id')=="")
                                    $tax_slab="0,0.0";
                                if($this->input->post('parent_id') && !$this->input->post('sub_cat_id'))
                                {
                                    $is_parent = $this->input->post('parent_id');
                                }
                                else if($this->input->post('parent_id') && $this->input->post('sub_cat_id'))
                                {
                                    $is_parent = $this->input->post('sub_cat_id');
                                }
                                else
                                {
                                    $is_parent = '0';
                                }
                                $data = array(
                                    'tax_id'     => $tax_slab[0],
                                    'tax_value'     => $tax_slab[1],
                                    'name'     => $this->input->post('name'),
                                    'is_parent'     => $is_parent,
                                    'description'     => $this->input->post('description'),
                                    'seq'     => $this->input->post('seq'),
                                    );
                                if($this->master_model->edit_category($data,$id)){
                                    $return['res'] = 'success';
                                    $return['msg'] = 'Saved.';
                                }
                            }
                            else{
                                $tax_slab = explode(",",$this->input->post('tax_id'));
                                if($this->input->post('tax_id')=="")
                                    $tax_slab="0,0.0";
                                if($this->input->post('parent_id') && !$this->input->post('sub_cat_id'))
                                {
                                    $is_parent = $this->input->post('parent_id');
                                }
                                else if($this->input->post('parent_id') && $this->input->post('sub_cat_id'))
                                {
                                    $is_parent = $this->input->post('sub_cat_id');
                                }
                                else
                                {
                                    $is_parent = '0';
                                }
                                $data = array(
                                    'tax_id'     => $tax_slab[0],
                                    'tax_value'     => $tax_slab[1],
                                    'is_parent'     => $is_parent,
                                    'name'     => $this->input->post('name'),
                                    'description'     => $this->input->post('description'),
                                    'seq'     => $this->input->post('seq'),
                                    );
                                    //$fileinfo = @getimagesize($_FILES["icon"]["tmp_name"]);
                                   // $width = $fileinfo[0];
                                //    $height = $fileinfo[1];
                                    // if ($width > "300" || $height > "200") {
                                    
                                    //         $return['res'] = 'error';
                                    //         $return['msg'] = 'Image dimension should be within 300X200';
                                      
                                    // }
                                
                               if ($this->master_model->add_category($data)) {
                                    $return['res'] = 'success';
                                    $return['msg'] = 'Saved.';
                                }
                            }
                        }
                        echo json_encode($return);
                        break;
    
                
        }
    }

    public function products_oldd($p=null)
    {
        $data['user']  = $user         = checkLogin();
        $data['title'] = 'Products';

        // $data['products'] = $this->master_model->view_data('products_subcategory');

        $data['search'] = '';
        if (@$_POST['search']) {
            $data['search'] = $_GET['search'];
        }
        $this->load->library('pagination');
        $config = array();
        $config["base_url"]         = base_url()."products";
        $config["total_rows"]       = count($this->master_model->products());
        $data['total_rows']         = $config["total_rows"];
        $config["per_page"]         = 20;
        $config["uri_segment"]      = 2;
        $config['attributes']       = array('class' => 'pag-link');
        $config['full_tag_open']    = "<div class='pag'>";
        $config['full_tag_close']   = "</div>";
        $config['first_link']        = '&lt;&lt;';
        $config['last_link']        = '&gt;&gt;';
        $this->pagination->initialize($config);
        $data["links"]          = $this->pagination->create_links();
        $data['page']           = $page = ($p!=null) ? $p : 0;
        $data['per_page']       = $config["per_page"];
        $data['products']       = $this->master_model->products($config["per_page"],$page);
        $data['properties']     = $this->master_model->view_data('product_props_master');
        $data['unit_type']      = $this->master_model->view_data('unit_master');
        $data['categories']     = $this->master_model->view_data('products_category');
        $data['remote']         = base_url().'master-data/remote/products/';
        $page                   = 'admin/master/products';
        $this->header_and_footer($page, $data);
    }

        public function products_old()
        {
            $data['title'] = 'Products';

            $data['products'] = $this->master_model->view_data('products_subcategory');
            
            $data['properties'] = $this->master_model->view_data('product_props_master');
            $data['unit_type'] = $this->master_model->view_data('unit_master');
            $data['categories'] = $this->master_model->view_data('products_category');
            $data['remote']     = base_url().'master-data/remote/products/';
            $page = 'admin/master/products';
            $this->header_and_footer($page, $data);
        }

        public function add_product()
        {
            $data = array(
                'parent_cat_id'     => $this->input->post('parent_cat_id'),
                'name'              => $this->input->post('name'),
                'search_keywords'   => $this->input->post('search_keywords'),
                'product_code'      => $this->input->post('product_code'),
                'unit_value'        => $this->input->post('unit_value'),
                'unit_type'         => $this->input->post('unit_type'),
                'description'       => $this->input->post('description'),
            );
            if ($this->master_model->add_product($data)) {
                $this->session->set_flashdata('success', 'Product Added Successfully');
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', 'Something Went Wrong!!');
                redirect($this->agent->referrer());
            }
        }
        public function edit_product()
        {
            $id = $this->uri->segment(2);
            $data = array(
                'parent_cat_id'     => $this->input->post('parent_cat_id'),
                'name'     => $this->input->post('name'),
                'search_keywords'     => $this->input->post('search_keywords'),
                'product_code'     => $this->input->post('product_code'),
                'unit_value'     => $this->input->post('unit_value'),
                'unit_type'     => $this->input->post('unit_type'),
                'description'     => $this->input->post('description'),
            );
            if ($this->master_model->edit_data('products_subcategory',$id,$data)) {
                $this->session->set_flashdata('success', 'Product Edited Successfully');
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', 'Something Went Wrong!!');
                redirect($this->agent->referrer());
            }
        }
        public function delete_product()
        {
            $id = $this->uri->segment(2);
            if ($this->master_model->delete_data('products_subcategory',$id)) {
                $this->session->set_flashdata('success', 'Product Deleted Successfully');
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', 'Something Went Wrong!!');
                redirect($this->agent->referrer());
            }
        }
        public function view_product_images()
        {
        //     $data['title'] = 'Product Photos';
        //     $id = $this->uri->segment(3);
        //     $data['prod_img'] = $this->master_model->get_data('products_photo','item_id',$id);
        // $page = 'admin/master/products_photo';
        // $this->header_and_footer($page, $data);
            // $id = $this->uri->segment(3);
            // echo $id;exit;
        }
        public function add_property_value()
        {
            $data['user']  = $user         = checkLogin();
            $product_id = $this->uri->segment(2);
            $data = array(
                'product_id'     => $product_id,
                'props_id'     => $this->input->post('props_id'),
                'value'     => $this->input->post('value'),
            );
            if ($this->master_model->add_data('product_props',$data)) {
                $this->session->set_flashdata('success', 'Property Value Added Successfully');
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', 'Something Went Wrong!!');
                redirect($this->agent->referrer());
            }
        }
        // Product Property
        public function product_property()
        {
            $data['user']  = $user         = checkLogin();
            $data['menu_id'] = $this->uri->segment(2);
            $data['title'] = 'Product Property';
            $data['properties'] = $this->master_model->view_data('product_props_master');
            $data['property_value'] = $this->master_model->view_data('product_props_value');
            $data['remote']     = base_url().'master-data/remote/product_props/';
            $page = 'admin/master/product_property';
            $data['menu_url'] = $this->uri->segment(1);
            $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
            $this->header_and_footer($page, $data);
        }
        public function add_product_property()
        {
            $data['user']  = $user         = checkLogin();
            $is_selectable = $this->input->post('is_selectable');
            // if($this->input->post('is_selectable'))
            // {
            //     $is_selectable = '1';
            // }
            // else
            // {
            //     $is_selectable = '0';
            // }
            $data = array(
                'name'     => $this->input->post('name'),
                'is_selectable'     => $is_selectable
            );
            $this->form_validation->set_rules('name', 'Property Name', 'required');
            if ($this->master_model->add_data('product_props_master',$data)) {
                $this->session->set_flashdata('success', 'Product Property Added Successfully');
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', 'Something Went Wrong!!');
                redirect($this->agent->referrer());
            }
        }   
        public function edit_product_property()
        {
            $data['user']  = $user         = checkLogin();
            $id = $this->uri->segment(3);
            $is_selectable = $this->input->post('is_selectable');
            // if($this->input->post('is_selectable'))
            // {
            //     $is_selectable = '1';
            // }
            // else
            // {
            //     $is_selectable = '0';
            // }
            $data = array(
                'name'     => $this->input->post('name'),
                'is_selectable'     => $is_selectable
            );
            $this->form_validation->set_rules('name', 'Property Name', 'required');
            if ($this->master_model->edit_data('product_props_master',$id,$data)) {
                $this->session->set_flashdata('success', 'Product Property Edited Successfully');
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', 'Something Went Wrong!!');
                redirect($this->agent->referrer());
            }
        }   
        public function delete_product_property()
        {
            $data['user']  = $user         = checkLogin();
            $id = $this->uri->segment(3);
            if ($this->master_model->delete_data('product_props_master',$id)) {
                $this->session->set_flashdata('success', 'Product Property Deleted Successfully');
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', 'Something Went Wrong!!');
                redirect($this->agent->referrer());
            }
        }
        /////// product prop value filter
        public function add_product_property_value()
        {   
            $data['user']  = $user         = checkLogin();
            $prop_id = $this->input->post('prop_id');  
            $data = array(
                'prop_id'  => $prop_id,
                'value'     => $this->input->post('name'),
            );
            
            if ($this->master_model->add_data('product_props_value',$data)) {
                $data['property_value'] = $this->master_model->view_data('product_props_value');
                foreach($data['property_value'] as $row): 
                    if ($prop_id == $row->prop_id):
                        echo '<tr class="jsgrid-filter-row">
                        <th class="jsgrid-cell">'.$row->value.'</th>
                        <th class="jsgrid-cell">
                            <a href="javscript:void(0)" onclick="edit_product_property_value(this, '.$row->id.', '.$row->prop_id.')"><i class="fa fa-edit"></i></a>
                            <a href="javscript:void(0)" onclick="delete_product_property_value('.$row->id.', '.$row->prop_id.')"><i class="fa fa-trash"></i></a>
                        </th>
                    </tr>';
                endif; 
                endforeach;
            }
        }
        public function update_product_property_value()
        {   
            $data['user']  = $user         = checkLogin();
            $id = $this->input->post('id');
            $prop_id = $this->input->post('prop_id');  
            $data = array(
                'value'     => $this->input->post('name'),
            );
            
            if ($this->master_model->edit_data('product_props_value',$id,$data)) {
                $data['property_value'] = $this->master_model->view_data('product_props_value');
                foreach($data['property_value'] as $row): 
                    if ($prop_id == $row->prop_id):
                        echo '<tr class="jsgrid-filter-row">
                        <th class="jsgrid-cell">'.$row->value.'</th>
                        <th class="jsgrid-cell">
                            <a href="javscript:void(0)" onclick="edit_product_property_value(this, '.$row->id.', '.$row->prop_id.')"><i class="fa fa-edit"></i></a>
                            <a href="javscript:void(0)" onclick="delete_product_property_value('.$row->id.', '.$row->prop_id.')"><i class="fa fa-trash"></i></a>
                        </th>
                    </tr>';
                endif; 
                endforeach;
            }
        }
        public function delete_product_property_value()
        {   
            $data['user']  = $user         = checkLogin();
            $id = $this->input->post('id');
            $prop_id = $this->input->post('prop_id');

            if ($this->master_model->delete_data('product_props_value',$id)) {
                $data['property_value'] = $this->master_model->view_data('product_props_value');
                foreach($data['property_value'] as $row): 
                    if ($prop_id == $row->prop_id):
                        echo '<tr class="jsgrid-filter-row">
                        <th class="jsgrid-cell">'.$row->value.'</th>
                        <th class="jsgrid-cell">
                            <a href="javscript:void(0)" onclick="edit_product_property_value(this, '.$row->id.', '.$row->prop_id.')"><i class="fa fa-edit"></i></a>
                            <a href="javscript:void(0)" onclick="delete_product_property_value('.$row->id.', '.$row->prop_id.')"><i class="fa fa-trash"></i></a>
                        </th>
                    </tr>';
                endif; 
                endforeach;
            }
        }

        public function get_properties_value()
        {   
            $data['user']  = $user         = checkLogin();
            $prop_id = $this->input->post('prop_id');
            $value_id = $this->input->post('value_id');

            $data['property_value'] = $this->master_model->get_data('product_props_value', 'prop_id',$prop_id);
            foreach($data['property_value'] as $row): 
                if ($value_id == $row->id) {
                    echo '<option value="'.$row->id.'" selected>'.$row->value.'</option>';
                }
                else{
                    echo '<option value="'.$row->id.'">'.$row->value.'</option>';
                }                
            endforeach;
        }

        public function get_product_prop_master()
        {
            $data['user']  = $user         = checkLogin();
            $cat_id = $this->input->post('cat_id');

            $data['properties'] = $this->master_model->get_data('product_props_master', 'is_selectable','2');
            $properties_category = $this->db->where(['cat_id'=>$cat_id])->get('products_category_props')->result();
            foreach($data['properties'] as $row):
                $checked = '';
                foreach($properties_category as $row2):
                    if ($row->id == $row2->prop_master_id && $cat_id == $row2->cat_id) {
                        $checked = 'checked';
                    }
                endforeach;
                echo '<tr class="jsgrid-filter-row"><th class="jsgrid-cell"><input type="checkbox" value="'.$row->id.'" id="prop_master_name1'.$row->id.'" onclick="add_filter('.$row->id.', '.$cat_id.')" '.$checked.'><label for="prop_master_name1'.$row->id.'">'.$row->name.'</label></th></tr>';
            endforeach;
        }

        public function add_product_prop_category()
        {
            $data['user']  = $user         = checkLogin();
            $prop_m_id = $this->input->post('prop_m_id');
            $cat_id = $this->input->post('cat_id');

            $query = $this->db->where(['cat_id'=>$cat_id, 'prop_master_id'=>$prop_m_id])->get('products_category_props')->result();
            if ($query == TRUE) {
                $id = $query[0]->id;
                $this->db->delete('products_category_props', array('id' => $id));
            }
            else{
                $data = array(
                    'cat_id'=>$cat_id, 
                    'prop_master_id'=>$prop_m_id
                );
                $this->master_model->add_data('products_category_props',$data);
            }            
        }
        

        // Shop Category
        public function shop_category()
        {
            $data['user']  = $user         = checkLogin();
            $data['menu_id'] = $this->uri->segment(2);
            $data['title'] = 'Shop Category';
            $data['categories'] = $this->master_model->view_data('shop_category');
            $data['remote']     = base_url().'master-data/remote/shop_category/';
            $page = 'admin/master/shop_category';
            $this->header_and_footer($page, $data);
        }
        public function add_shop_category()
        {
            $data['user']  = $user         = checkLogin();
            $data = array(
                'name'     => $this->input->post('name')
            );
            $config['file_name'] = rand(10000, 10000000000);
            $config['upload_path'] = UPLOAD_PATH.'shop_category/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|webp';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!empty($_FILES['icon']['name'])) {
                //upload images
                $_FILES['icons']['name'] = $_FILES['icon']['name'];
                $_FILES['icons']['type'] = $_FILES['icon']['type'];
                $_FILES['icons']['tmp_name'] = $_FILES['icon']['tmp_name'];
                $_FILES['icons']['size'] = $_FILES['icon']['size'];
                $_FILES['icons']['error'] = $_FILES['icon']['error'];

                if ($this->upload->do_upload('icons')) {
                    $image_data = $this->upload->data();
                    $fileName = "shop_category/" . $image_data['file_name'];
                }
                $data['icon'] = $fileName;
            } else {
                $data['icon'] = "";
            }
            $this->form_validation->set_rules('name', 'Category Name', 'required');
            if ($this->master_model->add_data('shop_category',$data)) {
                $this->session->set_flashdata('success', 'Shop Category Added Successfully');
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', 'Something Went Wrong!!');
                redirect($this->agent->referrer());
            }
        }   
        public function edit_shop_category()
        {
            $data['user']  = $user         = checkLogin();
            $id = $this->uri->segment(3);
            $data = array(
                'name'     => $this->input->post('name')
            );
            $this->form_validation->set_rules('name', 'Category Name', 'required');
            if ($this->master_model->edit_data('shop_category',$id,$data)) {
                $this->session->set_flashdata('success', 'Shop Category Edited Successfully');
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', 'Something Went Wrong!!');
                redirect($this->agent->referrer());
            }
        }   
        public function delete_shop_category()
        {
            $data['user']  = $user         = checkLogin();
            $id = $this->uri->segment(3);
            if ($this->master_model->delete_data('shop_category',$id)) {
                $this->session->set_flashdata('success', 'Shop Category Deleted Successfully');
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', 'Something Went Wrong!!');
                redirect($this->agent->referrer());
            }
        } 
    // Unit Master
    public function unit_master()
    {
        $data['user']  = $user         = checkLogin();
        $data['menu_id'] = $this->uri->segment(2);
        $data['title']      = 'Unit Master';
        $data['units']      = $this->master_model->view_unit_master('unit_master');
        $data['remote']     = base_url().'master-data/remote/unit/';
        $page               = 'admin/master/unit_master';
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
        $this->header_and_footer($page, $data);
    }
    public function add_unit()
    {
        $data['user']  = $user         = checkLogin();
        $data = array(
            'name'     => $this->input->post('name')
        );
        $this->form_validation->set_rules('name', 'Unit Name', 'required');
        if ($this->master_model->add_data('unit_master',$data)) {
            $this->session->set_flashdata('success', 'Unit Added Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }   
    public function edit_unit()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        $data = array(
            'name'     => $this->input->post('name')
        );
        $this->form_validation->set_rules('name', 'Unit Name', 'required');
        if ($this->master_model->edit_data('unit_master',$id,$data)) {
            $this->session->set_flashdata('success', 'Unit Edited Successfully');
            redirect($this->agent->referrer());
        } else {
            redirect($this->agent->referrer());
        }
    }   
    public function delete_unit()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        if ($this->master_model->delete_data('unit_master',$id)) {
            $this->session->set_flashdata('success', 'Unit Deleted Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    } 

    //Pin Code Criteria
    public function pincodes_criteria()
    {
        $data['user']  = $user         = checkLogin();
        $data['menu_id'] = $this->uri->segment(2);
        $data['title']      = 'Pincodes Criteria';
        $data['pincodes_criteria']  = $this->master_model->view_pincodes_criteria();
        $data['business']  = $this->master_model->view_data('business');
        $data['shops']  = $this->master_model->view_data('shops');
        $data['remote']     = base_url().'master-data/remote/pincode/';
        $page = 'admin/master/pincodes_criteria';
        $this->header_and_footer($page, $data);
    }
    //Fetch Shops
    public function fetch_shop()
    {
        $data['user']  = $user         = checkLogin();
        if($this->input->post('business_id'))
        {
            $bid= $this->input->post('business_id');
            $this->master_model->fetch_shop($bid);
        }
    }


    public function add_pincodes_criteria()
    {
        $data['user']  = $user         = checkLogin();
        $data = array(
            'pincode'     => $this->input->post('pincode'),
            'shop_id'     => $this->input->post('shop_id'),
            'price'     => $this->input->post('price'),
            'kilometer'     => $this->input->post('kilometer')
        );
        if ($this->master_model->add_data('pincodes_criteria',$data)) {
            $this->session->set_flashdata('success', 'Pincodes Criteria Added Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }   
    public function edit_pincodes_criteria()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        $data = array(
            'pincode'     => $this->input->post('pincode'),
            'shop_id'     => $this->input->post('shop_id'),
            'price'     => $this->input->post('price'),
            'kilometer'     => $this->input->post('kilometer')
        );
        if ($this->master_model->edit_data('pincodes_criteria',$id,$data)) {
            $this->session->set_flashdata('success', 'Pincodes Criteria Edited Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }   
    public function delete_pincodes_criteria()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        if ($this->master_model->delete_pincodes_criteria($id)) {
            $this->session->set_flashdata('success', 'Pincodes Criteria Deleted Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    } 
    //Booking Slots
    public function booking_slots()
    {
        $data['user']  = $user         = checkLogin();
        $data['menu_id'] = $this->uri->segment(2);
        $data['title']      = 'Booking Slots';
        $data['booking_slots']  = $this->master_model->get_booking_slots();
        $data['business']  = $this->master_model->view_data('business');
        $data['shops']  = $this->master_model->view_data('shops');
        $page = 'admin/master/booking_slots';
        $this->header_and_footer($page, $data);
    }
    //Fetch Slots
    public function fetch_slot()
    {
        $data['user']  = $user         = checkLogin();
        if($this->input->post('day_name'))
        {
            $day= $this->input->post('day_name');
            $shop_id= $this->input->post('shop_id');
            $data['available_slots'] = $this->master_model->fetch_slot($day,$shop_id);
            $this->load->view('admin/master/available_slots',$data);
        }
    }

    public function add_booking_slot()
    {
        $data['user']  = $user         = checkLogin();
        $data = array(
            'day'     => $this->input->post('day_name'),
            'timestart'     => $this->input->post('timestart'),
            'timeend'     => $this->input->post('timeend'),
            'slotsize'     => $this->input->post('slotsize'),
            'shop_id'     => $this->input->post('shop_id')
        );
        if ($this->master_model->add_data('booking_slots',$data)) {
            $this->session->set_flashdata('success', 'Booking Slot Added Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }   
    public function edit_booking_slot()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        $data = array(
            'day'     => $this->input->post('day_name'),
            'timestart'     => $this->input->post('timestart'),
            'timeend'     => $this->input->post('timeend'),
            'slotsize'     => $this->input->post('slotsize'),
            'shop_id'     => $this->input->post('shop_id')
        );
        if ($this->master_model->edit_data('booking_slots',$id,$data)) {
            $this->session->set_flashdata('success', 'Booking Slot Edited Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }   
    public function delete_booking_slot()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        if ($this->master_model->delete_booking_slot($id)) {
            $this->session->set_flashdata('success', 'Booking Slot Deleted Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }
    
    //Society
    public function society()
    {
        $data['user']  = $user         = checkLogin();
        $data['menu_id'] = $this->uri->segment(2);
        $data['title']      = 'Society';
        $data['societyr']     = base_url().'master-data/society_remote/societyr/';
        $data['society']  = $this->master_model->view_data('society_master');
        $data['states']  = $this->master_model->view_data('states');
        $data['cities']  = $this->master_model->view_data('cities');
        $data['link_shop_url']=base_url().'master-data/link_shop_view/';
        $page = 'admin/master/society';
        // $page = 'admin/master/society_copy';
        $this->header_and_footer($page, $data);
    }

     //Fetch City
     public function fetch_city()
     {
         if($this->input->post('state'))
         {
             $sid= $this->input->post('state');
             $this->master_model->fetch_city($sid);
         }
     }
     //Fetch Business by city id
     public function fetch_business()
     {
         if($this->input->post('cityid'))
         {
             $cid= $this->input->post('cityid');
             $this->master_model->fetch_business($cid);
         }
     }

    public function add_society()
    {
        $data['user']  = $user         = checkLogin();
        $data = array(
            'name'     => $this->input->post('name'),
            'state'     => $this->input->post('state'),
            'city'     => $this->input->post('city'),
            'society_range'     => $this->input->post('society_range'),
            'longitude'     => $this->input->post('longitude'),
            'latitude'     => $this->input->post('latitude'),
            'address'     => $this->input->post('address')
        );
        //society image code
        $config['file_name'] = rand(10000, 10000000000);
        $config['upload_path'] = UPLOAD_PATH.'society_photo/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|webp';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!empty($_FILES['img']['name'])) {
            //upload images
            $_FILES['imgs']['name'] = $_FILES['img']['name'];
            $_FILES['imgs']['type'] = $_FILES['img']['type'];
            $_FILES['imgs']['tmp_name'] = $_FILES['img']['tmp_name'];
            $_FILES['imgs']['size'] = $_FILES['img']['size'];
            $_FILES['imgs']['error'] = $_FILES['img']['error'];

            if ($this->upload->do_upload('imgs')) {
                $image_data = $this->upload->data();
                $fileName = "society_photo/" . $image_data['file_name'];
            }
            $data['img'] = $fileName;
        } else {
            $data['img'] = "";
        }
        if ($this->master_model->add_data('society_master',$data)) {
            $this->session->set_flashdata('success', 'Society Added Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }   
    public function edit_society()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        $data = array(
            'name'     => $this->input->post('name'),
            'state'     => $this->input->post('state'),
            'city'     => $this->input->post('city'),
            'society_range'     => $this->input->post('society_range'),
            'longitude'     => $this->input->post('longitude'),
            'latitude'     => $this->input->post('latitude'),
            'address'     => $this->input->post('address')
        );
        //society image code
        $config['file_name'] = rand(10000, 10000000000);
        $config['upload_path'] = UPLOAD_PATH.'society_photo/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|webp';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        
        if (!empty($_FILES['img']['name'])) {
            //upload images
            $_FILES['imgs']['name'] = $_FILES['img']['name'];
            $_FILES['imgs']['type'] = $_FILES['img']['type'];
            $_FILES['imgs']['tmp_name'] = $_FILES['img']['tmp_name'];
            $_FILES['imgs']['size'] = $_FILES['img']['size'];
            $_FILES['imgs']['error'] = $_FILES['img']['error'];

            if ($this->upload->do_upload('imgs')) {
                $image_data = $this->upload->data();
                $fileName = "society_photo/" . $image_data['file_name'];
            }

            if (!empty($fileName)) 
            {
                $data2 = $this->db->get_where('society_master', ['socity_id' =>$id])->row();
                if (!empty($data2->img))
                {
                    if(is_file(DELETE_PATH.$data2->img))
                    {
                        unlink(DELETE_PATH.$data2->img);
                    }
                }
                
                $data['img'] = $fileName;
            } 
        }
        if ($this->master_model->edit_society($id,$data)) {
            $this->session->set_flashdata('success', 'Society Edited Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }   
    public function delete_society()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        if ($this->master_model->delete_society($id)) {
            $this->session->set_flashdata('success', 'Society Deleted Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }
    //Link Shops with society
    public function link_shop_view($id)
     {
        $data['user']  = $user         = checkLogin();
         $data['society_id'] = $id;
         $data['states']  = $this->master_model->view_data('states');
         $data['shops'] = $this->master_model->get_shops_by_society($id);
         if(!empty($data['banners']))
         {
             if($data['banners']->link_type == '4')
             {
                 $sid = $data['banners']->link_id;
                 $data['linked_item'] = $this->master_model->get_row_data('shops','id',$sid);
             }
         }
         $this->load->view('admin/master/society/link_shop_view',$data);
     }
     public function fetch_society_shops()
     {
        $data['user']  = $user         = checkLogin();
        $business_id = $this->input->post('business_id');
        $society_id = $this->input->post('society_id');
        $data['flg']='2';
        $data['shops']  = $this->master_model->get_data('shops','business_id',$business_id);
        $data['mapped_data'] = $this->master_model->get_data1('society_shops_link','socity_id',$society_id);
        $this->load->view('admin/master/society/shop_list',$data);
     }
     public function link_society_shop()
     {
        $data['user']  = $user         = checkLogin();
         $shop_id= $this->input->post('shop_id');
         $society_id= $this->input->post('society_id');
         $data2['flg']='1';
         $data2['shop_id']=$this->input->post('shop_id');
         $data = array(
             'shop_id'     => $shop_id,
             'socity_id'     => $society_id,
             'is_inside'     => $this->input->post('is_inside')
         );
         if($this->master_model->add_data('society_shops_link',$data))
         {
            $this->load->view('admin/master/society/map_unmap',$data2);
         }
     }
     //remove linked shop in society master
     public function remove_linked_shop()
     {
        $data['user']  = $user         = checkLogin();
         $shop_id= $this->input->post('shop_id');
         $society_id= $this->input->post('society_id');
         $data['flg'] = '0';
         $data['shop_id'] = $shop_id;
         if ($this->master_model->delete_linked_shop($shop_id,$society_id)) {
             $this->load->view('admin/master/society/map_unmap',$data);
         } 
     }
    //Tax Slab
    public function tax_slab()
    {
        $data['user']  = $user         = checkLogin();
        $data['menu_id'] = $this->uri->segment(2);
        $data['title']      = 'Tax Slab';
        $data['tax_slabs']  = $this->master_model->view_data('tax_slabs');
        $data['remote']     = base_url().'master-data/remote/tax_slab/';
        $page               = 'admin/master/tax_slab';
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
        $this->header_and_footer($page, $data);
    }
    public function add_tax_slab()
    {
        $data['user']  = $user         = checkLogin();
        $data = array(
            'slab'     => $this->input->post('slab')
        );
        $this->form_validation->set_rules('slab', 'Tax Slab', 'required');
        if ($this->master_model->add_data('tax_slabs',$data)) {
            $this->session->set_flashdata('success', 'Tax Slab Added Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }   
    public function edit_tax_slab()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        $data = array(
            'slab'     => $this->input->post('slab')
        );
        $this->form_validation->set_rules('slab', 'Tax Slab', 'required');
        if ($this->master_model->edit_data('tax_slabs',$id,$data)) {
            $this->session->set_flashdata('success', 'Tax Slab Edited Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }   
    public function delete_tax_slab()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        if ($this->master_model->delete_data('tax_slabs',$id)) {
            $this->session->set_flashdata('success', 'Tax Slab Deleted Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    } 


    public function pr($data,$die=true)
    {
        echo "<pre>";
        print_r($data);
        echo"</pre>";
        if ($die==true) {
           die();
        }
        
    }
    public function delete_product_image()
    {
        $data['user']  = $user         = checkLogin();
        $imageid = $this->uri->segment(2);
        if ($this->master_model->delete_data('products_photo',$imageid) == true) {
            echo "1";
        } else {
            echo "2";
        }
    } 
      
    public function change_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('pincodes_criteria','id',$id);


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
        $this->master_model->edit_data('pincodes_criteria',$id,$data1);
        $this->load->view('admin/statusview',$data);
        
    }
    public function change_cat_featured()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('products_category','id',$id);

        if($data['status_data']->featured == 1)
        {
            $data1 = array(
                'featured' => 0
            );
            $status = "false";
        }
        else if($data['status_data']->featured == 0)
        {
            $data1 = array(
                'featured' => 1
            );
            $status = "true";
        }
        $this->master_model->edit_data('products_category',$id,$data1);
        echo $status;
        
    }
    public function change_cat_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('products_category','id',$id);


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
        $this->master_model->edit_data('products_category',$id,$data1);
        $this->load->view('admin/statusview',$data);
        
    }
    public function change_product_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('products_subcategory','id',$id);


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
        $this->master_model->edit_data('products_subcategory',$id,$data1);
        $this->load->view('admin/statusview',$data);
        
    }
    public function change_prod_prop_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('product_props_master','id',$id);


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
        $this->master_model->edit_data('product_props_master',$id,$data1);
        $this->load->view('admin/statusview',$data);
        
    }
    public function change_society_status()
    {
        $data['user']  = $user         = checkLogin();
        $socity_id = $this->input->post('socity_id');
        $data['status_data'] = $this->master_model->get_row_data('society_master','socity_id',$socity_id);

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
        $this->master_model->change_society_status($socity_id,$data1);
        $this->load->view('admin/societystatusview',$data);
        
    }
    public function change_unit_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('unit_master','id',$id);

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
        $this->master_model->edit_data('unit_master',$id,$data1);
        $this->load->view('admin/statusview',$data);
        
    }
    public function change_social_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('shop_social','id',$id);

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
        $this->master_model->edit_data('shop_social',$id,$data1);
        $this->load->view('admin/statusview',$data);
        
    }
    public function change_cancellation_reason_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('cancellation_reason','id',$id);

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
        $this->master_model->edit_data('cancellation_reason',$id,$data1);
        $this->load->view('admin/statusview',$data);
        
    }
    public function change_brand_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('brand_master','id',$id);

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
        $this->master_model->edit_data('brand_master',$id,$data1);
        $this->load->view('admin/statusview',$data);
        
    }
    public function change_tax_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('tax_slabs','id',$id);

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
        $this->master_model->edit_data('tax_slabs',$id,$data1);
        $this->load->view('admin/statusview',$data);
        
    }
    public function change_slot_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('booking_slots','id',$id);

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
        $this->master_model->edit_data('booking_slots',$id,$data1);
        $this->load->view('admin/statusview',$data);
        
    }
    public function change_homebanner_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('home_banners','id',$id);

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
        $this->master_model->edit_data('home_banners',$id,$data1);
        $this->load->view('admin/statusview',$data);
        
    }
    public function change_homeheader_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('home_headers','id',$id);

        if($data['status_data']->status == 1)
        {
            $data1 = array(
                'status' => 0
            );
        }
        else if($data['status_data']->status == 0)
        {
            $data1 = array(
                'status' => 1
            );
        }
        $this->master_model->edit_data('home_headers',$id,$data1);
        $this->load->view('admin/header_statusview',$data);
        
    }
    public function change_shop_cat_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('shop_category','id',$id);

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
        $this->master_model->edit_data('shop_category',$id,$data1);
        $this->load->view('admin/statusview',$data);
        
    }
    public function change_shop_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('shops','id',$id);


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
        $this->master_model->edit_data('shops',$id,$data1);
        $this->load->view('admin/shopstatusview',$data);
        
    }

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

    //Home Banners

    public function home_banners()
    {
        $data['user']  = $user         = checkLogin();
        $data['menu_id'] = $this->uri->segment(2);
        $data['title'] = 'Home Banners';
        $data['link_banner_url']=base_url().'master-data/link_banner_view/';
        $shop_id     = $this->input->post('shop');
        if(!empty($shop_id))
        {
            $data['home_banners']  = $this->master_model->get_home_banner($shop_id);
        }
        else
        {
            $data['home_banners']  = $this->master_model->view_home_banner();
        }
        $data['business']  = $this->master_model->view_data('business');
        $data['shops']  = $this->master_model->view_data('shops');
        $page = 'admin/master/home_banners';
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
        $this->header_and_footer($page, $data);
    }
    public function link_banner_view($id)
	{
        $data['user']  = $user         = checkLogin();
        $data['banner_id'] = $id;
        $data['banners'] = $this->master_model->get_row_data1('home_banners','id',$id);
        if(!empty($data['banners']))
        {
            if($data['banners']->link_type == '1')
            {
                $pid = $data['banners']->link_id;
                $data['linked_item'] = $this->master_model->get_row_data('products_subcategory','id',$pid);
            }
            else if($data['banners']->link_type == '2')
            {
                $cid = $data['banners']->link_id;
                $data['linked_item'] = $this->master_model->get_row_data('products_category','id',$cid);
            }
            else if($data['banners']->link_type == '3')
            {
                $hid = $data['banners']->link_id;
                $data['linked_item'] = $this->master_model->get_row_data('home_headers','id',$hid);
            }
        }
		$this->load->view('admin/master/home_banner/link_banner_view',$data);
	}

    public function fetch_items()
	{
        $data['user']  = $user         = checkLogin();
		$link_id = $this->input->post('link_id');
		$bannerid = $this->input->post('bannerid');
        if($link_id == '3')
        {
            $data['banner_id'] = $bannerid;
            $data['home_header']  = $this->master_model->view_home_header();
            $this->load->view('admin/master/home_banner/link_header',$data);
        }
        else if($link_id == '2')
        {
            $data['banner_id'] = $bannerid;
            $data['sub_categories'] = $this->master_model->get_category();
            $this->load->view('admin/master/home_banner/link_category',$data);
        }
        else
        {
            $data['banner_id'] = $bannerid;
            $data['parent_cat'] = $this->master_model->get_data('products_category','is_parent','0');
            $data['products'] = $this->master_model->view_data('products_subcategory');
            $this->load->view('admin/master/home_banner/link_product',$data);
        }
	}
    public function get_products()
    {
        $data['user']  = $user         = checkLogin();
        if($this->input->post('parent_cat_id'))
        {
            $id= $this->input->post('parent_cat_id');
            $data['available_products'] = $this->master_model->fetch_products($id);
            $this->load->view('admin/master/home_banner/available_products',$data);
        }
    }
    public function link_header()
    {
        $data['user']  = $user         = checkLogin();
        $hid= $this->input->post('hid');
        $bannerid= $this->input->post('bannerid');
        $data = array(
            'link_type'     => '3',
            'link_id'     => $hid,
        );
        if($this->master_model->edit_data('home_banners',$bannerid,$data))
        {
            echo 'linked';
        }
    }
    public function link_category()
    {
        $data['user']  = $user         = checkLogin();
        $cid= $this->input->post('cid');
        $bannerid= $this->input->post('bannerid');
        $data = array(
            'link_type'     => '2',
            'link_id'     => $cid,
        );
        if($this->master_model->edit_data('home_banners',$bannerid,$data))
        {
            echo 'linked';
        }
    }
    public function link_product()
    {
        $data['user']  = $user         = checkLogin();
        $pid= $this->input->post('pid');
        $bannerid= $this->input->post('bannerid');
        $data = array(
            'link_type'     => '1',
            'link_id'     => $pid,
        );
        if($this->master_model->edit_data('home_banners',$bannerid,$data))
        {
            echo 'linked';
        }
    }
    public function add_home_banner()
    {
        $data['user']  = $user         = checkLogin();
        $data = array(
            'shop_id'     => $this->input->post('shop_id'),
            'seq'     => $this->input->post('seq'),
            'banner_type'     => $this->input->post('banner_type'),
        );
        if ($this->master_model->add_home_banner($data)) {
            $this->session->set_flashdata('success', 'Home Banner Added Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }
    // public function filter_home_banner()
    // {
            
    //     if(!empty($shop_id))
    //     {
    //         $this->master_model->filter_home_banner($shop_id);
    //     }
    //     if ($this->master_model->filter_home_banner($data)) {
    //         $this->session->set_flashdata('success', 'Home Banner Filtered Successfully');
    //         redirect($this->agent->referrer());
    //     } else {
    //         $this->session->set_flashdata('error', 'Something Went Wrong!!');
    //         redirect($this->agent->referrer());
    //     }
    // }
    public function edit_home_banner()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);

        $data = array(
            'shop_id'     => $this->input->post('shop_id'),
            'seq'     => $this->input->post('seq'),
            'banner_type'     => $this->input->post('banner_type'),
        );

        if ($this->master_model->edit_home_banner($data,$id)) {
            $this->session->set_flashdata('success', 'Updated Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }
    public function delete_home_banner()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        if ($this->master_model->delete_data('home_banners',$id)) {
            $this->session->set_flashdata('success', 'Deleted Successfully ');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    } 
     //Market Place Home Banners
     public function market_place_home_banners()
     {
        $data['user']  = $user         = checkLogin();
         $data['menu_id'] = $this->uri->segment(2);
         $data['title'] = 'Market Place Home Banners';
         $data['link_banner_url']=base_url().'master-data/market_place_link_banner_view/';
         $data['home_banners']  = $this->master_model->get_market_place_home_banner();
         $page = 'admin/master/market_place_home_banners';
         $this->header_and_footer($page, $data);
     }
     public function add_market_place_home_banners()
     {
        $data['user']  = $user         = checkLogin();
         $data = array(
             'shop_id'     => '0',
             'seq'     => $this->input->post('seq'),
             'banner_type'     => $this->input->post('banner_type'),
         );
         if ($this->master_model->add_market_place_home_banners($data)) {
             $this->session->set_flashdata('success', 'Home Banner Added Successfully');
             redirect($this->agent->referrer());
         } else {
             $this->session->set_flashdata('error', 'Something Went Wrong!!');
             redirect($this->agent->referrer());
         }
     }
     public function edit_market_place_home_banners()
     {
        $data['user']  = $user         = checkLogin();
         $id = $this->uri->segment(3);
 
         $data = array(
             'shop_id'     => '0',
             'seq'     => $this->input->post('seq'),
             'banner_type'     => $this->input->post('banner_type'),
         );
 
         if ($this->master_model->edit_market_place_home_banners($data,$id)) {
             $this->session->set_flashdata('success', 'Updated Successfully');
             redirect($this->agent->referrer());
         } else {
             $this->session->set_flashdata('error', 'Something Went Wrong!!');
             redirect($this->agent->referrer());
         }
     }
     public function delete_market_place_home_banners()
     {
        $data['user']  = $user         = checkLogin();
         $id = $this->uri->segment(3);
         if ($this->master_model->delete_data('home_banners',$id)) {
             $this->session->set_flashdata('success', 'Deleted Successfully ');
             redirect($this->agent->referrer());
         } else {
             $this->session->set_flashdata('error', 'Something Went Wrong!!');
             redirect($this->agent->referrer());
         }
     } 
     public function market_place_link_banner_view($id)
     {
        $data['user']  = $user         = checkLogin();
         $data['banner_id'] = $id;
         $data['states']  = $this->master_model->view_data('states');
         $data['banners'] = $this->master_model->get_row_data1('home_banners','id',$id);
         if(!empty($data['banners']))
         {
             if($data['banners']->link_type == '4')
             {
                 $sid = $data['banners']->link_id;
                 $data['linked_item'] = $this->master_model->get_row_data('shops','id',$sid);
             }
         }
         $this->load->view('admin/master/market_place_home_banner/link_banner_view',$data);
     }
     // Cancellation Reason
    public function cancellation_reason()
    {
        $data['user']  = $user         = checkLogin();
        $data['menu_id'] = $this->uri->segment(2);
        $data['title']      = 'Cancellation Reason';
        $data['cancellation_reasons']      = $this->master_model->view_data('cancellation_reason');
        $data['remote']     = base_url().'master-data/remote/cancellation_reason/';
        $page               = 'admin/master/cancellation_reason';
        $this->header_and_footer($page, $data);
    }
    public function add_cancellation_reason()
    {
        $data['user']  = $user         = checkLogin();
        $data = array(
            'content'     => $this->input->post('content')
        );
        $this->form_validation->set_rules('content', 'Content Field', 'required');
        if ($this->master_model->add_data('cancellation_reason',$data)) {
            $this->session->set_flashdata('success', 'Reason Added Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }   
    public function edit_cancellation_reason()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        $data = array(
            'content'     => $this->input->post('content')
        );
        $this->form_validation->set_rules('content', 'Content Field', 'required');
        if ($this->master_model->edit_data('cancellation_reason',$id,$data)) {
            $this->session->set_flashdata('success', 'Reason Edited Successfully');
            redirect($this->agent->referrer());
        } else {
            redirect($this->agent->referrer());
        }
    }   
    public function delete_cancellation_reason()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        if ($this->master_model->delete_data('cancellation_reason',$id)) {
            $this->session->set_flashdata('success', 'Reason Deleted Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    } 
    //Shop Social
    public function shop_social()
    {
        $data['user']  = $user         = checkLogin();
        $data['menu_id'] = $this->uri->segment(2);
        $data['title']      = 'Shop Social';
        $data['socials']      = $this->master_model->shop_social();
        $data['business']  = $this->master_model->view_data('business');
        $data['remote']     = base_url().'master-data/remote/shop_social/';
        $page               = 'admin/master/shop_social';
        $this->header_and_footer($page, $data);
    }
    public function add_shop_social()
    {
        $data['user']  = $user         = checkLogin();
        $data = array(
            'icon'     => $this->input->post('icon'),
            'url'     => $this->input->post('url'),
            'shop_id'     => $this->input->post('shop_id'),
        );
        if ($this->master_model->add_data('shop_social',$data)) {
            $this->session->set_flashdata('success', 'Icon Added Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }   
    public function edit_shop_social()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        $data = array(
            'icon'     => $this->input->post('icon'),
            'url'     => $this->input->post('url'),
            'shop_id'     => $this->input->post('shop_id'),
        );
        if ($this->master_model->edit_data('shop_social',$id,$data)) {
            $this->session->set_flashdata('success', 'Icon Edited Successfully');
            redirect($this->agent->referrer());
        } else {
            redirect($this->agent->referrer());
        }
    }   
    public function delete_shop_social()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(3);
        if ($this->master_model->delete_data('shop_social',$id)) {
            $this->session->set_flashdata('success', 'Icon Deleted Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    } 
     public function fetch_shops()
     {
        $data['user']  = $user         = checkLogin();
        $business_id = $this->input->post('business_id');
        $data['shops']  = $this->master_model->get_data('shops','business_id',$business_id);
        $this->load->view('admin/master/market_place_home_banner/shop_list',$data);
     }
     public function link_shop()
     {
        $data['user']  = $user         = checkLogin();
         $sid= $this->input->post('sid');
         $bannerid= $this->input->post('bannerid');
         $data = array(
             'link_type'     => '4',
             'link_id'     => $sid,
         );
         if($this->master_model->edit_data('home_banners',$bannerid,$data))
         {
             echo 'linked';
         }
     }
    //Home Header

    public function home_header()
    {
        $data['user']  = $user         = checkLogin();
        $data['menu_id'] = $this->uri->segment(2);
        $data['title'] = 'Home Header';
        $data['home_header']  = $this->master_model->view_home_header();
        $data['business']  = $this->master_model->view_data('business');
        $data['shops']  = $this->master_model->view_data('shops');
        $page = 'admin/master/home_header/home_header';
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
        $this->header_and_footer($page, $data);
    }

    public function add_home_header()
    {
        $data['user']  = $user         = checkLogin();
        $data = array(
            'title'     => $this->input->post('title'),
            'type'     => $this->input->post('type'),
            'shop_id'     => $this->input->post('shop_id'),
            'colorcode'     => $this->input->post('colorcode'),
            'seq'     => $this->input->post('seq'),
        );
        if ($this->master_model->add_data('home_headers',$data)) {
            $this->session->set_flashdata('success', 'Home Header Added Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }
    public function edit_home_header()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(2);

        $data = array(
            'title'     => $this->input->post('title'),
            'type'     => $this->input->post('type'),
            'shop_id'     => $this->input->post('shop_id'),
            'colorcode'     => $this->input->post('colorcode'),
            'seq'     => $this->input->post('seq'),
        );

        if ($this->master_model->edit_data('home_headers',$id,$data)) {
            $this->session->set_flashdata('success', 'Data Updated Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }
    public function delete_home_header()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(2);
        if ($this->master_model->delete_data('home_headers',$id)) {
            $this->session->set_flashdata('success', 'Data Deleted Successfully ');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    } 

    public function product_headers_mapping()
    {
        $data['user']  = $user         = checkLogin();
        $data['title'] = 'Products Headers Mapping';
        $id = $this->uri->segment(2);
        $data['headerid'] = $id;
        $data['headers_mapping'] = $this->master_model->get_headers_mapping($id);
        $page = 'admin/master/home_header/headers_mapping';
        $this->header_and_footer($page, $data);
        
       
    }
    public function add_mapping()
    {
        $data['user']  = $user         = checkLogin();
        $data['headerid'] = $this->input->post('headerid');
        $data['parent_cat'] = $this->master_model->get_data('products_category','is_parent','0');
        $data['categories'] = $this->master_model->get_data('products_category','is_parent !=','0');
        $this->load->view('admin/master/home_header/add_mapping',$data);
       
    }
        //Fetch Products
        public function fetch_products()
        {
            if($this->input->post('parent_cat_id'))
            {
                $id= $this->input->post('parent_cat_id');
                $headerid= $this->input->post('headerid');
                $data['headerid'] = $headerid;
                $data['available_products'] = $this->master_model->fetch_products($id);
                $data['headers_mapping'] = $this->master_model->get_headers_mapping($headerid);
                // print_r($data['headers_mapping']);
                $this->load->view('admin/master/home_header/available_products',$data);
            }
        }
        public function map_product()
        {
            $data['user']  = $user         = checkLogin();
            $pid= $this->input->post('pid');
            $headerid= $this->input->post('headerid');
            $data = array(
                'header_id'     => $headerid,
                'value'     => $pid,
            );
            $data['headers_mapping'] = $this->master_model->add_data('home_headers_mapping',$data);
            $data['flg'] = '1';
            $data['pid'] = $pid;
            if($data['headers_mapping'])
            {
                $this->load->view('admin/master/home_header/map_unmap',$data);
            }
        }
        public function remove_map_product()
        {
            $data['user']  = $user         = checkLogin();
            $pid= $this->input->post('pid');
            $headerid= $this->input->post('headerid');
            $data['flg'] = '0';
            $data['pid'] = $pid;
            if ($this->master_model->delete_header_mapping($pid,$headerid)) {
                $this->load->view('admin/master/home_header/map_unmap',$data);
            } 
        }

        public function delete_header_mapping()
        {
            $data['user']  = $user         = checkLogin();
            $id = $this->uri->segment(2);
        if ($this->master_model->delete_header_map($id)) {
            $this->session->set_flashdata('success', 'Data Deleted Successfully ');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
        }

        //Category Headers Mapping
        public function cat_headers_mapping()
    {
        $data['user']  = $user         = checkLogin();
        $data['title'] = 'Category Headers Mapping';
        $id = $this->uri->segment(2);
        $data['headerid'] = $id;
        $data['category_mapping'] = $this->master_model->get_category_mapping($id);
        $page = 'admin/master/home_header/category_mapping';
        $this->header_and_footer($page, $data);
    }
    public function add_cat_mapping()
    {
        $data['user']  = $user         = checkLogin();
        $data['headerid'] = $this->input->post('headerid');
         $headerid = $data['headerid'];
        $data['parent_cat'] = $this->master_model->get_data('products_category','is_parent','0');
        $data['headers_mapping'] = $this->master_model->get_category_mapping($headerid);
        $this->load->view('admin/master/home_header/add_cat_mapping',$data);
       
    }
    public function map_category()
    {
        $data['user']  = $user         = checkLogin();
        $cid= $this->input->post('cid');
        $headerid= $this->input->post('headerid');
        $data = array(
            'header_id'     => $headerid,
            'value'     => $cid,
        );
        $data['category_mapping'] = $this->master_model->add_data('home_headers_mapping',$data);
        $data['flg'] = '1';
        $data['cid'] = $cid;
        if($data['category_mapping'])
        {
            $this->load->view('admin/master/home_header/cat_map_unmap',$data);
        }
    }
    public function remove_map_category()
    {
        $data['user']  = $user         = checkLogin();
        $cid= $this->input->post('cid');
        $headerid= $this->input->post('headerid');
        $data['flg'] = '0';
        $data['cid'] = $cid;
        if ($this->master_model->delete_category_mapping($cid,$headerid)) {
            $this->load->view('admin/master/home_header/cat_map_unmap',$data);
        } 
    }
    public function delete_cat_header_mapping()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(2);
    if ($this->master_model->delete_category_map($id)) {
        $this->session->set_flashdata('success', 'Data Deleted Successfully ');
        redirect($this->agent->referrer());
    } else {
        $this->session->set_flashdata('error', 'Something Went Wrong!!');
        redirect($this->agent->referrer());
    }
    }
    


    public function vendors($action=null,$p1=null,$p2=null,$p3=null)
    {
        $data['user']  = $user         = checkLogin();
        // $data['user']    = $this->checkLogin();
        switch ($action) {
            case null:
                $data['menu_id'] = $this->uri->segment(2);
                $data['title']          = 'Vendors';
                $data['tb_url']         = base_url().'vendors/tb';
                $data['new_url']        = base_url().'vendors/create';
                $page                   = 'admin/master/vendors/index';
                $data['menu_url'] = $this->uri->segment(1);
                $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
                $this->header_and_footer($page, $data);
                break;

            case 'tb':
                $data['search'] = '';
                if (@$_POST['search']) {
                    $data['search'] = $_POST['search'];
                }
            

                $this->load->library('pagination');
                $config = array();
                $config["base_url"]         = base_url()."vendors/tb/";
                $config["total_rows"]       = $this->shops_vendor_model->vendors();
                $data['total_rows']         = $config["total_rows"];
                $config["per_page"]         = 20;
                $config["uri_segment"]      = 3;
                $config['attributes']       = array('class' => 'pag-link');
                $config['full_tag_open']    = "<div class='pag'>";
                $config['full_tag_close']   = "</div>";
                $config['first_link']       = '&lt;&lt;';
                $config['last_link']        = '&gt;&gt;';
                $this->pagination->initialize($config);
                $data["links"]              = $this->pagination->create_links();
                $data['page']               = $page = ($p1!=null) ? $p1 : 0;
                $data['per_page']           = $config["per_page"];
                $data['vendors']           = $this->shops_vendor_model->vendors($config["per_page"],$page);
                $data['update_url']         = base_url().'vendors/create/';
                $page                       = 'admin/master/vendors/tb';

                
                $this->load->view($page, $data);
                break;
            
            case 'create':
                $data['remote']             = base_url().'master-data/remote/vendor/';
                $data['action_url']         = base_url().'vendors/save';
                $data['states']  = $this->shops_vendor_model->view_data('states');
                $data['cities']  = $this->shops_vendor_model->view_data('cities');
                $data['business']  = $this->master_model->view_data('business');
                $page                       = 'admin/master/vendors/create';
                if ($p1!=null) {
                    $data['value']          = $this->shops_vendor_model->customers_row($p1);
                    $data['action_url']     = base_url().'vendors/save/'.$p1;
                    $data['remote']         = base_url().'master-data/remote/vendor/'.$p1;
                    $data['opening']        = $this->shops_vendor_model->get_vendor_opening($p1);
                    $page                   = 'admin/master/vendors/update';
                }
                
                $data['form_id']            = uniqid();
                
               
                $this->load->view($page, $data);
                break;

            
                case 'save':
                    $id = $p1;
                    $return['res'] = 'error';
                    $return['msg'] = 'Not Saved!';
    
                    if ($this->input->server('REQUEST_METHOD')=='POST') { 
                      
                         if ($id!=null) {
                            $shop_id     = '6';
                            $datamain = array(
                                'mobile'              => $this->input->post('mobile'),
                                'shop_id'        => $shop_id,
                            );
                            $data = array(
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
                            $opening = array(
                                'user_id' => $id,
                                'dr_cr' => $this->input->post('dr_cr'),
                                'amount' => $this->input->post('amount'),
                                'remark' => $this->input->post('remark'),
                                );
                            $this->shops_vendor_model->vendor_opening_new2($opening,$id);
                            if($this->shops_vendor_model->edit_data('customers',$id,$datamain)){
                                $this->shops_vendor_model->Update('supplier_details',$data,['supplier_id'=>$p1]);
                                $return['res'] = 'success';
                                $return['msg'] = 'Updated.';
                            }
                        }
                        else{ 
                            $shop_id     = '6';
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
                                $return['res'] = 'success';
                                $return['msg'] = 'Saved.';
                            }
                        }
                    }
                    echo json_encode($return);
                    break;

            

               
            case 'delete':
                $id = $p1;
                if($this->shops_vendor_model->delete_data('vendors',$id))
                {
                    $data['search'] = '';
                    if (@$_POST['search']) {
                        $data['search'] = $_POST['search'];
                    }
    
                    $this->load->library('pagination');
                    $config = array();
                    $config["base_url"]         = base_url()."vendors/tb/";
                    $config["total_rows"]       = $this->shops_vendor_model->vendors();
                    $data['total_rows']         = $config["total_rows"];
                    $config["per_page"]         = 20;
                    $config["uri_segment"]      = 3;
                    $config['attributes']       = array('class' => 'pag-link');
                    $config['full_tag_open']    = "<div class='pag'>";
                    $config['full_tag_close']   = "</div>";
                    $config['first_link']       = '&lt;&lt;';
                    $config['last_link']        = '&gt;&gt;';
                    $this->pagination->initialize($config);
                    $data["links"]              = $this->pagination->create_links();
                    $data['page']               = $page = ($p2!=null) ? $p2 : 0;
                    $data['per_page']           = $config["per_page"];
                    $data['vendors']           = $this->shops_vendor_model->vendors($config["per_page"],$page);
                    $data['update_url']         = base_url().'vendors/create/';
                    $page                       = 'admin/master/vendors/tb';
    
                    
                    $this->load->view($page, $data);
                }
                break;
            default:
                # code...
                break;
        }
    }

    public function change_vendor_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->shops_vendor_model->get_row_data('vendors','id',$id);


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
        $this->shops_vendor_model->edit_data('vendors',$id,$data1);
        $this->load->view('admin/statusview',$data);
        
    }

    // Brand Master
    public function brand_master()
    {
        $data['user']  = $user         = checkLogin();
        $data['menu_id'] = $this->uri->segment(2);
        $data['title']      = 'Brand Master';
        $data['brands']      = $this->master_model->view_brand_master('brand_master');
        $data['remote']     = base_url().'master-data/remote/brand/';
        $page               = 'admin/master/brand_master';
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
        $this->header_and_footer($page, $data);
    }
    public function add_brand()
    {
        $data['user']  = $user         = checkLogin();
        $data = array(
            'name'     => $this->input->post('name')
        );
        $this->form_validation->set_rules('name', 'Brand Name', 'required');
        if ($this->master_model->add_data('brand_master',$data)) {
            $this->session->set_flashdata('success', 'Company Added Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }   
    public function edit_brand()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(2);
        $data = array(
            'name'=> $this->input->post('name')
        );
        $this->form_validation->set_rules('name', 'Brand Name', 'required');
        if ($this->master_model->edit_data('brand_master',$id,$data)) {
            $this->session->set_flashdata('success', 'Company Edited Successfully');
            redirect($this->agent->referrer());
        } else {
            redirect($this->agent->referrer());
        }
    }   
    public function delete_brand()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(2);
        if ($this->master_model->delete_data('brand_master',$id)) {
            $this->session->set_flashdata('success', 'Company Deleted Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    } 

    // Flavour Master
    public function flavour_master()
    {
        $data['user']  = $user         = checkLogin();
        $data['menu_id'] = $this->uri->segment(2);
        $data['title']      = 'Flavour Master';
        $data['flavour']      = $this->master_model->view_flavour_master('flavour_master');
        $data['remote']     = base_url().'master-data/remote/flavour/';
        $page               = 'admin/master/flavour_master';
        $this->header_and_footer($page, $data);
    }
    public function add_flavour()
    {
        $data['user']  = $user         = checkLogin();
        $data = array(
            'name'     => $this->input->post('name')
        );
        $this->form_validation->set_rules('name', 'Flavour Name', 'required');
        if ($this->master_model->add_data('flavour_master',$data)) {
            $this->session->set_flashdata('success', 'Flavour Added Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }   
    public function edit_flavour()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(2);
        $data = array(
            'name'     => $this->input->post('name')
        );
        $this->form_validation->set_rules('name', 'Flavour Name', 'required');
        if ($this->master_model->edit_data('flavour_master',$id,$data)) {
            $this->session->set_flashdata('success', 'Flavour Edited Successfully');
            redirect($this->agent->referrer());
        } else {
            redirect($this->agent->referrer());
        }
    }   
    public function delete_flavour()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->uri->segment(2);
        if ($this->master_model->delete_data('flavour_master',$id)) {
            $this->session->set_flashdata('success', 'Flavour Deleted Successfully');
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', 'Something Went Wrong!!');
            redirect($this->agent->referrer());
        }
    }

    public function change_flavour_status()
    {
        $data['user']  = $user         = checkLogin();
        $id = $this->input->post('id');
        $data['status_data'] = $this->master_model->get_row_data('flavour_master','id',$id);

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
        $this->master_model->edit_data('flavour_master',$id,$data1);
        $this->load->view('admin/statusview',$data);
        
    }
    //Fetch Sub categories
    public function fetch_sub_categories()
    {
        if($this->input->post('parent_id'))
        {
            $parent_id= $this->input->post('parent_id');
            $this->master_model->fetch_sub_categories($parent_id);
        }
    }
    function multiple_delete()
    {
        $data['user']  = $user         = checkLogin();
     if($this->input->post('checkbox_value'))
     {
        $id = $this->input->post('checkbox_value');
        $table = $this->input->post('table');
        for($count = 0; $count < count($id); $count++)
        {
            if($table == 'society_master')
            {
                $is_deleted = array('is_deleted' => 'DELETED');
                $this->db->where('socity_id', $id[$count])->update($table, $is_deleted);
            }
            else
            {
                $this->master_model->delete_data($table,$id[$count]);
            }
        }
        
     }
    }


    
    public function importExcelData($action=null,$p1=null)
    {
    switch ($action) {
    case null:
        $data['menu_id'] = $this->uri->segment(2);
        $data['title']          = 'Excel Data Import';
        $page                   = 'admin/master/data_import/index';
        $data['business']  = $this->master_model->view_data('business');
        $data['action_url']	  	= base_url('import-excel-data/data_import_post');
        $data['menu_url'] = $this->uri->segment(1);
        $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
        $this->header_and_footer($page, $data); 
    break;
    case 'data_import_post':
        $data['user']  = $user         = checkLogin();
        $return['res'] = 'error';
        $return['msg'] = 'Not Saved!';
         if ($this->input->server('REQUEST_METHOD')=='POST') { 
         $shop_id = $this->input->post('shop');   
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
            $CountItem = 0;
            $MRP = 0;
            $insertedItemCount = 0;
            $notInsertedItemCount = $insertedItemCountNotExist=0;
            $headerSkipped = false;
            $createsItem = array(); 
            foreach ($sheetData as $data) {
                if (!$headerSkipped) {
                    $headerSkipped = true;
                    continue; 
                }
                if (!empty($data)) {
                    $Company = trim($data[1]);
                    $ItemCode = trim($data[2]);
                    $Name = trim($data[3]);
                    $HsnCode = trim($data[4]);
                    $IGST = trim($data[9]);
                    $HsnName = trim($data[10]);
                    $Category = trim($data[11]);
                    $DPCO = trim($data[12]);
                    $QTY = trim($data[13]);
                    $QtyType = trim($data[14]);
                    $Return = trim($data[15]);
                    $MRP = trim($data[16]);
                    $Selling_Rate = trim($data[17]);                    
                     
                    if(!empty($Category)){
                     $CheckCategoryExist = $this->master_model->CheckCategoryExist($Category);
                     if($CheckCategoryExist)
                      {
                        // Company Check
                        $CompanyExist =$this->master_model->checkCompany($Company);
                        if($CompanyExist)
                        {
                            $Comp = $this->master_model->getRow('brand_master',['name'=>$Company]);
                            $Comp_Id = $Comp->id;
                        }else{
                            $CompanyData =array (
                                'name'=>$Company,
                                'active'=>'1',
                            );  
                            $Comp_Id =  $this->master_model->Save('brand_master',$CompanyData);
                        }
                        $UnitRow = $this->master_model->getRow('unit_master',['name'=>$QtyType]);
                        $dpcoName = $this->master_model->getRow('dpco',['title'=>$DPCO]);
                        $IGSTNAME = $this->master_model->getRow('tax_slabs',['slab'=>$IGST]);

                    $checkItemExist = $this->master_model->checkItemExist($ItemCode);
                    if($checkItemExist)
                      {
                        $ItemRow = $this->master_model->getRow('products_subcategory',['product_code'=>$ItemCode]);
                        $Item_id = $ItemRow->id;
                        }else{
                       $ItemData =array (
                         'name'=>$Name,
                         'product_code'=>$ItemCode,
                         'search_keywords'=>$Name,
                         'brand_id'=>$Comp_Id,
                         'sku'=>$HsnCode,
                         'unit_type'=>$UnitRow->name ? $UnitRow->name : '',
                         'unit_type_id'=>$UnitRow->id ? $UnitRow->id : '',
                         'is_return'=>$Return,
                         'dpco'=>$dpcoName->id ? $dpcoName->id : '',
                         'tax_id'=>$IGSTNAME->id ? $IGSTNAME->id : '',
                         'tax_value'=>$IGSTNAME->slab ? $IGSTNAME->slab : '',
                        );  
                       $Item_id =  $this->master_model->Save('products_subcategory',$ItemData);
                     }
                     $this->db->where(['pro_id'=>$Item_id,'cat_id'=>$CheckCategoryExist])->delete('cat_pro_maps'); 
                     //  Item Cat maps
                     $CatRowReturn = $this->master_model->getRow('products_category',['id'=>$CheckCategoryExist]);
                     if($CatRowReturn->is_parent==0 &&  $CatRowReturn->level==1)
                      {
                         $CatPro_map = array (
                            'pro_id'=>$Item_id,
                            'cat_id'=>$CheckCategoryExist,
                           );
                        $catid = $this->master_model->Save('cat_pro_maps',$CatPro_map);
                        logs($user->id,$Item_id,'Map Category Product','Category '.$CheckCategoryExist.' - '.$Item_id);
                        }elseif($CatRowReturn->level==2 )
                        {
                            $rs = $this->master_model->getRow('products_category',['id'=>$CatRowReturn->is_parent,'level'=>'1']);
                            $CatPro_map2 = array (
                                'pro_id'=>$Item_id,
                                'cat_id'=>$CheckCategoryExist,
                               );
                            $catid = $this->master_model->Save('cat_pro_maps',$CatPro_map2);
                            logs($user->id,$Item_id,'Map Category Product','Category '.$CheckCategoryExist.' - '.$Item_id);
                            $CatPro_map3 = array (
                                'pro_id'=>$Item_id,
                                'cat_id'=>$rs->id,
                               );
                            $catid = $this->master_model->Save('cat_pro_maps',$CatPro_map3);
                            logs($user->id,$Item_id,'Map Category Product','Category '.$rs->id.' - '.$Item_id);
                                }elseif($CatRowReturn->level==3)
                                {
                                    
                                    $rs3 = $this->master_model->getRow('products_category',['id'=>$CatRowReturn->is_parent,'level'=>'2']);
                                    $rs4 = $this->master_model->getRow('products_category',['id'=>$rs3->is_parent,'level'=>'1']);
                                    $CatPro_map4 = array (
                                        'pro_id'=>$Item_id,
                                        'cat_id'=>$CheckCategoryExist,
                                       );
                                    $catid = $this->master_model->Save('cat_pro_maps',$CatPro_map4);
                                    logs($user->id,$Item_id,'Map Category Product','Category '.$CheckCategoryExist.' - '.$Item_id);
                                    $CatPro_map5 = array (
                                        'pro_id'=>$Item_id,
                                        'cat_id'=>$rs3->id,
                                       );
                                    $catid = $this->master_model->Save('cat_pro_maps',$CatPro_map5);
                                    logs($user->id,$Item_id,'Map Category Product','Category '.$rs3->id.' - '.$Item_id);
                                    $CatPro_map6 = array (
                                        'pro_id'=>$Item_id,
                                        'cat_id'=>$rs4->id,
                                       );
                                    $catid = $this->master_model->Save('cat_pro_maps',$CatPro_map6);
                                    logs($user->id,$Item_id,'Map Category Product','Category'.$rs4->id.' - '.$Item_id);
                                }
                 
                    $CheckItemInventory = $this->master_model->CheckItemInventory($Item_id);
                    if($CheckItemInventory){
                    }else{
                        $inventory_data = array(
                            'product_id'    => $Item_id,
                            'qty'           => isset($QTY) ? $QTY : 0,
                            'purchase_rate' => isset($Selling_Rate) ? $Selling_Rate : 0,
                            'selling_rate'  => isset($Selling_Rate) ? $Selling_Rate : 0,
                            'mrp'           => isset($MRP) ? $MRP : 0,
                            'shop_id'       => $shop_id,
                        );
                     if($insert_stock = $this->master_model->Save('shops_inventory',$inventory_data))
                     {
                        $inventory_data['action']="ADD";
                        $inventory_data['shops_inventory_id']=$insert_stock;
                        if ($insert_id = $this->master_model->add_data('shop_inventory_logs', $inventory_data)) {
                        }
                     }
                    }
                    array_push($createsItem,$Item_id);
                    $insertedItemCount++;
                    }else{
                        $insertedItemCountNotExist++;
                    }
                  }else{
                    $notInsertedItemCount++;
                  }
                }  
            }
           
                        
                    $table = '<div class="row">
                    <div id="datatable">
                    <div id="grid_table">
                        <table class="jsgrid-table">
                            <tr class="jsgrid-header-row">
                                <th class="jsgrid-header-cell jsgrid-align-center">S.No.</th>
                                <th class="jsgrid-header-cell jsgrid-align-center">Company</th>
                                <th class="jsgrid-header-cell jsgrid-align-center">Item Name</th>
                                <th class="jsgrid-header-cell jsgrid-align-center">Code</th>
                                <th class="jsgrid-header-cell jsgrid-align-center">Search Keywords</th>
                                <th class="jsgrid-header-cell jsgrid-align-center">HSNCode</th>
                                <th class="jsgrid-header-cell jsgrid-align-center">IGST</th>
                                <th class="jsgrid-header-cell jsgrid-align-center">Unit Type</th>
                                <th class="jsgrid-header-cell jsgrid-align-center">DPCO/NON DPCO</th>
                                <th class="jsgrid-header-cell jsgrid-align-center">Return</th>
                                <th class="jsgrid-header-cell jsgrid-align-center">Qty</th>
                                <th class="jsgrid-header-cell jsgrid-align-center">MRP</th>
                                <th class="jsgrid-header-cell jsgrid-align-center">Selling Rate</th>
                            </tr>';
                $x = 1;
                foreach ($createsItem as $ID) {
                    $rs = $this->master_model->getRow('products_subcategory', ['id' => $ID]);
                    $inventory = $this->master_model->getRow('shops_inventory', ['product_id' => $ID]);
                    $Comp = $this->master_model->getRow('brand_master',['id'=>$rs->brand_id]);
                    $UnitRow = $this->master_model->getRow('unit_master',['id'=>$rs->unit_type_id]);
                    $dpcoName = $this->master_model->getRow('dpco',['id'=>$rs->dpco]);
                    $IGSTNAME = $this->master_model->getRow('tax_slabs',['id'=>$rs->tax_id]);
                    $table .= '<tr class="jsgrid-filter-row">
                                    <th class="jsgrid-cell jsgrid-align-center">' . $x . '</th>
                                    <td class="jsgrid-cell jsgrid-align-center">' . $Comp->name . '</td>
                                    <td class="jsgrid-cell jsgrid-align-center">' . $rs->name . '</td>
                                    <td class="jsgrid-cell jsgrid-align-center">' . $rs->product_code . '</td>
                                    <td class="jsgrid-cell jsgrid-align-center">' . $rs->search_keywords . '</td>
                                    <td class="jsgrid-cell jsgrid-align-center">' . $rs->sku . '</td>
                                    <td class="jsgrid-cell jsgrid-align-center">' . $IGSTNAME->slab . '</td>
                                    <td class="jsgrid-cell jsgrid-align-center">' . $UnitRow->name . '</td>
                                    <td class="jsgrid-cell jsgrid-align-center">' . $dpcoName->title . '</td>
                                    <td class="jsgrid-cell jsgrid-align-center">' . $rs->is_return . '</td>
                                    <td class="jsgrid-cell jsgrid-align-center">' . $inventory->qty . '</td>
                                    <td class="jsgrid-cell jsgrid-align-center">' . $inventory->mrp . '</td>
                                    <td class="jsgrid-cell jsgrid-align-center">' . $inventory->selling_rate . '</td>
                                </tr>';
                    $x++;
                }

                $table .= '</div>';
           $return['table']=$table;
           $return['res']='success';
           $return['msg'] = $insertedItemCount . ' Item(s) have been inserted. ' . $insertedItemCountNotExist . ' Item(s) were not inserted or empty category were not inserted' .$notInsertedItemCount;
        }
          
            echo json_encode($return);
        }
     break;   
                
     default:
    // code...
    break;
  }
}


public function importExcelInventoryData($action=null,$p1=null)
{
switch ($action) {
case null:
    $data['menu_id'] = $this->uri->segment(2);
    $data['title']          = 'Excel Inventory Data Import';
    $page                   = 'admin/master/data_import_inventory/index';
    $data['business']  = $this->master_model->view_data('business');
    $data['action_url']	  	= base_url('Import-excel-inventory/data_import_post');
    $data['menu_url'] = $this->uri->segment(1);
    $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
    $this->header_and_footer($page, $data); 
break;
case 'data_import_post':
    $data['user']  = $user         = checkLogin();
    $return['res'] = 'error';
    $return['msg'] = 'Not Saved!';
     if ($this->input->server('REQUEST_METHOD')=='POST') { 
     $shop_id = $this->input->post('shop');   
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
        $CountItem = 0;
        $MRP = 0;
        $insertedItemCount = 0;
        $notInsertedItemCount = $insertedItemCountNotExist=0;
        $headerSkipped = false;
        $createsItem = array(); 
        foreach ($sheetData as $data) {
            if (!$headerSkipped) {
                $headerSkipped = true;
                continue; 
            }
            if (!empty($data)) {
                $ItemCode = trim($data[1]);
                $QTY = trim($data[2]);
                $Selling_Rate = trim($data[3]);
                $MRP = trim($data[4]);                    
                 
                if(!empty($ItemCode)){
                 $CheckItemCodeExist = $this->master_model->CheckItemCodeExist($ItemCode);
                 if($CheckItemCodeExist)
                  {
                $CheckItemInventory = $this->master_model->CheckItemInventory($CheckItemCodeExist);
                if($CheckItemInventory){
                    $Inventory = $this->master_model->getRow('shops_inventory',['product_id'=>$CheckItemCodeExist]);
                    $qtyinsert = $QTY+$Inventory->qty;
                    $sellinginsert = $Selling_Rate+$Inventory->selling_rate;
                    $mrpinsert = $MRP+$Inventory->mrp;

                    $inventory_data = array(
                        'product_id'    => $CheckItemCodeExist,
                        'qty'           => $qtyinsert,
                        'purchase_rate' => $sellinginsert,
                        'selling_rate'  => $sellinginsert,
                        'mrp'           => $mrpinsert,
                        'shop_id'       => $shop_id,
                    );
                    if($this->master_model->Update('shops_inventory',$inventory_data,['id'=>$Inventory->id]))
                    {
                       $inventory_data['action']="UPDATE";
                       $inventory_data['shops_inventory_id']=$Inventory->id;
                       if ($insert_id = $this->master_model->add_data('shop_inventory_logs', $inventory_data)) {
                       }
                    }
                }else{
                    $inventory_data = array(
                        'product_id'    => $CheckItemCodeExist,
                        'qty'           => isset($QTY) ? $QTY : 0,
                        'purchase_rate' => isset($Selling_Rate) ? $Selling_Rate : 0,
                        'selling_rate'  => isset($Selling_Rate) ? $Selling_Rate : 0,
                        'mrp'           => isset($MRP) ? $MRP : 0,
                        'shop_id'       => $shop_id,
                    );
                 if($insert_stock = $this->master_model->Save('shops_inventory',$inventory_data))
                 {
                    $inventory_data['action']="ADD";
                    $inventory_data['shops_inventory_id']=$insert_stock;
                    if ($insert_id = $this->master_model->add_data('shop_inventory_logs', $inventory_data)) {
                    }
                 }
                }
                array_push($createsItem,$CheckItemCodeExist);
                $insertedItemCount++;
                }else{
                    $insertedItemCountNotExist++;
                }
              }else{
                $notInsertedItemCount++;
              }
            }  
        }
       
                    
                $table = '<div class="row">
                <div id="datatable">
                <div id="grid_table">
                    <table class="jsgrid-table">
                        <tr class="jsgrid-header-row">
                            <th class="jsgrid-header-cell jsgrid-align-center">S.No.</th>
                            <th class="jsgrid-header-cell jsgrid-align-center">Company</th>
                            <th class="jsgrid-header-cell jsgrid-align-center">Item Name</th>
                            <th class="jsgrid-header-cell jsgrid-align-center">Code</th>
                            <th class="jsgrid-header-cell jsgrid-align-center">Search Keywords</th>
                            <th class="jsgrid-header-cell jsgrid-align-center">HSNCode</th>
                            <th class="jsgrid-header-cell jsgrid-align-center">IGST</th>
                            <th class="jsgrid-header-cell jsgrid-align-center">Unit Type</th>
                            <th class="jsgrid-header-cell jsgrid-align-center">DPCO/NON DPCO</th>
                            <th class="jsgrid-header-cell jsgrid-align-center">Return</th>
                            <th class="jsgrid-header-cell jsgrid-align-center">Qty</th>
                            <th class="jsgrid-header-cell jsgrid-align-center">MRP</th>
                            <th class="jsgrid-header-cell jsgrid-align-center">Selling Rate</th>
                        </tr>';
            $x = 1;
            foreach ($createsItem as $ID) {
                $rs = $this->master_model->getRow('products_subcategory', ['id' => $ID]);
                $inventory = $this->master_model->getRow('shops_inventory', ['product_id' => $ID]);
                $Comp = $this->master_model->getRow('brand_master',['id'=>$rs->brand_id]);
                $UnitRow = $this->master_model->getRow('unit_master',['id'=>$rs->unit_type_id]);
                $dpcoName = $this->master_model->getRow('dpco',['id'=>$rs->dpco]);
                $IGSTNAME = $this->master_model->getRow('tax_slabs',['id'=>$rs->tax_id]);
                $table .= '<tr class="jsgrid-filter-row">
                                <th class="jsgrid-cell jsgrid-align-center">' . $x . '</th>
                                <td class="jsgrid-cell jsgrid-align-center">' . $Comp->name . '</td>
                                <td class="jsgrid-cell jsgrid-align-center">' . $rs->name . '</td>
                                <td class="jsgrid-cell jsgrid-align-center">' . $rs->product_code . '</td>
                                <td class="jsgrid-cell jsgrid-align-center">' . $rs->search_keywords . '</td>
                                <td class="jsgrid-cell jsgrid-align-center">' . $rs->sku . '</td>
                                <td class="jsgrid-cell jsgrid-align-center">' . $IGSTNAME->slab . '</td>
                                <td class="jsgrid-cell jsgrid-align-center">' . $UnitRow->name . '</td>
                                <td class="jsgrid-cell jsgrid-align-center">' . $dpcoName->title . '</td>
                                <td class="jsgrid-cell jsgrid-align-center">' . $rs->is_return . '</td>
                                <td class="jsgrid-cell jsgrid-align-center">' . $inventory->qty . '</td>
                                <td class="jsgrid-cell jsgrid-align-center">' . $inventory->mrp . '</td>
                                <td class="jsgrid-cell jsgrid-align-center">' . $inventory->selling_rate . '</td>
                            </tr>';
                $x++;
            }

            $table .= '</div>';
       $return['table']=$table;
       $return['res']='success';
       $return['msg'] = $insertedItemCount . ' Item(s) inventory have been inserted. ' . $insertedItemCountNotExist . ' Item(s) inventory were not inserted or empty product code were not inventory inserted' .$notInsertedItemCount;
    }
      
        echo json_encode($return);
    }
 break;   
            
 default:
// code...
break;
}
}



public function in_inventory($action=null,$p1=null,$p2=null,$p3=null)
{
  $data['user']  = $user         = checkLogin();
    switch ($action) {
        case null:
            $data['menu_id'] = $this->uri->segment(2);
            $data['title']          = 'Product Inventory';
            $page                   = 'admin/master/inventory/index';
            $data['business']  = $this->offers_model->view_data('business');
            $data['shops']  = $this->offers_model->view_data('shops');
            $data['action_url']         = base_url().'in-inventory/save';
            $data['sub_action_url']         = base_url().'in-inventory/sub_save';
            $data['menu_url'] = $this->uri->segment(1);
            $data['breadcrumb']    = generate_breadcrumb($data['menu_url']);
            $this->header_and_footer($page, $data);
            break;
           case 'save':
            $id = $p1;
            $return['res'] = 'error';
            $return['msg'] = 'Not Saved!';

            if ($this->input->server('REQUEST_METHOD')=='POST') { 
             $shop_inventry_id = $this->input->post('inventory_id');
              $product_id = $this->input->post('product_id');
              $exist_qty = $this->input->post('exist_qty');
              $shop_id = $this->input->post('shopid');
                 if(!empty($shop_id))
                 {
                  $shopid =  $shop_id;
                 }else
                 {
                   $shopid ='6';
                 }
              $count = $this->master_model->Counter('shops_inventory', array('product_id'=>$product_id));
             
              if ($count >=1) {
                  $data_shop_inventry = array(
                      'qty'=>($exist_qty+$this->input->post('s_qty')),
                      'purchase_rate'=>$this->input->post('purchase_rate'),
                      'mrp'=>$this->input->post('selling_rate'),
                      'selling_rate'=>$this->input->post('selling_rate'),
                      'shop_id'=>$shopid,
                      'product_id'=>$product_id,
                  );
                  $data_shop_inventry_log = array(
                      'product_id'=>$product_id,
                      'qty'=>$this->input->post('s_qty'),
                      'purchase_rate'=>$this->input->post('purchase_rate'),
                      'mrp'=>$this->input->post('selling_rate'),
                      'selling_rate'=>$this->input->post('selling_rate'),
                      'shop_id'=>$shopid,
                      
                  );

                  if ($this->master_model->edit_data('shops_inventory', $shop_inventry_id, $data_shop_inventry)) {
                      logs($user->id,$shop_inventry_id,'EDIT','Edit Product Inventory');
                      $data_shop_inventry['action']="UPDATE_INVENTORY";
                      $data_shop_inventry['shops_inventory_id'] = $shop_inventry_id;
                      $data_shop_inventry_log['action']="UPDATE_INVENTORY";
                      $data_shop_inventry_log['shops_inventory_id'] = $shop_inventry_id;
                      if ($this->master_model->add_data('shop_inventory_logs', $data_shop_inventry_log)) {
                          $return['res'] = 'success';
                          $return['msg'] = 'Saved.';
                      }
                  }  
              }
              else{
                  
                  $data_shop_inventry_log = array(
                      'product_id'=>$product_id,
                      'qty'=>$this->input->post('s_qty'),
                      'purchase_rate'=>$this->input->post('purchase_rate'),
                      'mrp'=>$this->input->post('selling_rate'),
                      'selling_rate'=>$this->input->post('selling_rate'),
                      'shop_id'=>$shopid,
                      
                  );

                  if ($insert_stock = $this->master_model->add_data('shops_inventory', $data_shop_inventry_log)) {
                      logs($user->id,$insert_stock,'ADD','Add Product Inventory');
                      $data_shop_inventry_log['action']="ADD_INVENTORY"; $data_shop_inventry_log['shops_inventory_id']=$insert_stock;

                      if ($insert_id = $this->master_model->add_data('shop_inventory_logs', $data_shop_inventry_log)) {
                          $return['res'] = 'success';
                          $return['msg'] = 'Saved.';
                      }
                  }                                    
              }
            }
            echo json_encode($return);
            break;
            case 'sub_save':
              $id = $p1;
              $return['res'] = 'error';
              $return['msg'] = 'Not Saved!';

              if ($this->input->server('REQUEST_METHOD')=='POST') { 
               $shop_inventry_id = $this->input->post('inventory_id');
                $product_id = $this->input->post('product_id');
                $exist_qty = $this->input->post('exist_qty');
                $shop_id = $this->input->post('shopid');
                   if(!empty($shop_id))
                   {
                    $shopid =  $shop_id;
                   }else
                   {
                     $shopid ='6';
                   }
                $count = $this->master_model->Counter('shops_inventory', array('product_id'=>$product_id));
               
                if ($count >=1) {
                    $data_shop_inventry = array(
                        'qty'=>($exist_qty-$this->input->post('s_qty')),
                        'purchase_rate'=>$this->input->post('purchase_rate'),
                        'mrp'=>$this->input->post('selling_rate'),
                        'selling_rate'=>$this->input->post('selling_rate'),
                        'shop_id'=>$shopid,
                        'product_id'=>$product_id,
                    );
                    $data_shop_inventry_log = array(
                        'product_id'=>$product_id,
                        'qty'=>$this->input->post('s_qty'),
                        'purchase_rate'=>$this->input->post('purchase_rate'),
                        'mrp'=>$this->input->post('selling_rate'),
                        'selling_rate'=>$this->input->post('selling_rate'),
                        'shop_id'=>$shopid,
                        
                    );

                    if ($this->master_model->edit_data('shops_inventory', $shop_inventry_id, $data_shop_inventry)) {
                      logs($user->id,$shop_inventry_id,'EDIT','Subtract Product Inventory');
                        $data_shop_inventry_log['action']="SUB_INVENTORY";
                        $data_shop_inventry_log['shops_inventory_id'] = $shop_inventry_id;
                        if ($this->master_model->add_data('shop_inventory_logs', $data_shop_inventry_log)) {
                            $return['res'] = 'success';
                            $return['msg'] = 'Saved.';
                        }
                    }  
                }
                else{
                    
                    $data_shop_inventry_log = array(
                        'product_id'=>$product_id,
                        'qty'=>$this->input->post('s_qty'),
                        'purchase_rate'=>$this->input->post('purchase_rate'),
                        'mrp'=>$this->input->post('selling_rate'),
                        'selling_rate'=>$this->input->post('selling_rate'),
                        'shop_id'=>$shopid,
                        
                    );

                    if ($insert_stock = $this->master_model->add_data('shops_inventory', $data_shop_inventry_log)) {
                      logs($user->id,$insert_stock,'ADD','Add Product Inventory');
                        $data_shop_inventry_log['action']="ADD_INVENTORY"; $data_shop_inventry_log['shops_inventory_id']=$insert_stock;

                        if ($insert_id = $this->master_model->add_data('shop_inventory_logs', $data_shop_inventry_log)) {
                            $return['res'] = 'success';
                            $return['msg'] = 'Saved.';
                        }
                    }                                    
                }
              }
              echo json_encode($return);
              break;
            case 'getProductDetails':
              $search = $this->input->post('search');  
              $shop_id = $this->input->post('shop_id');
             $productData = $this->master_model->getProductDetails($shop_id,$search);
              // Return the data as JSON
              //print_r($productData);die();
              header('Content-Type: application/json');
              
              if(!empty($productData))
              {
                  $res = array(
                      'error'=>'false',
                      'data'=>$productData
                    );
              }else
              {
                  $res = array(
                      'error'=>'true',
                    );
              }
             
             echo json_encode($res);
           break; 
           case 'getProductValue':
              $pro_id = $this->input->post('pro_id');   // Assuming the 'mobile' is sent via POST
              
              // Load the model and get the user data by mobile
             $productData = $this->order_items_model->get_value($pro_id);
              // Return the data as JSON
              header('Content-Type: application/json');
              if(!empty($productData))
              {
                  $res = array(
                      'error'=>'false',
                      'data'=>$productData
                    );
              }else
              {
                  $res = array(
                      'error'=>'true',
                    );
              }
             
             echo json_encode($res);
           break;   

        default:
            # code...
            break;
    }
}

public function generate_purchase_order_no($supplier_id) {
    $latest_purchase_order_no = $this->master_model->get_latest_purchase_order_no($supplier_id);

    if ($latest_purchase_order_no) {
        // Extract the numeric part of the latest purchase order number
        $numeric_part = substr($latest_purchase_order_no, 1);
        // Increment the numeric part by 1
        $new_numeric_part = str_pad((int)$numeric_part + 1, 5, '0', STR_PAD_LEFT);
        // Append the suffix 'A'
        $new_purchase_order_no = 'A' . $new_numeric_part;
    } else {
        // No existing records, start with 'A00001'
        $new_purchase_order_no = 'A00001';
    }

    return $new_purchase_order_no;
}





    
}