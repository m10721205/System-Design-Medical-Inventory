<?php
//編輯醫材分類
  $page_title = '編輯部門';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $users = find_all('users');
?>
<?php
  //Display all Department.
 $e_department = find_by_id('department',(int)$_GET['id']);
  if(!$e_department){
    $session->msg("d","Missing Department id.");
    redirect('department.php');
  }
?>

<?php
//Update Department basic info
  if(isset($_POST['update'])) {
    $req_fields = array('departmentcode','departmentname','departmentextension','departmentusers');
    validate_fields($req_fields);
    if(empty($errors)){
             $id = (int)$e_department['id'];
       $cat_code = remove_junk($db->escape($_POST['departmentcode']));
       $cat_name = remove_junk($db->escape($_POST['departmentname']));
  $cat_extension = remove_junk($db->escape($_POST['departmentextension']));
      $cat_users = remove_junk($db->escape($_POST['departmentusers']));
	  
      //$sql = "UPDATE department SET department_id ='{$cat_id}', department_name ='{$cat_name}',extension='{$cat_extension}',username='{$cat_users}' WHERE id='{$db->escape($id)}'";
        $sql = "UPDATE department SET ";
	   $sql .= "department_id='{$cat_code}',department_name='{$cat_name}',extension='{$cat_extension}',username='{$cat_users}'";
       $sql .= " WHERE id='{$db->escape($e_department['id'])}'";
	   
	   $result = $db->query($sql);
          if($result){
            $session->msg('s',"成功更新部門資料！");
            redirect('edit_department.php?id='.(int)$e_department['id'], false);
          } else {
            $session->msg('d', '注意，更新失敗！');
            redirect('edit_department.php?id='.(int)$e_department['id'], false);
          }
    } else {
      $session->msg("d", $errors);
      redirect('edit_department.php?id='.(int)$e_department['id'],false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>編輯
		   <?php echo remove_junk(ucfirst($e_department['department_id']));?> &
		   <?php echo remove_junk(ucfirst($e_department['department_name']));?>
		   </span>
        </strong>
       </div>
		   <div class="panel-body">
			   <form method="post" action="edit_department.php?id=<?php echo (int)$e_department['id'];?>">
			<div class="form-group">
			   <label for="name">部門代碼</label>
               <input type="text" class="form-control" name="departmentcode" value="<?php echo remove_junk(ucfirst($e_department['department_id']));?>" readonly>
           </div>         	   
           <div class="form-group">
			   <label for="extension">部門名稱</label>
               <input type="text" class="form-control" name="departmentname" value="<?php echo remove_junk(ucfirst($e_department['department_name']));?>">
           </div>                      
           <div class="form-group">
			   <label for="extension">聯絡分機</label>
               <input type="text" class="form-control" name="departmentextension" value="<?php echo remove_junk(ucfirst($e_department['extension']));?>">
           </div>          
           <div class="form-group">
			   <label for="username">管理人</label>
			   <select class="form-control" name="departmentusers" id="username">
                  <?php foreach ($users as $user ){?>
                   <option value="<?php echo $user['username'];?>" <?php echo (remove_junk(ucfirst($e_department['username']==$user['username'])))?"selected":"";?>><?php echo ucwords($user['username']);?></option>
				  <?php };?>
                </select>
           </div>
           <button type="submit" name="update" class="btn btn-primary">更新部門</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
