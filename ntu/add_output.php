<?php
  $page_title = 'output';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
  //$all_deptbasic = find_all('material_depbtasic');
  $now_user = current_user();
  $department_deptbasic = output_find_by_input($now_user['department_id']);
  //$department = departmentid_find_by_department($now_user['department_id']);
  //$input_barcode = find_by_sql("SELECT barcode FROM input_stock_recode");
  //$input_s = input_sum();   
 
?>
<?php
//這裡只把價格刪除，其他沒更改，沒看懂 
 if(isset($_POST['add_output'])){
   $req_fields = array('out-batch-number','out-number','out-date');
   validate_fields($req_fields);   
   if(empty($errors)){
     //$outdepartmentid  = remove_junk($db->escape($_POST['out-department-id']));
	 $outdepartmentbarcode = remove_junk($db->escape($_POST['out-department-barcode']));
     //$outmaterialdeptbasic  = remove_junk($db->escape($_POST['out-material-deptbasic']));
     //$outrefdeptbasic  = remove_junk($db->escape($_POST['out-ref-deptbasic']));
	 $outbatchunmber  = remove_junk($db->escape($_POST['out-batch-number']));
     $outnumber   = remove_junk($db->escape($_POST['out-number']));
     //$outunit   = remove_junk($db->escape($_POST['out-unit']));
	 $outusername  = $now_user['username'];
     $outdate   = make_date();
     $outremarks   = remove_junk($db->escape($_POST['out-remarks']));
     $outcategory   = remove_junk($db->escape($_POST['out-category']));
	 $patient   = remove_junk($db->escape($_POST['patient']));
		 
	 $query1 = find_by_sql("SELECT (v.input_qty-v.out_qty) as now_qty FROM v WHERE v.barcode ='$outdepartmentbarcode'");
	 if($query1){
		 if($outnumber <= $query1[0]['now_qty']){
			 
			 $query  ="INSERT INTO output_stock_recode (";
			 $query .="barcode, out_quantity, time, employee_id, remarks, category, patient_history";
			 $query .=") VALUES (";
			 $query .="'{$outdepartmentbarcode}','{$outnumber}', '{$outdate}', '{$outusername}', '{$outremarks}', '{$outcategory}', '{$patient}'";
			 $query .=")";
			 if($db->query($query)){
				$session->msg('s'," 出庫成功! ");
				redirect('add_output.php', false);
			 }else{
			 $session->msg('d',' 注意，出庫失敗!');
			 redirect('add_output.php', false);
			 }
		 }else{
			 $session->msg('d',' 注意，出庫數量有誤!');
			 redirect('add_output.php', false);
		 }
	 }else{
		$session->msg('d',' 注意，出庫失敗!');
		redirect('add_output.php', false);
	 }

     /*if($db->query($query)){
       $session->msg('s'," 出庫成功! ");
       redirect('add_output.php', false);
     } else {
       $session->msg('d',' 注意，出庫失敗!');
       redirect('add_output.php', false);
     }*/

   } else{
     $session->msg("d", $errors);
     redirect('add_output.php',false);
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
  <div class="col-md-10">
    <div class="panel panel-default">
      <div class="panel-heading"> 
		<strong> 
			<span class="glyphicon glyphicon-th"></span> 
			<span>新增出庫</span> 
		</strong> 
	  </div>
      <div class="panel-body">
	  <font color="#FF0000">* 表示欄位必填</font>
        <form method="post" action="add_output.php"  class="clearfix">
          <div class="form-group">
            <div class="row">
              <div class="col-md-2">
                <label for="out-department-id">部門</label>
                <input name="out-department-id" type="text" class="form-control" value="<?php echo $now_user['department_id'];?>" readonly value="<?php echo $now_user['department_id'];?>">
              </div>
			  <!--<form method="post" action="ajax(sales).php" autocomplete="off" id="out-department-barcode-from"> -->
				<div class="col-md-3">
			      <font color="#FF0000">*</font> <label for="out-department-barcode">Barcode</label>
				  <div class="input-group">	 
					 <span class="input-group-addon">
						<i>
							<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>
						</i>
					</span>
					<select class="form-control" autofocus name="out-department-barcode" id="out-department-barcode" onChange="changeBarcodeName($(this).val()) ; changeBarcodeRef($(this).val()) ; changeBarcodeLotnum($(this).val())">
                      <option value="">請掃入條碼</option>
                      <?php foreach ($department_deptbasic as $dept): ?>
                      <option value="<?php echo $dept['barcode'];?>"><?php echo $dept['barcode'];?></option>
                      <?php endforeach;?>
                    </select>
					<div id="result" class="list-group"></div>
				  </div>
				</div>
			  <!--</form>-->
			  
              <div class="col-md-3">
                <font color="#FF0000">*</font> <label for="out-material-deptbasic">醫材名稱</label>              
				<select name="out-material-deptbasic" required class="form-control" id="out-material-deptbasic" >
					<option value="">醫材名稱</option>
                </select>
			  </div>
              <div class="col-md-2">
                <font color="#FF0000">*</font> <label for="out-ref-deptbasic">REF</label>
                <select name="out-ref-deptbasic" required class="form-control" id="out-ref-deptbasic">
                <option value="">REF</option>
                </select>              
			  </div>
              <div class="col-md-2">
                <font color="#FF0000">*</font> <label for="out-batch-number">批號</label>
                <select name="out-batch-number" required class="form-control" id="out-batch-number">
				<option value="">Lot</option>
				</select>
              </div>
            </div>
          </div>
          <div class="row" >
		    <div class="col-md-2">
              <font color="#FF0000">*</font> <label for="out-number">出庫數量</label>
              <input type="number" min="0" required class="form-control" name="out-number" id="in-number" placeholder="請輸入數量" >
            </div>
            <div class="col-md-3">
			  <font color="#FF0000">*</font> <label for="patient">病人帳號</label>
			  <div class="input-group">
				  <span class="input-group-addon">
				    <i>
					  <span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>
					</i>
				  </span>
					<input type="text" required class="form-control" name="patient" id="patient" placeholder="請輸入病人帳號" >
			  </div>
            </div>
            <!--<div class="col-md-3">
              <font color="#FF0000">*</font> <label for="out-late-date">到期日期</label>
              <input name="out-late-date" type="date" class="form-control" placeholder="到期日">
            </div>-->
            <div class="col-md-2">
              <font color="#FF0000">*</font> <label for="out-category">出庫類別</label>			  
			  	  <select class="form-control" name="out-category">
				    <option value="#">請選擇</option>
					<option value="0">出庫</option>
					<option value="1">借出</option>
					<option value="2">重消</option>
				  </select> 
            </div>
			<div class="col-md-2">
              <label for="out-username">出庫人員</label>
              <input name="out-username" type="text" class="form-control" value="" placeholder="<?php echo $now_user['username'];?>" readonly>
            </div>
			<div class="col-md-3">
              <label for="out-date">出庫日期</label>
              <input name="out-date" value ="<?php echo date('Y-m-d')?>"type="date" class="form-control" readonly>
            </div>
          </div>
		  <div class="row" >
		   
		  </div>
		  </P>
          <div class="row" >
            <div class="col-md-3">
              <label for="out-remarks">備註 (如特殊出庫請填寫)</label>
              <textarea name="out-remarks" style="width: 300px;height: 70px;" ></textarea>
            </div>
          </div></P>        
          <button type="submit" name="add_output" class="btn btn-primary">出庫</button>
        </form>     
    </div>
  </div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>
<script>
function changeBarcodeName(id){
 if(id){
	$.ajax({
	type: 'GET',   //使用get方式傳送
	url: 'myajax(dp).php?type=changeBarcodeName&id='+id,  //要執行的檔案名稱，若有參數，則加在後面
	success: function(msg){ //若ajax執行成功，則執行下方程式碼
		$('#out-material-deptbasic').html(msg); //將ajax.php回傳的內容，寫入id=“num”的標籤中
		}//end success
	});//end ajax
 }else{ 
 
	$('#out-material-deptbasic').html("<option value=\"\">醫材名稱</option>");
 }
 }

function changeBarcodeRef(id){
 if(id){
	$.ajax({
	type: 'GET',   //使用get方式傳送
	url: 'myajax(dp).php?type=changeBarcodeRef&id='+id,  //要執行的檔案名稱，若有參數，則加在後面
	success: function(msg){ //若ajax執行成功，則執行下方程式碼
		$('#out-ref-deptbasic').html(msg); //將ajax.php回傳的內容，寫入id=“num”的標籤中
		}//end success
 });//end ajax
	}else 
	{  
		$('#out-ref-deptbasic').html("<option value=\"\">REF</option>");
    }
}

function changeBarcodeLotnum(id){
 if(id){
	$.ajax({
	type: 'GET',   //使用get方式傳送
	url: 'myajax(dp).php?type=changeBarcodeLotnum&id='+id,  //要執行的檔案名稱，若有參數，則加在後面
	success: function(msg){ //若ajax執行成功，則執行下方程式碼
		$('#out-batch-number').html(msg); //將ajax.php回傳的內容，寫入id=“num”的標籤中
		}//end success
 });//end ajax
	}else 
	{  
		$('#out-batch-number').html("<option value=\"\">Lot</option>");
    }
}
</script>