<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mshop_model extends CI_Model {

    public $errors;
    public $messages;
    public $basePath;
    public $apiResponce;
    public $apiUrl;

    public function __construct() {
        parent::__construct();

        //initialize messages and error
        $this->messages = array();
        $this->errors = array();
        $this->basePath = base_url();
        $this->apiResponce = '';
        $this->apiUrl = '';
    }

    public function postUrl($url, $data = array()) {

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $postDataArr[] = $key . "=" . $value;
            }

            $postData = join('&', $postDataArr);
        } else {
            $postData = '';
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
                "postman-token: 3bda5de7-1610-baef-2618-ff16b9dce0da"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "API Error :" . $err;
            exit;
        } else {
            return $response;
        }
    }

    public function isJSON($string) {
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }

    public function JSon2Arr($string) {

        if ($this->isJSon($string)) {
            return (array) json_decode($string, true);
        }

        return $string;
    }

    public function Arr2JSon(array $arr) {

        if (is_array($arr)) {
            return json_encode($arr, true);
        }

        return $arr;
    }

    public function get_shop_settings() {

        $q = $this->db->get('eshop_settings');

        if ($q->num_rows() > 0) {
            $data = (array) $q->result();
            return $data[0];
        }
        return false;
    }

    /**
     * 
     * @return boolean
     */
    public function session_authenticate() {

        if ($this->session->has_userdata('id') && $this->session->has_userdata('auth_token')) {
            return $this->session->has_userdata('id');
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $id
     * @return int
     */
    public function getCustomerByID($id) {
        $q = $this->db->select('id,name,email,phone,mobile_verification_code,email_verification_code,email_is_verified,mobile_is_verified,address,city,state,country,postal_code,company,vat_no,gstn_no,logo')
                ->get_where('companies', array('id' => $id), 1);

        if ($q->num_rows() > 0) {
            return (array) $q->row();
        }
        return 0;
    }

    public function getCustomerByEmail($email) {
        $q = $this->db->select('id,name,email,phone,mobile_verification_code,email_verification_code,email_is_verified,mobile_is_verified')
                ->get_where('companies', array('email' => $email), 1);
        if ($q->num_rows() > 0) {
            return (array) $q->row();
        }
        return 0;
    }

    /**
     * 
     * @param type $condition // Array Type
     * @return int
     */
    public function getCustomerDetails($condition) {
        $q = $this->db->select('id,name,email,phone,mobile_verification_code,email_verification_code,email_is_verified,mobile_is_verified,address,city,state,country,postal_code,company,vat_no,gstn_no,logo')
                ->get_where('companies', $condition, 1);
        if ($q->num_rows() > 0) {
            return (array) $q->row();
        }
        return 0;
    }

    /**
     * 
     * @param type $arr
     * @return boolean
     */
    public function getCompanyCustomer($arr) {
        if (is_array($arr)):
            $q = $this->db->get_where('companies', $arr, 1);
            if ($q->num_rows() > 0) {
                return $q->row();
            }
        endif;
        return FALSE;
    }

    /**
     * 
     * @param type $id
     * @param int $data
     * @return boolean
     */
    public function updateCompany($id, $data = array()) {
        $this->db->where('id', $id);
        if (!isset($data['is_synced'])):
            $data['is_synced'] = 0;
        endif;
        if ($this->db->update('companies', $data)) {
            if ($data['group_id'] == 3 && $data['is_synced'] != 1):
                $coustmer = $this->getCompanyByID($id);
                $this->load->library('sma');
                $this->sma->SyncCustomerData($coustmer);
            endif;
            return true;
        }
        return false;
    }

    /**
     * Whishlist Product Get
     * @param type $userId
     * @return type
     */
    public function getWishListItems($userId) {
        $q = $this->db->select('sma_eshop_wishlist.*, sma_products.name, sma_products.price, sma_products.image,sma_product_variants.name as option_name,sma_product_variants.price as option_price ')
                ->join('sma_products', 'sma_eshop_wishlist.product_id = sma_products.id', 'inner')
                ->join('sma_product_variants', 'sma_product_variants.id = sma_eshop_wishlist.option_id', 'left')
                ->where('user_id', $userId)
                ->order_by('sma_eshop_wishlist.id', 'desc')
                ->get('sma_eshop_wishlist');
        $count = $q->num_rows();
        if ($count > 0)
            $result = $q->result_array();
        return array('count' => $count, 'result' => $result);
    }

    /**
     * Get Users Orders
     * @param type $param
     * @return boolean
     */
    public function getOrdersByUser($param) {
        $User_id = isset($param['user_id']) && !empty($param['user_id']) ? $param['user_id'] : NULL;
        $limit = isset($param['limit']) && !empty($param['limit']) ? $param['limit'] : NULL;
        $offset = isset($param['offset']) && !empty($param['offset']) ? $param['offset'] : 0;
        $sort_field = isset($param['sort_field']) && !empty($param['sort_field']) ? $param['sort_field'] : 'orders.id'; //'sales.id';
        $sort_dir = isset($param['sort_dir']) && !empty($param['sort_dir']) ? $param['sort_dir'] : 'desc';
        $search_by = isset($param['search_by']) && !empty($param['search_by']) ? $param['search_by'] : NULL;
        $search_param = isset($param['search_param']) && !empty($param['search_param']) ? $param['search_param'] : NULL;

        if (!empty($search_by) && is_array($search_param)):
            switch ($search_by) {
                case 'order_ref':
                    if (empty($search_param['order_ref'])):
                        return false;
                    endif;
                    $this->db->where('orders.reference_no', $search_param['order_ref']);
                    break;

                case 'order_date':
                    if (empty($search_param['order_date1']) || empty($search_param['order_date2'])):
                        return false;
                    endif;
                    $this->db->where('date(orders.`date`) between  ' . " '" . $search_param['order_date1'] . "'  and '" . $search_param['order_date2'] . "' ");
                    break;

                case 'pay_status':
                    if (empty($search_param['pay_status'])):
                        return false;
                    endif;
                    $this->db->where('orders.payment_status', $search_param['pay_status']);
                    break;

                case 'pay_ref':
                    if (empty($search_param['pay_ref'])):
                        return false;
                    endif;
                    $this->db->where('payments.reference_no', $search_param['pay_ref']);
                    break;

                case 'pay_trans':
                    if (empty($search_param['pay_trans'])):
                        return false;
                    endif;
                    $this->db->where('payments.transaction_id', $search_param['pay_trans']);
                    break;

                default:
                    break;
            }
        endif;

        if (empty($User_id)):
            return false;
        endif;


        $this->db->select("orders.id as order_id,orders.grand_total as grand_total,orders.rounding as rounding , orders.delivery_status as order_delivery_status , orders.invoice_no as order_number,orders.reference_no as order_no,DATE_FORMAT(sma_orders.date,'%b %d %Y %h:%i %p') as order_date,"
                . "orders.payment_status ,orders.sale_status ,payments.reference_no as payment_no,payments.transaction_id as transaction_no"
                . ", deliveries.do_reference_no  as delivery_reference_no"
                . ", deliveries.status  as delivery_status"
        );
        $this->db->from('orders');
        $this->db->join('payments', 'orders.id =  payments.order_id', 'left');
        $this->db->join('deliveries', 'orders.id =  deliveries.order_id', 'left');
        $this->db->order_by('orders.date', 'desc');
        $this->db->where('orders.customer_id', $User_id);
        $this->db->where("orders.eshop_sale='1'");
        $this->db->where("orders.sale_status!='completed'");
        //--------------SORT ------------------------------
        if (!empty($sort_field) && !empty($sort_dir)):
            $this->db->order_by($sort_field, $sort_dir);
        endif;

        //--------------Limit ------------------------------
        if (!empty($limit) && !empty($offset)):
            $this->db->limit($limit, $offset);
        endif;

        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            $i = 1;
            foreach (($q->result()) as $row) {
                if (!empty($row->delivery_status)):
                    $row->order_status = @ucfirst($row->delivery_status);
                elseif (!empty($row->payment_status)):
                    $row->order_status = ($row->payment_status == 'due') ? 'Payment due' : @ucfirst($row->payment_status);
                else :
                    $row->order_status = 'Payment due';
                endif;

                $data[] = $row;
                $i++;
            }
            return $data;
        }
        return FALSE;
    }

    /**
     * Get Users Sales
     * @param type $param
     * @return boolean
     */
    public function getEshopSalesByUser($param) {
        $User_id = isset($param['user_id']) && !empty($param['user_id']) ? $param['user_id'] : NULL;
        $limit = isset($param['limit']) && !empty($param['limit']) ? $param['limit'] : NULL;
        $offset = isset($param['offset']) && !empty($param['offset']) ? $param['offset'] : 0;
        $sort_field = isset($param['sort_field']) && !empty($param['sort_field']) ? $param['sort_field'] : 'sales.id'; //'sales.id';
        $sort_dir = isset($param['sort_dir']) && !empty($param['sort_dir']) ? $param['sort_dir'] : 'desc';
        $search_by = isset($param['search_by']) && !empty($param['search_by']) ? $param['search_by'] : NULL;
        $search_param = isset($param['search_param']) && !empty($param['search_param']) ? $param['search_param'] : NULL;

        if (!empty($search_by) && is_array($search_param)):
            switch ($search_by) {
                case 'order_ref':
                    if (empty($search_param['order_ref'])):
                        return false;
                    endif;
                    $this->db->where('sales.reference_no', $search_param['order_ref']);
                    break;

                case 'order_date':
                    if (empty($search_param['order_date1']) || empty($search_param['order_date2'])):
                        return false;
                    endif;
                    $this->db->where('date(sales.`date`) between  ' . " '" . $search_param['order_date1'] . "'  and '" . $search_param['order_date2'] . "' ");
                    break;

                case 'pay_status':
                    if (empty($search_param['pay_status'])):
                        return false;
                    endif;
                    $this->db->where('sales.payment_status', $search_param['pay_status']);
                    break;

                case 'pay_ref':
                    if (empty($search_param['pay_ref'])):
                        return false;
                    endif;
                    $this->db->where('payments.reference_no', $search_param['pay_ref']);
                    break;

                case 'pay_trans':
                    if (empty($search_param['pay_trans'])):
                        return false;
                    endif;
                    $this->db->where('payments.transaction_id', $search_param['pay_trans']);
                    break;

                default:
                    break;
            }
        endif;

        if (empty($User_id)):
            return false;
        endif;

        $this->db->select("sales.id as order_id,sales.grand_total as grand_total,sales.reference_no as order_no,  sales.delivery_status as sales_delivery_status , order_no as order_no_view, DATE_FORMAT(sma_sales.date,'%b %d %Y %h:%i %p') as order_date,"
                . "sales.payment_status ,sales.sale_status ,payments.reference_no as payment_no,payments.transaction_id as transaction_no"
                . ", deliveries.do_reference_no  as delivery_reference_no"
                . ", deliveries.status  as delivery_status"
        );
        $this->db->from('sales');
        $this->db->join('payments', 'sales.id =  payments.sale_id', 'left');
        $this->db->join('deliveries', 'sales.id =  deliveries.sale_id', 'left');
        $this->db->order_by('sales.date', 'desc');
        $this->db->where('sales.customer_id', $User_id);
        $this->db->where("sales.eshop_sale='1'");
        $this->db->where("sales.sale_status='completed'");

        //--------------SORT ------------------------------
        if (!empty($sort_field) && !empty($sort_dir)):
            $this->db->order_by($sort_field, $sort_dir);
        endif;

        //--------------Limit ------------------------------
        if (!empty($limit) && !empty($offset)):
            $this->db->limit($limit, $offset);
        endif;

        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            $i = 1;
            foreach (($q->result()) as $row) {
                if (!empty($row->delivery_status)):
                    $row->order_status = @ucfirst($row->delivery_status);
                elseif (!empty($row->payment_status)):
                    $row->order_status = ($row->payment_status == 'due') ? 'Payment due' : @ucfirst($row->payment_status);
                else :
                    $row->order_status = 'Payment due';
                endif;

                $data[] = $row;
                $i++;
            }
            return $data;
        }
        return FALSE;
    }

    /**
     * Get Customer Info
     * @return boolean
     */
    public function getCustomerInfo() {

        $id = $this->session->userdata('id');

        $q = $this->db->select('id,name, company, vat_no, address, city, state, postal_code, country, phone, email, dob, gstn_no')
                ->where(array('group_name' => 'customer', 'id' => $id))
                ->get('companies');

        if ($q->num_rows() > 0) {
            $data = (array) $q->result();
            return $data[0];
        }

        return false;
    }

    /**
     * 
     * @param array $data
     * @param type $id
     * @return boolean
     */
    public function updateCustomerInfo(array $data, $id = '') {

        if (!$id) {
            $id = $this->session->userdata('id');
        }

        $this->db->where('id', $id);
        $q = $this->db->update('companies', $data);

        if ($q) {
            return true;
        }

        return false;
    }

    /**
     * Auth Customer
     * @param array $param
     * @return string
     */
    public function getAuthCustomer(array $param) {

        $loginid = $param['login_id'];
        $password = md5($param['password']);
        $data['status'] = 'ERROR';
        $where = "password='$password' AND ( email='$loginid' OR phone='$loginid' )";

        $this->db->select('id,name,email,phone,mobile_verification_code,email_verification_code,email_is_verified,mobile_is_verified');
        $this->db->where($where);
        $this->db->limit(1, 0);
        $q = $this->db->get('companies');
        //  echo $this->db->last_query(); 

        if ($q->num_rows() > 0) {
            $data['status'] = 'SUCCESS';
            $data['result'] = $q->result_array();
        } else {
            $data['error'] = "Invalid User Input";
        }

        return $data;
    }

    /**
     * Google Email Check
     * */
    public function getAuthCustomergoogle(array $param) {

        $loginid = $param['login_id'];
        $password = md5($param['password']);
        $data['status'] = 'ERROR';
        //password='$password' AND 
        $where = "( email='$loginid' OR phone='$loginid' )";

        $this->db->select('id,name,email,phone,mobile_verification_code,email_verification_code,email_is_verified,mobile_is_verified');
        $this->db->where($where);
        $this->db->limit(1, 0);
        $q = $this->db->get('companies');
        //  echo $this->db->last_query(); 

        if ($q->num_rows() > 0) {
            $data['status'] = 'SUCCESS';
            $data['result'] = $q->result_array();
        } else {
            $data['error'] = "Invalid User Input";
        }

        return $data;
    }

    
    /**
     * Set Session
     */
    
    public function set_user_session($userData) {

        $this->session->set_userdata($userData);
    }

    /**
     * 
     * @param type $arr
     * @return boolean
     */
    public function getStaticPages($arr) {
        if (is_array($arr)):
            $q = $this->db->get_where('eshop_pages', $arr, 1);
            if ($q->num_rows() > 0) {
                return $q->row();
            }
        endif;
        return FALSE;
    }
    
      /**
     * OTP action
     * 
     * @param type $key
     * @param type $data
     * @param type $update
     * @return type
     */
    public function otp_action($key, $data, $update = NULL){
        switch ($key){

            case 'Update':
                
                    $this->db->where($data)->update('sma_companies',$update);
                    return ($this->db->affected_rows())?TRUE:FALSE;
                break;
            
            case 'check':
                    $q = $this->db->select('id,phone,mobile_verification_code')->where($data)->get('sma_companies')->row();
                    return ($this->db->affected_rows())?$q:FALSE;
                break;
            
            
            
        }
    }

    /**
     * 
     * @param type $loginid
     * @return int
     */
    public function getCustomerByloginId($loginid) {
        $q = $this->db->select('id,name,email,phone,mobile_verification_code,email_verification_code,email_is_verified,mobile_is_verified')
                ->where(['phone' => $loginid])
                ->or_where(['email' => $loginid])
                ->get('companies');
        if ($q->num_rows() > 0) {
            return (array) $q->row();
        }
        return 0;
    }
    
     /**
     * 
     * @param type $userid
     * @param type $imagename
     */
    public function uploadphoto($userid, $imagename){
        $getdata = $this->db->select('logo')->where(['id'=>$userid])->get('companies')->row();
        if($getdata->logo){
           $file_pointer = 'assets/uploads/avatars/'.$getdata->logo;
            if (file_exists($file_pointer)) {
                unlink($file_pointer);
            }
        }
        $datavalue = ['logo' =>$imagename];
            
        $this->db->where(['id'=>$userid])->update('companies',$datavalue);
        
        
    }

/**
     * Guest Login
     */
    public function guestlogin($data) {
        $this->db->where(['phone' => $data['phone']]);
        if ($data['email']) {
            $this->db->or_where(['email' => $data['email']]);
        }
        $sql = $this->db->get('companies')->row();

        if ($sql) {
            return $sql;
        } else {
            $data = array('name' => $data['name'],
                'email' => $data['email'],
                'group_id' => '3',
                'group_name' => 'customer',
                'customer_group_id' => '1',
                'customer_group_name' => 'General',
                'price_group_id' => '2',
                'is_synced' => '0',
                'price_group_name' => 'Standered',
                'company' => '-',
                'country' => 'India',
                'phone' => $data['phone'],
            );

            $this->db->insert('companies', $data);
            $insert_id = $this->db->insert_id();
            $sql = $this->db->where(['id' => $insert_id])->get('companies')->row();
            return $sql;
        }
    }

    /**
     * End Guest Login
     */
   
}
