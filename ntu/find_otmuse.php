<?php
  $page_title = '重消醫材紀錄';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>

<?php
  if(isset($_POST['find_otmuse'])){
	$otm_inf  = remove_junk($db->escape($_POST['otm_barcode']));
	$otm_result = find_by_sql("SELECT * FROM otm_material_recode left JOIN users ON users.username = otm_material_recode.employee_id WHERE barcode = '$otm_inf'");
    $rs1 = find_by_sql("SELECT mb.use_people_times,mb.use_times FROM input_stock_recode i LEFT JOIN material_deptbasic md ON md.id = i.mdp_id LEFT JOIN material_basic mb ON mb.material_code = md.material_code && mb.ref_code = md.ref_code WHERE i.barcode ='$otm_inf'");
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
			<span>查詢 重消醫材紀錄</span></p>	
			<form method="post" action="find_otmuse.php" class="clearfix">
			<div class="from-group">
			 <div class="row">
				<div class="col-md-12">
					<label for="user">重消醫材條碼:</label>
				 <div class="input-group">
					<span class="input-group-addon">
					 <i class="glyphicon glyphicon-barcode" aria-hidden="true"></i>
					</span>
					<input autofocus type="text" class="form-control"  style="width: 200px;" name="otm_barcode" id="otm_barcode" placeholder="掃入條碼">
					<div class="col-md-1">
						<button type="submit" name="find_otmuse" class="btn btn-primary" onclick="display1()">搜尋</button>
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
				<th class="text-center" style="width: 30%;">條碼</th>
				<th class="text-center" style="width: 10%;">使用次數</th>
				<th class="text-center" style="width: 10%;">使用人次</th>
				<th class="text-center" style="width: 25%;">時戳</th>
				<th class="text-center" style="width: 10%;">出庫人</th>
				<th class="text-center" style="width: 10%;">狀態</th>
             </tr>
            </thead>
           <tbody>
			 <?php $otm_result = empty($otm_result)?array():$otm_result;
			 $con1 = empty($con1)?0:$con1;
			 $rs = empty($rs)?0:$rs;
             foreach ($otm_result as $otm_results):?>
             <tr>
               <td class="text-center"><?php echo count_id();?></td>
						<td class="text-center"><?php echo remove_junk($otm_results['barcode']); ?></td>
						<td class="text-center"><?php echo remove_junk($otm_results['use_times']); ?></td>
						<td class="text-center"><?php echo remove_junk($otm_results['use_people_times']); ?></td>
						<td class="text-center"><?php echo remove_junk($otm_results['time']); ?></td>
						<td class="text-center"><?php echo remove_junk($otm_results['name']); ?></td>
						<td class="text-center"><?php 
						 switch(remove_junk($otm_results['otm_state'])){
						 case 0:
						  echo "在庫";
						  break;
						 case 1:
						  echo "消毒中";
						  break;
						  default:
						  remove_junk($otm_results['otm_state']);
						  break;
						 }?>
						 <?php 
						$con1 += $otm_results['use_times'];
						$rs += $otm_results['use_people_times']; ?></td>
             </tr>
             <?php endforeach;?>
           </tbody>
         </table>
		 <HR size="8px" color ="#1AFF19">
		 <div class="from-group" align="right">
			<strong>基本使用次數/人次: <?php if(isset($_POST['find_otmuse'])){ echo $rs1[0]['use_times']."/".$rs1[0]['use_people_times'] ;}?></strong>
		 </div>
		 <div class="from-group" align="right">
			<strong>合計使用次數/人次: <?php if(isset($_POST['find_otmuse'])){ echo $con1."/".$rs ;}?></strong>
		 </div>
		 <HR size="8px" color ="#1AFF19">
		 <div class="from-group" align="right">
			<strong>剩餘使用次數/人次: <?php if(isset($_POST['find_otmuse'])){ echo ($rs1[0]['use_times']-$con1)."/".($rs1[0]['use_people_times']-$rs) ;}?></strong>
		 </div>
        </div>
      </div>
    </div>
  </div>
  
<?php include_once('layouts/footer.php'); ?>
