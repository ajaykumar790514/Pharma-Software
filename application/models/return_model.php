<?php 

/**
 * 
 */
class Return_model extends CI_Model
{
	
	function __construct()
    {
        $this->load->database();
    }




    public function get_sales_return()
    {
        $rows = [];
        if (@$_POST['customer_id'] or @$_POST['product_id']):

            $customer_id = $_POST['customer_id'];
            $product_id  = $_POST['product_id'];

            if (@$_POST['customer_id'] && @$_POST['product_id']) {
                $where = "mtb.customer_id = $customer_id AND mtb.product_id = $product_id";
            }
            elseif (@$_POST['customer_id']) {
                $where = "mtb.customer_id = $customer_id";
            }
            elseif (@$_POST['product_id']) {
                $where = "p.id = $product_id";
            }

           
            $query = "SELECT mtb.*, 
                            p.name as product_name,
                            CONCAT(v.fname, ' ', v.mname, ' ', v.lname) AS customer_name
                        FROM sales_return mtb
                        LEFT JOIN products_subcategory p on p.id = mtb.product_id
                        LEFT JOIN customer_personal_details v on v.customer_id = mtb.customer_id
                        WHERE $where
                        ORDER BY mtb.date DESC
                        ";

            $rows = $this->db->query($query)->result();
            // echo $this->db->last_query();
        endif;

        return $rows;
    }

    // public function get_purchase_return()
    // {
    //     $rows = [];
    //     $where1="mtb.is_deleted = NOT_DELETED";
    //     if (@$_POST['vendor_id'] or @$_POST['product_id']):
    //         $vendor_id = $_POST['vendor_id'];
    //         $product_id  = $_POST['product_id'];

    //         if (@$_POST['vendor_id'] && @$_POST['product_id']) {
    //             $where = "mtb.vendor_id = $vendor_id AND mtb.product_id = $product_id";
    //         }
    //         elseif (@$_POST['vendor_id']) {
    //             $where = "mtb.vendor_id = $vendor_id";
    //         }
    //         elseif (@$_POST['product_id']) {
    //             $where = "p.id = $product_id";
    //         }

    //         $query = "SELECT mtb.*, 
    //         p.name as product_name,
    //         CONCAT(v.fname, ' ', v.lname) AS customer_name
    //     FROM purchase_return mtb
    //     LEFT JOIN products_subcategory p ON p.id = mtb.product_id
    //     LEFT JOIN customers v ON v.id = mtb.vendor_id
    //     WHERE $where AND $where1
    //     ORDER BY mtb.date DESC";


    //         $rows = $this->db->query($query)->result();
    //         // echo $this->db->last_query();
    //     endif;

    //     return $rows;
    // }
    public function get_purchase_return()
{
    $rows = [];
    $this->db->select('mtb.*, p.name as product_name, CONCAT(v.fname, " ", v.lname) AS customer_name');
    $this->db->from('purchase_return mtb');
    $this->db->join('products_subcategory p', 'p.id = mtb.product_id', 'left');
    $this->db->join('customers v', 'v.id = mtb.vendor_id', 'left');
    $this->db->where('mtb.is_deleted', 'NOT_DELETED');

    if (@$_POST['vendor_id'] || @$_POST['product_id']) {
        if (@$_POST['vendor_id'] && @$_POST['product_id']) {
            $this->db->where('mtb.vendor_id', $_POST['vendor_id']);
            $this->db->where('mtb.product_id', $_POST['product_id']);
        } elseif (@$_POST['vendor_id']) {
            $this->db->where('mtb.vendor_id', $_POST['vendor_id']);
        } elseif (@$_POST['product_id']) {
            $this->db->where('p.id', $_POST['product_id']);
        }
    }

    $this->db->order_by('mtb.date', 'DESC');
    $query = $this->db->get();
    $rows = $query->result();

    return $rows;
}


public function get_purchase_return_update($id)
{
    $rows = [];
    $this->db->select('mtb.*, p.name as product_name, CONCAT(v.fname, " ", v.lname) AS customer_name,p.id as product_id');
    $this->db->from('purchase_return mtb');
    $this->db->join('products_subcategory p', 'p.id = mtb.product_id', 'left');
    $this->db->join('customers v', 'v.id = mtb.vendor_id', 'left');
    $this->db->where('mtb.is_deleted', 'NOT_DELETED');
    $this->db->where('mtb.id', $id);
    $query = $this->db->get();
    $rows = $query->row();

    return $rows;
}







