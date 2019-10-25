<?php
  require_once('includes/load.php');
/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}
/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}'");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
 /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
  function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
      if($password_request === $user['password'] ){
        return $user['id'];
      }
    }
   return false;
  }
  /*--------------------------------------------------------------*/
  /* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
     $result = $db->query($sql);
     if($db->num_rows($result)){
       $user = $db->fetch_assoc($result);
       $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,u.department_id,dp.department_name,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
	  $sql .="LEFT JOIN department dp ON dp.department_id = u.department_id ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ORDER BY u.department_id ASC,u.user_level ASC";
      $result = find_by_sql($sql);
      return $result;
  }
  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /* Find all Group name
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Find group level
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 3 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
   function page_require_level($require_level){
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['user_level']);
     //if user not login
     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Please login...');
            redirect('index.php', false);
      //if Group status Deactive
     elseif($login_level['group_status'] === '0'):
           $session->msg('d','This level user has been band!');
           redirect('home.php',false);
      //cheackin log in User level and Require level is Less than or equal to
     elseif($current_user['user_level'] <= (int)$require_level):
              return true;
      else:
            $session->msg("d", "Sorry! you dont have permission to view the page.");
            redirect('home.php', false);
        endif;

     }
   /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
  function join_product_table(){
     global $db;
     $sql  =" SELECT p.id,p.material_code,p.material_name,p.ref_code,p.material_format,p.buy_price,p.special,p.use_people_times,p.use_times,p.employee_id,p.date,p.media_id,u.name";
    //$sql  .=" AS department,m.file_name AS IImage";
    $sql  .=" FROM material_basic p";
    $sql  .=" LEFT JOIN users u ON u.username = p.employee_id";
    $sql  .=" LEFT JOIN media m ON m.id = p.media_id";
    $sql  .=" ORDER BY p.material_code ,p.material_code ASC";
    return find_by_sql($sql);

   }
   
   function join_product_table_dp($dpid){
     global $db;
     $sql  =" SELECT p.id, p.department_id, p.material_code, p.ref_code, p.material_dpname, p.in_unit, p.unit_quantity, p.out_unit, p.safety_stock, p.highest_stock,p.stock_location, p.employee_id ,p.date,u.name";
    //$sql  .=" AS department,m.file_name AS IImage";
    $sql  .=" FROM material_deptbasic p";
	$sql  .=" LEFT JOIN users u ON u.username = p.employee_id";
	$sql  .=" where p.department_id = '$dpid'";
    //$sql  .=" LEFT JOIN media m ON m.id = p.media_id";
    $sql  .=" ORDER BY p.material_code,p.material_code ASC";
    return find_by_sql($sql);
	
   }
   
  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /* 借貨查詢 >> 搜尋醫材名稱
  /*--------------------------------------------------------------*/

   function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     $sql  = "SELECT m.material_code,m.ref_code,m.material_name,m.id";
	 $sql .= " FROM material_basic m";
	 $sql .= " WHERE m.material_code like '$p_name%'";
	 $sql .= " ORDER BY m.material_code ASC,m.ref_code ASC";
     $result = find_by_sql($sql);
     return $result;
   }
	
	
   function find_barcode_by_title($product_barcode){
     global $db;
     $p_barcode = remove_junk($db->escape($product_barcode));
     $sql  = "SELECT i.barcode,i.material_code,i.ref_code,i.lot_num,i.expiry_date";
	 $sql .= " FROM input_stock_recode i";
	 $sql .= " WHERE i.barcode like '$p_barcode'";
     $result = find_by_sql($sql);
     return $result;
   }
   
  /*--------------------------------------------------------------*/
  /* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /* 借貨查詢>搜尋符合的code/ref
  /*--------------------------------------------------------------*/
  function find_all_product_info_by_title($title,$title1){/*OK*/
    global $db;
	
	$sql  = "SELECT v.mdp_id,v.material_code,v.department_id,v.ref_code,dp.extension,dp.department_name,mdp.out_unit,users.name,";
    $sql .= "SUM(v.input_qty)as in_qty,SUM(v.out_qty)as out_qty,sum(v.input_qty-v.out_qty) as now_qty";
    $sql .= " FROM v";
	$sql .= " LEFT JOIN department dp ON dp.department_id = v.department_id";
	$sql .= " LEFT JOIN material_deptbasic mdp on mdp.id = v.mdp_id";
	$sql .= " LEFT JOIN users users on users.username = dp.username";
	$sql .= " WHERE v.material_code ='{$title}' and v.ref_code ='{$title1}'";
    $sql .= " GROUP BY v.mdp_id";
	$sql .= " ORDER BY now_qty DESC";
    return find_by_sql($sql);
  }
  /*--------------------------------------------------------------*/
  /* Function for 找符合dp的即時庫存量
  /*--------------------------------------------------------------*/
  function find_all_product_nowqty_by_dp($dp_name){/*OK*/
    global $db;
    $sql  = "SELECT mdp.material_dpname,v.material_code,v.ref_code,mdp.out_unit as unit,mdp.safety_stock,";
    $sql .= "sum((v.input_qty*mdp.unit_quantity) - v.out_qty) as now_qty,mdp.highest_stock ";
    $sql .= "FROM v ";
	$sql .= "LEFT JOIN material_deptbasic mdp on v.mdp_id = mdp.id ";
	$sql .= "WHERE v.department_id ='{$dp_name}' ";
    $sql .= "GROUP BY v.mdp_id ";
	$sql .= "ORDER BY v.material_code";
    return find_by_sql($sql);
  }
  /*--------------------------------------------------------------*/
  /* Function for 更新v.view
  /*--------------------------------------------------------------*/
  function renew_qty(){/*OK*/
    global $db;
    $sql  = 'CREATE OR REPLACE VIEW v AS SELECT input_stock_recode.barcode,input_stock_recode.mdp_id,input_stock_recode.in_quantity AS input_qty,IFNULL(Sum(output_stock_recode.out_quantity),0) AS out_qty,material_deptbasic.material_code, material_deptbasic.department_id,material_deptbasic.ref_code FROM input_stock_recode JOIN material_deptbasic ON material_deptbasic.id = input_stock_recode.mdp_id LEFT JOIN output_stock_recode ON output_stock_recode.barcode = input_stock_recode.barcode WHERE input_stock_recode.mdp_id = material_deptbasic.id GROUP BY input_stock_recode.barcode';
  }
  /*--------------------------------------------------------------*/
  /* Function for Update product quantity
  /*--------------------------------------------------------------*/
  function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }
  /*--------------------------------------------------------------*/
  /* Function for Display Recent product Added
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.name,p.sale_price,p.media_id,c.name AS categorie,";
   $sql  .= "m.file_name AS image FROM products p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit){
   global $db;
   $sql  = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
   $sql .= " GROUP BY s.product_id";
   $sql .= " ORDER BY SUM(s.qty) DESC LIMIT ".$db->escape((int)$limit);
   return $db->query($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function find_all_sale(){
   global $db;
   $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " ORDER BY s.date DESC";
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Display Recent sale
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for <呼叫區間>
/*--------------------------------------------------------------*/
function find_range_dates_inrecode($start_date,$end_date,$dpid){/*入庫區間*/
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  
  $sql  = "SELECT i.barcode,mdp.material_dpname,mdp.material_code,mdp.ref_code,i.lot_num,i.in_quantity as qty,i.employee_id,i.remarks,i.category,mdp.in_unit as unit,(i.in_quantity*mdp.unit_quantity) as chanqty,mdp.out_unit,";
  $sql .= "date_format(i.expiry_date,'%Y-%m-%d')AS expiry_date,date_format(i.in_time,'%Y-%m-%d %H:%i')AS time";
  $sql .= " FROM input_stock_recode i";
  $sql .= " LEFT JOIN material_deptbasic mdp on mdp.id = i.mdp_id";
  $sql .= " WHERE DATE_FORMAT(i.in_time,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' && mdp.department_id='$dpid'";
  $sql .= " GROUP BY i.barcode";
  $sql .= " ORDER BY i.in_time";
  return $db->query($sql);
}
function find_range_dates_outrecode($start_date,$end_date,$dpid){/*出庫區間*/
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  
  $sql  = "SELECT i.barcode,mdp.material_dpname,mdp.material_code,mdp.ref_code,i.lot_num,o.employee_id,o.patient_history,o.category,o.remarks,o.out_quantity as qty,mdp.out_unit as unit,";
  $sql .= "date_format(i.expiry_date,'%Y-%m-%d')AS expiry_date,date_format(o.time,'%Y-%m-%d %H:%i')AS time";
  $sql .= " FROM output_stock_recode o";
  $sql .= " LEFT JOIN input_stock_recode i ON i.barcode = o.barcode";
  $sql .= " LEFT JOIN material_deptbasic mdp on mdp.id = i.mdp_id";
  $sql .= " WHERE DATE_FORMAT(o.time,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' && mdp.department_id='$dpid'";
  $sql .= " ORDER BY o.time";
  return $db->query($sql);
}
/*--------------------------------------------------------------*/
/* Function for <呼叫當月>
/*--------------------------------------------------------------*/
function  find_monthly_dates_inrecode($year,$month,$dpid){/*入庫當月*/
  global $db;
  $sql  = "SELECT i.barcode,mdp.material_dpname,mdp.material_code,mdp.ref_code,i.lot_num,i.in_quantity as qty,i.employee_id,i.remarks,i.category,mdp.in_unit as unit,(i.in_quantity*mdp.unit_quantity) as chanqty,mdp.out_unit,";
  $sql .= "date_format(i.expiry_date,'%Y-%m-%d')AS expiry_date,date_format(i.in_time,'%Y-%m-%d %H:%i')AS time";
  $sql .= " FROM input_stock_recode i";
  $sql .= " LEFT JOIN material_deptbasic mdp on mdp.id = i.mdp_id";
  $sql .= " WHERE DATE_FORMAT(i.in_time,'%Y-%m')  = '$year-$month' && mdp.department_id='$dpid'";
  $sql .= " GROUP BY i.barcode";
  $sql .= " ORDER BY i.in_time";
  return find_by_sql($sql);
}
function  find_monthly_dates_outrecode($year,$month,$dpid){/*出庫當月*/
  global $db;
  $sql  = "SELECT i.barcode,mdp.material_dpname,mdp.material_code,mdp.ref_code,i.lot_num,o.employee_id,o.patient_history,o.category,o.remarks,o.out_quantity as qty,mdp.out_unit as unit,";
  $sql .= "date_format(i.expiry_date,'%Y-%m-%d')AS expiry_date,date_format(o.time,'%Y-%m-%d %H:%i')AS time";
  $sql .= " FROM output_stock_recode o";
  $sql .= " LEFT JOIN input_stock_recode i ON i.barcode = o.barcode";
  $sql .= " LEFT JOIN material_deptbasic mdp ON mdp.id = i.mdp_id";
  $sql .= " WHERE DATE_FORMAT(o.time, '%Y-%m' ) = '$year-$month' && mdp.department_id='$dpid'";
  $sql .= " ORDER BY o.time";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function for <呼叫當日>
/*--------------------------------------------------------------*/
function  find_daily_dates_inrecode($year,$month,$day,$dpid){/*入庫當日*/
  global $db;
  $sql  = "SELECT i.barcode,mdp.material_dpname,mdp.material_code,mdp.ref_code,i.lot_num,i.in_quantity as qty,i.employee_id,i.remarks,i.category,mdp.in_unit as unit,(i.in_quantity*mdp.unit_quantity) as chanqty,mdp.out_unit,";
  $sql .= "date_format(i.expiry_date,'%Y-%m-%d')AS expiry_date,date_format(i.in_time,'%Y-%m-%d %H:%i')AS time";
  $sql .= " FROM input_stock_recode i";
  $sql .= " LEFT JOIN material_deptbasic mdp on mdp.id = i.mdp_id";
  $sql .= " WHERE DATE_FORMAT(i.in_time,'%Y-%m-%d')  = '$year-$month-$day' && mdp.department_id='$dpid'";
  $sql .= " GROUP BY i.barcode";
  $sql .= " ORDER BY i.in_time";
  return find_by_sql($sql);
}
function  find_daily_dates_outrecode($year,$month,$day,$dpid){/*出庫當日*/
  global $db;
  $sql  = "SELECT i.barcode,mdp.material_dpname,mdp.material_code,mdp.ref_code,i.lot_num,o.employee_id,o.patient_history,o.category,o.remarks,o.out_quantity as qty,mdp.out_unit as unit,";
  $sql .= "date_format(i.expiry_date,'%Y-%m-%d')AS expiry_date,date_format(o.time,'%Y-%m-%d %H:%i')AS time";
  $sql .= " FROM output_stock_recode o";
  $sql .= " LEFT JOIN input_stock_recode i ON i.barcode = o.barcode";
  $sql .= " LEFT JOIN material_deptbasic mdp on mdp.id = i.mdp_id";
  $sql .= " WHERE DATE_FORMAT(o.time,'%Y-%m-%d') = '$year-$month-$day' && mdp.department_id='$dpid'";
  $sql .= " ORDER BY o.time";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for 查詢病人帳號
/*--------------------------------------------------------------*/
 function find_username($username){/*入庫input_stock_recode*/
    global $db;
	$sql   ="SELECT ou.patient_history,mdp.material_dpname,mdp.material_code,mdp.ref_code,ou.out_quantity,ou.time,mb.buy_price";
	$sql  .=" FROM output_stock_recode ou";
	$sql  .=" left join input_stock_recode i ON i.barcode = ou.barcode";
	$sql  .=" left join material_deptbasic mdp ON mdp.id = i.mdp_id";
	$sql  .=" left join material_basic mb ON mb.material_code = mdp.material_code && mb.ref_code = mdp.ref_code";
	$sql  .=" WHERE ou.patient_history = '$username'";
     return find_by_sql($sql);
   }
/*--------------------------------------------------------------*/
/* Function for 入出庫紀錄呼叫
/*--------------------------------------------------------------*/
 function join_stock_recodes_table(){/*入庫input_stock_recode*/
    global $db;
	$date    = make_date();
	$sql   =" SELECT i.id,i.name,i.material_code,i.ref_code,i.in_quantity,i.in_time,i.employee_id";
	$sql  .=" FROM input_stock_recode i";
	$sql  .=" WHERE in_time='$date'";
	$sql  .=" ORDER BY i.in_time ASC";
	$sql  .=" ORDER BY i.in_time ASC";
     return find_by_sql($sql);
   }
/*--------------------------------------------------------------*/
/* Function for 入庫
/*--------------------------------------------------------------*/
function input_find_by_department($department_id) {
   global $db; 
     return find_by_sql("SELECT * FROM material_deptbasic WHERE department_id ='".$db->escape($department_id)."'");
 }
 /*--------------------------------------------------------------*/
/* Function for 入庫
/*--------------------------------------------------------------*/
 function input_find_by_code($code) {
   global $db; 
      return find_by_sql("SELECT * FROM material_basic WHERE material_code ='".$db->escape($code)."'");
 }
  /*--------------------------------------------------------------*/
/* Function for 入庫
/*--------------------------------------------------------------*/
 function departmentid_find_by_department($department_id) {
   global $db; 
      return find_by_sql("SELECT * FROM department WHERE department_id ='".$db->escape($department_id)."'");
 }

/*--------------------------------------------------------------*/
/* Function for 毅傑 *
/*--------------------------------------------------------------*/
 function input_find_by_name($depname) {
   global $db;
      return find_by_sql("SELECT * FROM material_basic WHERE material_name ='".$db->escape($depname)."'");
 }  
 function input_find_by_deptcode($code) {
   global $db; 
      return find_by_sql("SELECT * FROM material_deptbasic WHERE material_code ='".$db->escape($code)."'");
 }
  function input_find_by_dp_REF($dp_ref) {
   global $db; 
      return find_by_sql("SELECT * FROM material_deptbasic WHERE ref_code ='".$db->escape($dp_ref)."'");
 }
/*--------------------------------------------------------------*/
/* Function for 邵君 *
/*--------------------------------------------------------------*/
  function input_find_by_REF($ref) {
   global $db; 
      return find_by_sql("SELECT distinct in_unit FROM material_deptbasic WHERE ref_code ='".$db->escape($ref)."'");
 }
 function input_find_id($code,$ref,$dpid) {
   global $db; 
   //echo "SELECT id FROM material_deptbasic WHERE material_code ='".$db->escape($code)."' && ref_code ='".$db->escape($ref)."' && department_id ='".$db->escape($dpid)."'";
      return find_by_sql("SELECT id FROM material_deptbasic WHERE material_code ='".$db->escape($code)."' && ref_code ='".$db->escape($ref)."' && department_id ='".$db->escape($dpid)."'");
 }
  function input_find_by_department_unique($department_id) {
   global $db; 
     return find_by_sql("SELECT distinct material_code FROM material_deptbasic WHERE department_id ='".$db->escape($department_id)."'");
 }
  function input_sum() {
   global $db; 
     return find_by_sql("SELECT material_code , ref_code, SUM(in_quantity) AS 'SUMIN' FROM input_stock_recode GROUP BY material_code,ref_code");
 }  
  function output_sum($material_code,$ref_code){
   global $db; 
   if(isset($material_code)){
    if(isset($ref_code))
       return find_by_sql("SELECT SUM(out_quantity) AS 'SUMOUT' FROM output_stock_recode WHERE material_code='".$db->escape($material_code)."'AND ref_code ='".$db->escape($ref_code)."'GROUP BY material_code,ref_code");
  else
    return 0;
   }
   else
     return 0;
 }
 
 function output_find_by_department_unique($department_id) {
   global $db; 
     return find_by_sql("SELECT distinct mdp.material_code FROM input_stock_recode i LEFT JOIN material_deptbasic mdp ON mdp.id = i.mdp_id WHERE mdp.department_id ='".$db->escape($department_id)."'");
 }
 function output_find_by_input($department_id) {
   global $db; 
     return find_by_sql("SELECT barcode, department_id, mdp.material_code, mdp.ref_code, mdp.material_dpname, lot_num FROM input_stock_recode i LEFT JOIN material_deptbasic mdp ON i.mdp_id = mdp.id WHERE mdp.department_id ='".$db->escape($department_id)."'");
 }		
 function output_find_by_barcode($barcode) {
   global $db; 
     return find_by_sql("SELECT barcode, department_id, mdp.material_code, mdp.ref_code, mdp.material_dpname, lot_num FROM input_stock_recode i LEFT JOIN material_deptbasic mdp ON i.mdp_id = mdp.id WHERE i.barcode ='".$db->escape($barcode)."'");
 }
?>
