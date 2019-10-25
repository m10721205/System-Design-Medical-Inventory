<?php
  $page_title = '即時庫存查詢';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
   //renew_qty();
   $now_user = current_user();
   $nowqty_infos = find_all_product_nowqty_by_dp( $now_user['department_id'] );
   
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
    <div class="col-md-10">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
			<p><span class="glyphicon glyphicon-th"></span>
			<span>查詢 即時庫存</span></p>
			
			<div class="from-group">
			 <div class="row">
				<div class="col-md-12">
					<label for="product-dept_id">部門:</label>
				 <div class="input-group">	
					<input class="form-control" style="width: 60px;" value="<?php echo $now_user['department_id'];?>" name="in-department-id" readonly >
				 </div>
				</div>
			 </div>
			</div>
          </strong>
        </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
				<th class="text-center" style="width: 20%;">醫材名稱</th>
				<th class="text-center" style="width: 150px;">醫材碼</th>
				<th class="text-center" style="width: 120px;">REF碼</th>
				<th class="text-center" style="width: 120px;">基本庫存量</th>
				<th class="text-center" style="width: 120px;">最高庫存量</th>
				<th class="text-center" >即時庫存量</th>
             </tr>
            </thead>
           <tbody>
             <?php foreach ($nowqty_infos as $nowqty_info):?>
             <tr>
               <td class="text-center"><?php echo count_id();?></td>
						<td class="text-center"><?php echo remove_junk($nowqty_info['material_dpname']); ?></td>
						<td class="text-center"><?php echo remove_junk($nowqty_info['material_code']); ?></td>
						<td class="text-center"><?php echo remove_junk($nowqty_info['ref_code']); ?></td>
						<td class="text-right"><?php echo remove_junk($nowqty_info['safety_stock']); ?><?php echo remove_junk($nowqty_info['unit']); ?></td>	
						<td class="text-right"><?php echo remove_junk($nowqty_info['highest_stock']); ?><?php echo remove_junk($nowqty_info['unit']); ?></td>						
						<!--<td class="text-right"><?php echo remove_junk($nowqty_info['now_qty']); ?><?php echo remove_junk($nowqty_info['unit']); ?></td>-->
						<?php 
						if($nowqty_info['now_qty'] < $nowqty_info['safety_stock']){
							echo "<td bgcolor='#FF5959' class='text-center'><font color='white'>{$nowqty_info['now_qty']}{$nowqty_info['unit']}</td>";
						}elseif ($nowqty_info['now_qty'] >= $nowqty_info['safety_stock'] && $nowqty_info['now_qty'] <= ($nowqty_info['safety_stock']+(($nowqty_info['highest_stock']-$nowqty_info['safety_stock'])/2)) ){ 
							echo "<td bgcolor='#FFC559' class='text-center'>{$nowqty_info['now_qty']}{$nowqty_info['unit']}</td>";			
						}else{
							echo "<td class='text-center'>{$nowqty_info['now_qty']}{$nowqty_info['unit']}</td>";
						}
						?>
             </tr>
             <?php endforeach;?>
           </tbody>
         </table>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