    public function get_stocks($shop_id,$pro_id)
    {
        // echo $pro_id;

    	$date = "'".date('Y-m-d')."'";
    	$query = "SELECT mtb.*, CONCAT(v.fname, ' ', v.lname) AS vendor_name, v.vendor_code AS vendor_code
          FROM shops_inventory mtb
          LEFT JOIN customers v ON v.id = mtb.vendor_id
          WHERE mtb.product_id = $pro_id
            AND mtb.shop_id = $shop_id
            AND mtb.status = 1
            AND mtb.is_deleted = 'NOT_DELETED'
            -- AND mtb.expiry_date > $date
            AND v.is_deleted = 'NOT_DELETED'
            AND v.isActive = 1
          ORDER BY mtb.id DESC
          LIMIT 5";


    	$result = $this->db->query($query)->result();
    	//  echo $this->db->last_query();

    	//  echo _prx($result);
    	return $result;
    }

    public function get_stocks_purchase($shop_id, $pro_id, $vendor_id)
    {
        // Prepare the SQL query with placeholders for parameters
        $query = "
            SELECT mtb.*, CONCAT(v.fname, ' ', v.lname) AS vendor_name, v.vendor_code AS vendor_code
            FROM shops_inventory mtb
            LEFT JOIN customers v ON v.id = mtb.vendor_id
            WHERE mtb.product_id = ?
              AND mtb.shop_id = ?
              AND mtb.vendor_id = ?
              AND mtb.qty != 0
              AND mtb.status = 1
              AND mtb.is_deleted = 'NOT_DELETED'
              AND v.is_deleted = 'NOT_DELETED'
              AND v.isActive = 1
            ORDER BY mtb.expiry_date ASC
            LIMIT 5";
    
        // Execute the query using parameterized inputs
        $result = $this->db->query($query, array($pro_id, $shop_id, $vendor_id))->result();
    
    
        return $result;
    }
    
    public function get_stocks_purchase_update($shop_id, $pro_id, $vendor_id) {
        $this->db->select('mtb.*, CONCAT(v.fname, " ", v.lname) AS vendor_name, v.vendor_code as vendor_code');
        $this->db->from('shops_inventory mtb');
        $this->db->join('customers v', 'v.id = mtb.vendor_id', 'left');
        $this->db->where('mtb.id', $pro_id);
        $this->db->where('mtb.shop_id', $shop_id);
        $this->db->where('mtb.vendor_id', $vendor_id);
        $this->db->where('mtb.status', 1);
        $this->db->where('mtb.is_deleted', 'NOT_DELETED');
        $this->db->where('v.is_deleted', 'NOT_DELETED');
        $this->db->where('v.isActive', 1);
        $result = $this->db->get()->result();
        //  echo $this->db->last_query();die();
        return $result;
    }
    

    public function report()
    {
        $data['user']  = $user         = $this->checkShopLogin();
        $result = false;
        $where = '';
        if (@$_POST['from_date'] && @$_POST['to_date'] && (@$_POST['is_Customer']=='on' or @$_POST['is_Vendor']=='on')) {
            $shop_id     = $user->id;
            $f_date = $_POST['from_date'];
            $t_date = $_POST['to_date'];
            $customer_id = @$_POST['business_id'];

            $tb = (@$_POST['is_Customer']=='on') ? 'sales_return' : 'purchase_return' ;
            $_POST['type'] = $tb;
            if (@$_POST['business_id']) {
                $where = " AND mtb.vendor_id = $customer_id ";
            }

            if ($tb == 'sales_return') {

                if (@$_POST['business_id']) {
                    $where = " AND mtb.customer_id = $customer_id ";
                }
                $query = "SELECT mtb.*, 
                            p.name as product_name,
                            p.product_code as product_code,
                            CONCAT(v.fname, ' ', v.lname) as name,
                            v.vendor_code as code,
                            p.id as prod_id,
                            pro.thumbnail
                        FROM sales_return mtb
                        LEFT JOIN products_subcategory p on p.id = mtb.product_id
                        LEFT JOIN customers v on v.id = mtb.customer_id
                        LEFT JOIN products_photo pro on pro.item_id = p.id
                        WHERE mtb.date >= '{$f_date}' 
                            AND mtb.date <= '{$t_date}'
                            AND pro.is_cover='1'
                            $where
                        ORDER BY mtb.date DESC";
            }
            else{
                // echo "string";
                if (@$_POST['business_id']) {
                    $where = " AND mtb.vendor_id = $customer_id ";
                }
                $query = "SELECT mtb.*, 
                            p.name as product_name,
                            p.product_code as product_code,
                            CONCAT(v.fname, ' ', v.lname) as name,
                            v.vendor_code as code,
                            p.id as prod_id,
                            pro.thumbnail
                        FROM purchase_return mtb
                        LEFT JOIN products_subcategory p on p.id = mtb.product_id
                        LEFT JOIN customers v on v.id = mtb.vendor_id
                        LEFT JOIN products_photo pro on pro.item_id = p.id
                        WHERE mtb.date >= '{$f_date}' 
                            AND mtb.date <= '{$t_date}'
                            AND pro.is_cover='1'
                            $where
                        ORDER BY mtb.date DESC";
            }

            $result = $this->db->query($query)->result();
                  
        }
        return $result;

    
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