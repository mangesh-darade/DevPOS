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

    public function get_categories() {
      
        $q = $this->db->select('id, code, name, image, parent_id')->order_by('name', 'asc')->get('categories');
        
        if ($q->num_rows() > 0) {
            
            foreach ($q->result() as $row) {
                
                if((int)$row->parent_id > 0) {
                    $data[$row->parent_id][$row->id] = $row;
                } else {
                    $data['main'][$row->id] = $row;
                }
            }
             
            return $data;
        }
        return false;
   
    }

     public function getCategoryProducts($category_id, $pageno = 1, $itemsPerPage = 18) {
         
        if (is_numeric($category_id)) {
            $data['count'] = 0;
        }  

        $offset = ( $pageno - 1 ) * $itemsPerPage;

        for ($i = 1; $i <= 2; $i++) {

            if ($i == 1) {
                $this->db->select('p.`id`');
            } else {
                $this->db->select("p.`id`, p.`code`, p.`name`, p.`unit`, p.`price`, p.`quantity`, p.`image`, p.`tax_rate` AS tax_rate_id, t.`rate` AS tax_rate, t.`name` AS tax_name, p.`tax_method`, p.category_id, p.subcategory_id,"
                    . "p.`promotion`, p.`promo_price`, p.`start_date`, p.`end_date`, p.`sale_unit`, u.name AS unit_name, "
                    . "pv.id as option_id, pv.name as option_name, pv.price as option_price , pv.quantity as option_quantity ");                
            }
            
            $this->db->from('products AS p');
            
            $this->db->join('product_variants AS pv', 'p.id =  pv.product_id', 'left');
            $this->db->join('tax_rates AS t', 'p.tax_rate =  t.id', 'left');
            $this->db->join('units AS u', 'p.`sale_unit` =  u.id', 'left');
            
            $this->db->where(['p.category_id' => $category_id]);

            $this->db->or_where('p.subcategory_id', $category_id);

            if ($i == 2) {

                $offset = ($pageno - 1 ) * $itemsPerPage;

                $this->db->limit($itemsPerPage, $offset);
                //$this->db->limit($itemsPerPage);
            }
            $var = 'q' . $i;
            $$var = $this->db->get();
        }//end for.

        $count = $q1->num_rows();
        $data['count'] = $count;
        $data['totalPages'] = ceil($count / $itemsPerPage);

        if ($count > 0) {
            $data['msg'] = '<div class="alert alert-info">Result: ' . $count . ' products found.</div>';

            foreach (($q2->result()) as $row) {
                $data['items'][] = (array) $row;
            }
        } else {
            $data['msg'] = '<div class="alert alert-info">Products not found in this category</div>';
        }
        return $data;
    }
    
    
    public function getProducts($product_id, $variant_id=null) {
        
        $this->db->select("p.`id`, p.`code`, p.`name`, p.`unit`, p.`price`, p.`quantity`, p.`image`, p.`tax_rate` AS tax_rate_id, t.`rate` AS tax_rate, t.`name` AS tax_name, p.`tax_method`, p.category_id, p.subcategory_id, p.details, p.product_details, p.type AS product_type, p.brand, "
                    . "p.`promotion`, p.`promo_price`, p.`start_date`, p.`end_date`, p.`sale_unit`, u.name AS unit_name, "
                    . "pv.id as option_id, pv.name as option_name, pv.price as option_price , pv.quantity as option_quantity ");     
        
        $this->db->from('products AS p');
            
            $this->db->join('product_variants AS pv', 'p.id =  pv.product_id', 'left');
            $this->db->join('tax_rates AS t', 'p.tax_rate =  t.id', 'left');
            $this->db->join('units AS u', 'p.`sale_unit` =  u.id', 'left');
            
            $this->db->where(['p.id' => $product_id]);
            if($variant_id){
               $this->db->where(['pv.id' => $variant_id]); 
            }
            $q = $this->db->get(); 

            if ($q->num_rows() > 0) {
                
                foreach ($q->result() as $row) {
                    $data[$row->id] = (array) $row;
                }

                return $data;
            }

            return false;
         
    }
    
    
    public function getProductVariants($product_id) {
         $q = $this->db->select('id, name, price, quantity')
                ->where_in('product_id', $product_id)
                ->get('product_variants');

        if ($q->num_rows() > 0) {
                
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }

        return false; 
    }
    
    public function getProductsImages($product_id) {
        $q = $this->db->select('photo')
                ->where_in('product_id', $product_id)
                ->get('product_photos');

        if ($q->num_rows() > 0) {
                
            foreach ($q->result() as $row) {
                $data[] = $row->photo;
            }
            return $data;
        }

        return false;         
    }
    
    
    public function get_shop_settings() {
      
        $q = $this->db->get('eshop_settings');
        
        if ($q->num_rows() > 0) {
            $data = (array) $q->result();
            return $data[0];
        }
        return false;
   
    }

}
