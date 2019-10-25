<?php
  $page_title = '所有使用者';
  require_once('includes/load.php');
?>
<?php
// Checkin What level user has permission to view this page
 page_require_level(2);
//pull out all user form database
 $all_users = find_all_user();
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>使用者</span>
       </strong>
         <a href="add_user.php" class="btn btn-info pull-right">新增使用者</a>
      </div>
     <div class="panel-body">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th class="text-center" style="width: 3%;">#</th>
			<th class="text-center" style="width: 7%;">使用者</th>
			<th class="text-center" style="width: 7%;">帳號</th>
			<th class="text-center" style="width: 23%;">部門</th>
			<!--<th>密碼</th>-->
            <th class="text-center" style="width: 17%;">使用者權限</th>
            <th class="text-center" style="width: 7%;">狀態</th>
            <th style="width: 25%;">最近登入時間</th>
            <th class="text-center" style="width: 8%;">操作</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_users as $a_user): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_user['name']))?></td>
           <td><?php echo remove_junk(ucwords($a_user['username']))?></td>
		   <td><?php echo remove_junk(ucwords($a_user['department_id']))?>(<?php echo remove_junk(ucwords($a_user['department_name']))?>)</td>
		   <td class="text-center"><?php echo remove_junk(ucwords($a_user['group_name']))?></td>
           <!--這裡是狀態-->
		   <td class="text-center">
           <?php if($a_user['status'] === '1'): ?>
            <span class="label label-success"><?php echo "啟用"; ?></span>
          <?php else: ?>
            <span class="label label-danger"><?php echo "停權中"; ?></span>
          <?php endif;?>
           </td>
		   <!--這裡是狀態-->
           <td><?php echo read_date($a_user['last_login'])?></td>   <!--讀取最近登入日期時間-->
           <td class="text-center">
             <div class="btn-group">
                <a href="edit_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="編輯">
                  <i class="glyphicon glyphicon-pencil"></i>
               </a>
                <a href="delete_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="移除">
                  <i class="glyphicon glyphicon-remove"></i>
                </a>
                </div>
           </td>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
     </div>
    </div>
  </div>
</div>
  <?php include_once('layouts/footer.php'); ?>
