<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model_new extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getProductNames($term, $limit = 5) {
        $this->db->select('id, code, name')
                ->like('name', $term, 'both')->or_like('code', $term, 'both');
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getStaff() {
        if ($this->Admin) {
            $this->db->where('group_id !=', 1);
        }
        $this->db->where('group_id !=', 3)->where('group_id !=', 4);
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    /*daily sale Report*/
    public function getStaffDailySales($user_id, $year, $month, $warehouse_id = NULL) {
        $getwarehouse = str_replace("_", ",", $warehouse_id);
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping,SUM(CASE WHEN up_sales = 1 THEN grand_total ELSE 0 END ) AS urban_piper
            FROM " . $this->db->dbprefix('sales') . " WHERE ";
        if ($warehouse_id) {
            $myQuery .= " warehouse_id IN( {$getwarehouse} ) AND ";
        }
        if ($this->Owner || $this->Admin) {
            if ($user_id) {
                $myQuery .= " created_by = {$user_id} AND ";
            }
        } else {
            if ($this->session->userdata('view_right') == '0') {
                $myQuery .= " created_by = {$user_id} AND ";
            }
        }
        $myQuery .= " DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStaffMonthlySales($user_id, $year, $warehouse_id = NULL) {
        $getwarehouse = str_replace("_", ',', $warehouse_id);
        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . " WHERE ";
        if ($warehouse_id) {
            $myQuery .= " warehouse_id IN ({$getwarehouse}) AND ";
        }

        if ($this->Owner || $this->Admin) {
            if ($user_id) {
                $myQuery .= " created_by = {$user_id} AND ";
            }
        } else {
            if ($this->session->userdata('view_right') == '0') {
                $myQuery .= " created_by = {$user_id} AND ";
            }
        }

        $myQuery .= "  DATE_FORMAT( date,  '%Y' ) =  '{$year}'
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    /* 12-28-2019 It show to warehouse */

    public function getStaffDailySales_w($user_id, $year, $month, $warehouse_id = NULL) {
        $getwarehouse = str_replace("_", ",", $warehouse_id);
        $myQuery = "SELECT DATE_FORMAT( s.date,  '%e' ) AS date, SUM( COALESCE( s.product_tax, 0 ) ) AS tax1, SUM( COALESCE( s.order_tax, 0 ) ) AS tax2, SUM( COALESCE( s.grand_total, 0 ) ) AS total, SUM( COALESCE( s.total_discount, 0 ) ) AS discount, SUM( COALESCE( s.shipping, 0 ) ) AS shipping,SUM(CASE WHEN up_sales = 1 THEN grand_total ELSE 0 END ) AS urban_piper, w.name as warehouse  FROM   sma_sales s  LEFT JOIN sma_warehouses w on s.warehouse_id = w.id WHERE ";
        if ($warehouse_id) {
            $myQuery .= " s.warehouse_id IN( {$getwarehouse} ) AND ";
        }
        if ($this->Owner || $this->Admin) {
            if ($user_id) {
                $myQuery .= " s.created_by = {$user_id} AND ";
            }
        } else {
            if ($this->session->userdata('view_right') == '0') {
                $myQuery .= " s.created_by = {$user_id} AND ";
            }
        }
        $myQuery .= " DATE_FORMAT( s.date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( s.date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getDailySales($year, $month, $warehouse_id = NULL) {
        $getwarehouse = str_replace("_", ",", $warehouse_id);

       // $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
		//	FROM " . $this->db->dbprefix('sales') . " WHERE ";
        
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping,SUM(CASE WHEN up_sales = 1 THEN grand_total ELSE 0 END ) AS urban_piper
            FROM " . $this->db->dbprefix('sales') . " WHERE ";
        /* if ($warehouse_id) {
          $myQuery .= " warehouse_id = {$warehouse_id} AND ";
          } */

        if ($warehouse_id) {
            $myQuery .= " warehouse_id IN( {$getwarehouse} ) AND ";
        }
        if ($this->session->userdata('view_right') == '0') {
            $myQuery .= " created_by = {$user_id} AND  ";
        }

        $myQuery .= " DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
			GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getDailySales_w($year, $month, $warehouse_id = NULL) {
        $getwarehouse = str_replace("_", ",", $warehouse_id);

        $myQuery = "SELECT DATE_FORMAT( s.date,  '%e' ) AS date, SUM( COALESCE( s.product_tax, 0 ) ) AS tax1, SUM( COALESCE( s.order_tax, 0 ) ) AS tax2, SUM( COALESCE( s.grand_total, 0 ) ) AS total, SUM( COALESCE( s.total_discount, 0 ) ) AS discount, SUM( COALESCE( s.shipping, 0 ) ) AS shipping, SUM(CASE WHEN up_sales = 1 THEN grand_total ELSE 0 END ) AS urban_piper, w.name as warehouse 	FROM   sma_sales s  LEFT JOIN sma_warehouses w on s.warehouse_id = w.id WHERE ";
        /* if ($warehouse_id) {
          $myQuery .= " warehouse_id = {$warehouse_id} AND ";
          } */

        if ($warehouse_id) {
            $myQuery .= " s.warehouse_id IN( {$getwarehouse} ) AND ";
        }
        if ($this->session->userdata('view_right') == '0') {
            $myQuery .= " s.created_by = {$user_id} AND  ";
        }

        $myQuery .= " DATE_FORMAT( s.date,  '%Y-%m' ) =  '{$year}-{$month}'
			GROUP BY DATE_FORMAT( s.date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStaffMonthlySales_w($user_id, $year, $warehouse_id = NULL) {
        $getwarehouse = str_replace("_", ',', $warehouse_id);
        $myQuery = "SELECT DATE_FORMAT(  s.date,  '%c' ) AS date, SUM( COALESCE(  s.product_tax, 0 ) ) AS tax1, SUM( COALESCE(  s.order_tax, 0 ) ) AS tax2, SUM( COALESCE(  s.grand_total, 0 ) ) AS total, SUM( COALESCE(  s.total_discount, 0 ) ) AS discount, SUM( COALESCE(  s.shipping, 0 ) ) AS shipping, w.name as warehouse   FROM   sma_sales s  LEFT JOIN sma_warehouses w on s.warehouse_id = w.id WHERE ";
        if ($warehouse_id) {
            $myQuery .= " s.warehouse_id IN ({$getwarehouse}) AND ";
        }

        if ($this->Owner || $this->Admin) {
            if ($user_id) {
                $myQuery .= " s.created_by = {$user_id} AND ";
            }
        } else {
            if ($this->session->userdata('view_right') == '0') {
                $myQuery .= " s.created_by = {$user_id} AND ";
            }
        }

        $myQuery .= "  DATE_FORMAT( s.date,  '%Y' ) =  '{$year}'
            GROUP BY date_format( s.date, '%c' ) ORDER BY date_format( s.date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getMonthlySales_w($year, $warehouse_id = NULL) {
        $getwarehouse = str_replace("_", ",", $warehouse_id);
        $myQuery = "SELECT  DATE_FORMAT(  s.date,  '%c' ) AS date, SUM( COALESCE(  s.product_tax, 0 ) ) AS tax1, SUM( COALESCE(  s.order_tax, 0 ) ) AS tax2, SUM( COALESCE(  s.grand_total, 0 ) ) AS total, SUM( COALESCE(  s.total_discount, 0 ) ) AS discount, SUM( COALESCE(  s.shipping, 0 ) ) AS shipping, w.name as warehouse  FROM   sma_sales s  LEFT JOIN sma_warehouses w on s.warehouse_id = w.id WHERE ";
        if ($warehouse_id) {
            $myQuery .= " s.warehouse_id IN ({$getwarehouse}) AND ";
        }
        $myQuery .= " DATE_FORMAT( date,  '%Y' ) =  '{$year}'
			GROUP BY date_format( s.date, '%c' ) ORDER BY date_format( s.date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

      public function getDailySalesItems($date, $warehouse_id = 0) {
        $query = "SELECT  si.product_id ,si.product_code ,  si.product_name ,  si.net_unit_price, si.product_unit_code as unit,
                    SUM(  si.quantity ) as qty, SUM(  si.item_tax ) as tax, si.tax as tax_rate, SUM(  si.item_discount ) as discount, SUM(  si.subtotal ) as total, c.id as category_id, c.name as category_name
                FROM  " . $this->db->dbprefix('sale_items') . " si  left join " . $this->db->dbprefix('products') . " p on p.id=si.product_id left join  " . $this->db->dbprefix('categories') . " c on c.id=p.category_id
                WHERE  si.sale_id IN ( SELECT  `id`  FROM  " . $this->db->dbprefix('sales') . "  WHERE DATE( `date` ) =  '$date' )";
        if ($warehouse_id != 0) {
            $query .= " and si.warehouse_id='$warehouse_id'  ";
        }
        $query .= " GROUP BY  si.product_code 
                ORDER BY  si.product_name ";

        $q = $this->db->query($query, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getDailySalesItemsTaxes($date, $warehouse_id = 0) {
        $select_warehouse = '';
        if ($warehouse_id != 0) {
            $select_warehouse = " and warehouse_id='$warehouse_id'  ";
        }
        /*$query = "SELECT sum(`tax_amount`) amount, ( `attr_per` * 2) as rate,item_id
            FROM  " . $this->db->dbprefix('sales_items_tax') . " 
                WHERE `sale_id` IN ( SELECT  `id`  FROM  " . $this->db->dbprefix('sales') . "  WHERE DATE( `date` ) =  '$date' " . $select_warehouse . " ) 
                    AND `attr_per` > 0 GROUP BY `attr_per` ORDER BY `attr_per` ASC ";*/
        
        $query = "SELECT gst_rate,sum(cgst) AS CGST,sum(sgst) AS SGST,sum(igst) AS IGST
            FROM  " . $this->db->dbprefix('sale_items') . " 
                WHERE `sale_id` IN ( SELECT  `id`  FROM  " . $this->db->dbprefix('sales') . "  WHERE DATE( `date` ) =  '$date' " . $select_warehouse . " ) 
                    GROUP BY `gst_rate` ";

        $q = $this->db->query($query, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    /*public function gettaxitemid($item_id) {

        $qry = "SELECT (SELECT attr_per FROM  " . $this->db->dbprefix('sales_items_tax') . "  WHERE `attr_code` = 'CGST' AND  item_id ='$item_id' ) AS CGST ,(SELECT attr_per FROM  " . $this->db->dbprefix('sales_items_tax') . "  WHERE `attr_code` = 'SGST' AND item_id ='$item_id') AS SGST ,(SELECT attr_per FROM  " . $this->db->dbprefix('sales_items_tax') . "  WHERE `attr_code` = 'IGST' AND  item_id ='$item_id' ) AS IGST FROM  " . $this->db->dbprefix('sales_items_tax') . "  WHERE   item_id ='$item_id' Group By item_id";
          $sqlrs = $this->db->query($qry, false);
        if ($sqlrs->num_rows() > 0) {
            foreach (($sqlrs->result()) as $row_rs) {
                $data[] = $row_rs;
            }
            return $data;
        }
        return FALSE;
    }*/
    public function getMonthSalesItemsTaxes($month, $year) {
        /*$query = "SELECT sum(`tax_amount`) amount, ( `attr_per` * 2) as rate,item_id
            FROM  " . $this->db->dbprefix('sales_items_tax') . " 
                WHERE `sale_id` IN ( SELECT  `id`  FROM  " . $this->db->dbprefix('sales') . "  WHERE  DATE_FORMAT( date,  '%c' ) =  '{$month}' AND  DATE_FORMAT( date,  '%Y' ) =  '{$year}' ) 
                    AND `attr_per` > 0 GROUP BY `attr_per` ORDER BY `attr_per` ASC ";*/
                
      
        $query = "SELECT gst_rate,sum(cgst) AS CGST,sum(sgst) AS SGST,sum(igst) AS IGST
            FROM  " . $this->db->dbprefix('sale_items') . " 
                WHERE `sale_id` IN ( SELECT  `id`  FROM  " . $this->db->dbprefix('sales') . "  WHERE  DATE_FORMAT( date,  '%c' ) =  '{$month}' AND  DATE_FORMAT( date,  '%Y' ) =  '{$year}' ) 
                    GROUP BY `gst_rate`";
        $q = $this->db->query($query, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getMonthlySales($year, $warehouse_id = NULL) {
        $getwarehouse = str_replace("_", ",", $warehouse_id);
        $myQuery = "SELECT  DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
			FROM " . $this->db->dbprefix('sales') . " WHERE ";
        if ($warehouse_id) {
            $myQuery .= " warehouse_id IN ({$getwarehouse}) AND ";
        }
        $myQuery .= " DATE_FORMAT( date,  '%Y' ) =  '{$year}'
			GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }  
   
    /*Urbin Piper Daily Report*/
     public function getDailyUrbinpiper($date) {
       
        $query = "SELECT  Count(id) AS invoice, up_channel, SUM( COALESCE( grand_total, 0 ) ) AS total
            FROM  " . $this->db->dbprefix('sales') . "  WHERE up_sales = 1 AND DATE( `date` ) =  '$date'  GROUP BY up_channel ";

        $q = $this->db->query($query, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    /**/
    /*daily and monthy purchase function*/
     public function getDailyPurchases($year, $month, $warehouse_id = NULL) {
        $getwarehouse = str_replace("_", ",", $warehouse_id);
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases') . " WHERE ";
        if ($warehouse_id) {
            $myQuery .= " warehouse_id IN ({$getwarehouse}) AND ";
        }
        $myQuery .= " DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getMonthlyPurchases($year, $warehouse_id = NULL) {
        $getwarehouse = str_replace("_", ",", $warehouse_id);
        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases') . " WHERE ";
        if ($warehouse_id) {
            $myQuery .= " warehouse_id IN ({$getwarehouse}) AND ";
        }
        $myQuery .= " DATE_FORMAT( date,  '%Y' ) =  '{$year}'
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStaffDailyPurchases($user_id, $year, $month, $warehouse_id = NULL) {
        $getwarehouse = str_replace("_", ",", $warehouse_id);
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases') . " WHERE ";
        if ($warehouse_id) {
            $myQuery .= " warehouse_id IN ( {$getwarehouse} ) AND ";
        }

        // 03/04/19
        if ($this->session->userdata('view_right') == '0') {
            $myQuery .= " created_by = {$user_id} AND ";
        }
        // End  03/04/19


        $myQuery .= "  DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStaffMonthlyPurchases($user_id, $year, $warehouse_id = NULL) {
        $getwarehouse = str_replace("_", ",", $warehouse_id);
        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases') . " WHERE ";
        if ($warehouse_id) {
            $myQuery .= " warehouse_id IN ( {$getwarehouse}) AND ";
        }

        if ($this->session->userdata('view_right') == '0') {
            $myQuery .= " created_by = {$user_id} AND ";
        }

        $myQuery .= " created_by = {$user_id} AND DATE_FORMAT( date,  '%Y' ) =  '{$year}'
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getDailyPurchaseItemsTaxes($date) {
        
        $query = "SELECT gst_rate,sum(cgst) AS CGST,sum(sgst) AS SGST,sum(igst) AS IGST
            FROM  " . $this->db->dbprefix('purchase_items') . " 
            WHERE `purchase_id` IN ( SELECT  `id`  FROM  " . $this->db->dbprefix('purchases') . "  WHERE DATE( `date` ) =  '$date' ) 
            GROUP BY `gst_rate` ";

        $q = $this->db->query($query, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }

    public function getMonthPurchaseItemsTaxes($month, $year) {
        $query = "SELECT gst_rate,sum(cgst) AS CGST,sum(sgst) AS SGST,sum(igst) AS IGST
            FROM  " . $this->db->dbprefix('purchase_items') . "
            WHERE `purchase_id` IN ( SELECT  `id`  FROM  " . $this->db->dbprefix('purchases') . "  WHERE  DATE_FORMAT( date,  '%c' ) =  '{$month}' AND  DATE_FORMAT( date,  '%Y' ) =  '{$year}' ) 
             GROUP BY `gst_rate` ";

        $q = $this->db->query($query, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    /* Tax Purchase CGST SGST IGST */

    public function getpurchasetaxitemid($item_id) {
        $qry = "SELECT (SELECT attr_per FROM  " . $this->db->dbprefix('purchase_items_tax') . "  WHERE `attr_code` = 'CGST' AND  item_id ='$item_id' ) AS CGST ,(SELECT attr_per FROM  " . $this->db->dbprefix('purchase_items_tax') . "  WHERE `attr_code` = 'SGST' AND item_id ='$item_id') AS SGST ,(SELECT attr_per FROM  " . $this->db->dbprefix('purchase_items_tax') . "  WHERE `attr_code` = 'IGST' AND  item_id ='$item_id' ) AS IGST FROM  " . $this->db->dbprefix('purchase_items_tax') . "  WHERE   item_id ='$item_id' Group By item_id";
        $sqlrs = $this->db->query($qry, false);
        if ($sqlrs->num_rows() > 0) {
            foreach (($sqlrs->result()) as $row_rs) {
                $data[] = $row_rs;
            }
            return $data;
        }
        return FALSE;
    }

   /**/    
}
