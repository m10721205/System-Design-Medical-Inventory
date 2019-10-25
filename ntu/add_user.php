<?php
  
  $page_title = 'Add User';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $groups = find_all('user_groups');
  $departments = find_all('department');  
  
?>
<?php
  if(isset($_POST['add_user'])){

   $req_fields = array('full-name','username','password','department','level' );
   validate_fields($req_fields);

   if(empty($errors)){
       $name   = remove_junk($db->escape($_POST['full-name']));
       $username   = remove_junk($db->escape($_POST['username']));
       $password   = remove_junk($db->escape($_POST['password']));
	   $department   = remove_junk($db->escape($_POST['department']));
       $user_level = (int)$db->escape($_POST['level']);
       $password = sha1($password);
        $query = "INSERT INTO users (";
        $query .="name,username,password,department_id,user_level,status";
        $query .=") VALUES (";
        $query .=" '{$name}', '{$username}', '{$password}', '{$department}','{$user_level}','1'";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s',"新增使用者成功！");
          redirect('add_user.php', false);
        } else {
          //failed
          $session->msg('d','新增使用者失敗！');
          redirect('add_user.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('add_user.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>
  <?php echo display_msg($msg); ?>
  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Add New User</span>
       </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-6">
          <form method="post" action="add_user.php">
            <div class="form-group">
                <label for="name">姓名</label>
                <input type="text" class="form-control" name="full-name" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="username">員工編號</label>
                <input type="text" class="form-control" name="username" placeholder="Employee Number">
            </div>
            <div class="form-group">
                <label for="password">密碼</label>
                <input type="password" class="form-control" name ="password"  placeholder="Password">
            </div>
			<div class="form-group">
              <label for="level">部門</label>
                <select class="form-control" name="department">
                  <?php foreach ($departments as $department ):?>
                   <option value="<?php echo $department['department_id'];?>"><?php echo ucwords($department['department_name']);?></option>
                <?php endforeach;?>
                </select>
            </div>
            <div class="form-group">
              <label for="level">使用者權限</label>
                <select class="form-control" name="level">
                  <?php foreach ($groups as $group ):?>
                   <option value="<?php echo $group['group_level'];?>"><?php echo ucwords($group['group_name']);?></option>
                <?php endforeach;?>
                </select>
            </div>
            <div class="form-group clearfix">
              <button type="submit" name="add_user" class="btn btn-primary">新增</button>
            </div>
        </form>
        </div>

      </div>

    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
