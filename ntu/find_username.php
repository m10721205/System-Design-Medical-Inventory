<?php
  $page_title = '病人使用紀錄';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>

<?php
  if(isset($_POST['find_username'])){
	$in_inf  = remove_junk($db->escape($_POST['user_inf']));
	$user_result = find_username($in_inf);
  }
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
			<p><span class="glyphicon glyphicon-th"></span>
			<span>查詢 病人使用紀錄</span></p>	
			<form method="post" action="find_username.php" class="clearfix">
			<div class="from-group">
			 <div class="row">
				<div class="col-md-12">
					<label for="user">病人帳號:</label>
				 <div class="input-group">
					<span class="input-group-addon">
					 <i class="glyphicon glyphicon-barcode" aria-hidden="true"></i>
					</span>
					<input autofocus type="text" class="form-control"  style="width: 150px;" name="user_inf" id="user_inf" placeholder="輸入病人帳號">
					<div class="col-md-1">
						<button type="submit" name="find_username" class="btn btn-primary" onclick="display1()">搜尋</button>
					</div>
					
			   </div>
			  </div>
			</div>
          </strong>
        </div>
		</form>
		
        <div class="panel-body" style="width:auto;">
          <table class="table table-bordered table-striped" style="width:auto;">
            <thead>
              <tr>
                <th class="text-center" style="width: 5%;">#</th>
				<th class="text-center" style="width: 30%;">醫材名稱</th>
				<th class="text-center" style="width: 10%;">醫材碼</th>
				<th class="text-center" style="width: 10%;">REF碼</th>
				<th class="text-center" style="width: 10%;">使用數量</th>
				<th class="text-center" style="width: 25%;">時戳</th>
				<th class="text-center" style="width: 10%;">成本</th>
             </tr>
            </thead>
           <tbody>
			 <?php $user_result = empty($user_result)?array():$user_result;
				   $con = empty($con)?0:$con;
             foreach ($user_result as $user_results):?>
             <tr>
               <td class="text-center"><?php echo count_id();?></td>
						<td class="text-center"><?php echo remove_junk($user_results['material_dpname']); ?></td>
						<td class="text-center"><?php echo remove_junk($user_results['material_code']); ?></td>
						<td class="text-center"><?php echo remove_junk($user_results['ref_code']); ?></td>
						<td class="text-center"><?php echo remove_junk($user_results['out_quantity']); ?></td>
						<td class="text-center"><?php echo remove_junk($user_results['time']); ?></td>
						<td class="text-center"><?php echo remove_junk($user_results['buy_price']); ?></td>
						<?php 
						$con += $user_results['buy_price'] ?>
             </tr>
             <?php endforeach;?>
           </tbody>
         </table>
		 <HR size="8px" color ="#1AFF19">
		 <div class="from-group" align="right">
			<strong>總成本: <?php echo $con?></strong>
		 </div>
        </div>
      </div>
    </div>
  </div>
  
<?php include_once('layouts/footer.php'); ?>
