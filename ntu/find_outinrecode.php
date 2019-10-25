<?php
  $page_title = '出/入庫紀錄查詢';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
   $now_user = current_user();
?>

<?php

$category=empty($_POST['category'])?"":$_POST['category'];
$date=empty($_POST['date'])?"":$_POST['date'];
$startdate=empty($_POST['start-date'])?"":$_POST['start-date'];
$enddate=empty($_POST['end-date'])?"":$_POST['end-date'];

 if(isset($_POST['find_inf'])){
	 $year  = date('Y');
	 $month = date('m');
	 $day = date('d');
	 if ($_POST['category'] == 0){
	  switch($_POST['date']){
		case 0:
			$stock_recodes = find_daily_dates_outrecode($year,$month,$day,$now_user['department_id']);
		break;
		case 1:
			$stock_recodes = find_monthly_dates_outrecode($year,$month,$now_user['department_id']);
		break;
		case 2:
			$stock_recodes = find_range_dates_outrecode($_POST['start-date'],$_POST['end-date'],$now_user['department_id']);
		break;
	  }
	 }else{
	  switch($_POST['date']){
		case 0:
			$stock_recodes = find_daily_dates_inrecode($year,$month,$day,$now_user['department_id']);
		break;
		case 1:
			$stock_recodes = find_monthly_dates_inrecode($year,$month,$now_user['department_id']);
		break;
		case 2:
			$stock_recodes = find_range_dates_inrecode($_POST['start-date'],$_POST['end-date'],$now_user['department_id']);
		break;
	  }
	 }
 }
?>

<script type="text/javascript">
function changedate() {
		var test = document.getElementById('date').value;
	if(test == '2'){
		document.getElementById('select_date').style.visibility= "visible"; 
	}else{
		document.getElementById('select_date').style.visibility= "hidden"; 
	}
}
</script>

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
			<span>查詢 出/入庫 紀錄</span></p>
			<font color="#FF0000">*表示欄位必填</font>
			
			<div class="from-group">
			 <div class="row">
			  <form method="post" action="find_outinrecode.php">
				<div class="col-md-1">
					<label for="product-dept_id">部門</label>
				 <div class="input-group">	
					<input class="form-control" style="width: 60px;" value="<?php echo $now_user['department_id'];?>" name="in-department-id" readonly >
				 </div>
				</div>
				
				<div class="col-md-2">
					<font color="#FF0000">*</font><label for="category">查詢類別</label>
				 <div class="input-group">	
					<select class="form-control" style="width: 80px;" id ="category" name="category">
						 <option value="0" <?php echo ($category==0)?"selected":"";?>>出庫</option>
						 <option value="1" <?php echo ($category==1)?"selected":"";?>>入庫</option>
					</select> 
				 </div>
				</div>
				<div class="col-md-2">
					<font color="#FF0000">*</font><label for="date">選擇時間</label>
				 <div class="input-group">	
					<select class="form-control" style="width: 110px;" id ="date" name="date" onchange="changedate()">
						 <option value="0" <?php echo ($date==0)?"selected":"";?>>當日</option>
						 <option value="1" <?php echo ($date==1)?"selected":"";?>>當月</option>
						 <option value="2" <?php echo ($date==2)?"selected":"";?>>自訂區間</option>
					</select> 
				 </div>
				</div>
				
				<div class="col-md-3" id ="select_date" style="visibility: hidden">
					<font color="#FF0000">*</font><label for="date">選擇區間</label>
				 <div class="input-group">	
					<input type="text" class="datepicker form-control" name="start-date" id="start-date" placeholder="From" value="<?php echo $startdate;?>">
					<span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
					<input type="text" class="datepicker form-control" name="end-date" id="end-date" placeholder="To" value="<?php echo $enddate;?>">
				 </div>
				</div>
				<div class="col-md-1"><br/>
						<button type="submit" name="find_inf" class="btn btn-primary " onclick="display1()">搜尋</button>	
				</div>
			  </form>
			 </div>
			</div>
          </strong>
        </div>
        <div class="panel-body" style="width:1000px;overflow-x:scroll">
          <table class="table table-bordered table-striped" style="width:1200px;">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
				<th class="text-center" >條碼</th>
				<th class="text-center" >醫材名稱</th>
				<th class="text-center" >醫材碼</th>
				<th class="text-center" >REF碼</th>
				<th class="text-center" >批號</th>
				<th class="text-center" >到期日</th>
                <th class="text-center" >數量</th>
				<?php if(isset($_POST['find_inf'])){
				if($_POST['category'] == 0){ echo "<th class='text-center' >病人帳號</th>"; }
				}?>
                <th class="text-center" >新增時間</th>
				<th class="text-center" >操作人/類別</th>
				<th class="text-center" >備註</th>
             </tr>
            </thead>
           <tbody>
             <?php 
			 $stock_recodes=empty($stock_recodes)?array():$stock_recodes; //判斷變數
			 foreach ($stock_recodes as $stock_recode){?>
             <tr>
               <td class="text-center"><?php echo count_id();?></td>
						<td class="text-center"><?php echo remove_junk($stock_recode['barcode']); ?></td>
						<td class="text-center"><?php echo remove_junk($stock_recode['material_dpname']); ?></td>
						<td class="text-center"><?php echo remove_junk($stock_recode['material_code']); ?></td>
						<td class="text-center"><?php echo remove_junk($stock_recode['ref_code']); ?></td>
						<td class="text-center"><?php echo remove_junk($stock_recode['lot_num']); ?></td>
						<td class="text-center"><?php echo remove_junk($stock_recode['expiry_date']); ?></td>
						<td class="text-right"><?php echo remove_junk($stock_recode['qty']); ?><?php echo remove_junk($stock_recode['unit']); ?><?php if($_POST['category'] == 1){ echo "({$stock_recode['chanqty']}{$stock_recode['out_unit']})"; }?></td>
						<?php if($_POST['category'] == 0){ echo "<td class='text-center'>{$stock_recode['patient_history']} </td>"; }?>
						<td class="text-center"><?php echo remove_junk($stock_recode['time']); ?></td>
						<td class="text-center"><?php echo remove_junk($stock_recode['employee_id']); ?>/<?php 
						
						 switch(remove_junk($stock_recode['category'])){
						 case 0:
						  echo "出庫";
						  break;
						 case 1:
						  echo "借出";
						  break;
						 case 2:
						  echo "重消";
						  break;
						 case 3:
						  echo "入庫";
						  break;
						 case 4:
						  echo "借入";
						  break;
						 case 5:
						  echo "特殊";
						  break;
						  default:
						  remove_junk($stock_recode['category']);
						  break;
						 }
						?></td>
						<td class="text-center"><?php echo remove_junk($stock_recode['remarks']); ?></td>
						
             </tr>
						 <?php }?>
           </tbody>
         </table>
        </div>
      </div>
    </div>
  </div>
  <script>
<?php
if($date==2){
	echo "changedate();";
}
?>
</script>
<?php include_once('layouts/footer.php'); ?>
