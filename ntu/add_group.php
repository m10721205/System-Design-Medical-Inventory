<?php
  $page_title = 'Add Group';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
  if(isset($_POST['add'])){

   $req_fields = array('group-name','group-level');
   validate_fields($req_fields);

   if(find_by_groupName($_POST['group-name']) === false ){
     $session->msg('d','<b>Sorry!</b> Entered Group Name already in database!');
     redirect('add_group.php', false);
   }elseif(find_by_groupLevel($_POST['group-level']) === false) {
     $session->msg('d','<b>Sorry!</b> Entered Group Level already in database!');
     redirect('add_group.php', false);
   }
   if(empty($errors)){
           $name = remove_junk($db->escape($_POST['group-name']));
          $level = remove_junk($db->escape($_POST['group-level']));
         $status = remove_junk($db->escape($_POST['status']));

        $query  = "INSERT INTO user_groups (";
        $query .="group_name,group_level,group_status";
        $query .=") VALUES (";
        $query .=" '{$name}', '{$level}','{$status}'";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s',"Group has been creted! ");
          redirect('add_group.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry failed to create Group!');
          redirect('add_group.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('add_group.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h3>新增使用者類群</h3>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="add_group.php" class="clearfix">
        <div class="form-group">
              <label for="name" class="control-label">類群名稱</label>
              <input type="name" class="form-control" name="group-name">
        </div>
        <div class="form-group">
              <label for="level" class="control-label">類群權限</label>
              <input type="number" class="form-control" name="group-level">
        </div>
		<div class="form-group">
         <!--<label for="level">類群權限</label>
            <select class="form-control" name="group-level">
              <option value="1">1</option>
              <option value="2">2</option>
			  <option value="3">3</option>
            </select>
        </div>-->
        <div class="form-group">
          <label for="status">狀態</label>
            <select class="form-control" name="status">
              <option value="1">啟用</option>
              <option value="0">停用</option>
            </select>
        </div>
        <div class="form-group clearfix">
                <button type="submit" name="add" class="btn btn-info pull-right">新增</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>
