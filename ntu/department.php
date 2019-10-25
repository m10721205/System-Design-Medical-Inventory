<?php
  $page_title = '所有科別';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  $all_department = find_all('department');
  $users = find_all('users');
?>
<?php
 if(isset($_POST['add_cat'])){
   $req_field = array('department_id','department_name','department_extension','department_username');
   validate_fields($req_field);
   
      if(empty($errors)){
	  $cat_id	= remove_junk($db->escape($_POST['department_id']));
	  $cat_name = remove_junk($db->escape($_POST['department_name']));
	  $cat_extension= remove_junk($db->escape($_POST['department_extension']));
	  $cat_username = remove_junk($db->escape($_POST['department_username']));

      $sql  = "INSERT INTO department (";
	  $sql .= "department_id,department_name,extension,username";
	  $sql .= ")";
      $sql .= " VALUES ('{$cat_id}','{$cat_name}','{$cat_extension}','{$cat_username}'";
	  $sql .=")";
      if($db->query($sql)){
        $session->msg("s", "成功新增科別");
        redirect('department.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('department.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('department.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>

  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>
   <div class="row">
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>增加科別</span>
         </strong>
        </div>
		<font color="#FF0000">* 表示欄位必填</font>
        <div class="panel-body">
          <form method="post" action="department.php">
            <div class="form-group">
			 <div class="row">
			  <div class="col-md-8">
				<font color="#FF0000">*</font> <label for="id">科別代碼</label>
				<input type="integer" class="form-control"  name="department_id" placeholder="Department ID">
				
			  </div>
			 </div>
			</div>
			<div class="form-group">
				<font color="#FF0000">*</font> <label for="name">科別名稱</label>
                <input type="text" class="form-control" name="department_name" placeholder="Department Name">
            </div>
            <div class="form-group">
				<font color="#FF0000">*</font> <label for="extension">聯絡分機</label>
                <input type="text" class="form-control" name="department_extension" placeholder="Extension Number">
            </div>
            <div class="form-group">
			    <font color="#FF0000">*</font> <label for="username">管理人</label>
				<select class="form-control" name="department_username">
                  <?php foreach ($users as $user ):?>
                   <option value="<?php echo $user['username'];?>"><?php echo ucwords($user['username']);?></option>
                <?php endforeach;?>
                </select>
            </div>
            
            <button type="submit" name="add_cat" class="btn btn-primary">新增</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>所有科別</span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
					<th class="text-center" style="width: 50px;">#</th>
                    <th>科別代碼</th>
                    <th>科別名稱</th>
                    <th>聯絡分機</th>
                    <th>部門管理人</th>
                    <th class="text-center" style="width: 100px;">動作</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_department as $a_department):?>
                <tr>
					<td class="text-center"><?php echo count_id();?></td>
                    <td> <?php echo remove_junk(ucfirst($a_department['department_id'])); ?></td>
                    <td> <?php echo remove_junk(ucfirst($a_department['department_name'])); ?></td>
                    <td> <?php echo remove_junk(ucfirst($a_department['extension'])); ?></td>
                    <td> <?php echo remove_junk(ucfirst($a_department['username'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_department.php?id=<?php echo (int)$a_department['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="編輯">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_department.php?id=<?php echo (int)$a_department['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="刪除">
                          <span class="glyphicon glyphicon-trash"></span>
                        </a>
                      </div>
                    </td>

                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
       </div>
    </div>
    </div>
   </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
