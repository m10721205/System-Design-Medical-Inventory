<?php
  $page_title = '所有群組';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
  $all_groups = find_all('user_groups');
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
        <span>所有群組</span>
     </strong>
       <a href="add_group.php" class="btn btn-info pull-right btn-sm"> 新增群組</a>
    </div>
     <div class="panel-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">#</th>
            <th>群組名字</th>
            <th class="text-center" style="width: 20%;">權限等級</th>
            <th class="text-center" style="width: 15%;">狀態</th>
            <th class="text-center" style="width: 150px;">動作</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_groups as $a_group): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td><?php echo remove_junk(ucwords($a_group['group_name']))?></td>
           <td class="text-center">
             <?php echo remove_junk(ucwords($a_group['group_level']))?>
           </td>
		   <td class="text-center">
           <?php if($a_group['group_status'] === '1'): ?>
            <span class="label label-success"><?php echo "啟用"; ?></span>
          <?php else: ?>
            <span class="label label-danger"><?php echo "停用"; ?></span>
          <?php endif;?>
           </td>
           <td class="text-center">
             <div class="btn-group">
                <a href="edit_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" >
                  <i class="glyphicon glyphicon-pencil"></i>
               </a>
                <a href="delete_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove" onclick="return confirm('是否確定要執行這個動作？');">
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
