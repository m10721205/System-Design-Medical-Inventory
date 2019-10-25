<?php
  $page_title = 'output';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
  //$all_deptbasic = find_all('material_depbtasic');
  $now_user = current_user();
  $department_deptbasic = output_find_by_input($now_user['department_id']);
  //$department = departmentid_find_by_department($now_user['department_id']);
  //$input_barcode = find_by_sql("SELECT barcode FROM input_stock_recode");
  //$input_s = input_sum();
  

 
?>
<?php
//這裡只把價格刪除，其他沒更改，沒看懂 
if(isset($_POST['add_backotm'])){
   $req_fields = array('out-batch-number');
   validate_fields($req_fields);
	if(empty($errors)){
		$outdepartmentid  = remove_junk($db->escape($now_user['department_id']));
		$outdepartmentbarcode = remove_junk($db->escape($_POST['out-department-barcode']));
		$outusername  = $now_user['username'];
		$backdate   = make_date();
		$outremarks   = remove_junk($db->escape($_POST['out-remarks']));
		$patientid = 0;
		$peopletimes = 0;
		$outstate = 0;
		$useunit   = 0;
		$outnumber = 1;
		
		$rs1 = find_by_sql("SELECT otm_state FROM otm_material_recode where barcode='$outdepartmentbarcode' order by otm_material_recode.id desc limit 0,1"); 
		$rs2 = find_by_sql("SELECT mb.special FROM input_stock_recode i left join material_deptbasic mdp ON mdp.id = i.mdp_id left join material_basic mb on mb.material_code = mdp.material_code && mb.ref_code = mdp.ref_code WHERE i.barcode='$outdepartmentbarcode'"); 
	    $rs3 = find_by_sql("SELECT out_quantity FROM output_stock_recode where barcode='$outdepartmentbarcode' order by output_stock_recode.id desc limit 0,1"); 
		$qt1 = find_by_sql("SELECT sum(omr.use_people_times) as total_peoples,sum(omr.use_times) as total_times,mb.use_people_times as basic_peoples,mb.use_times as basic_times FROM otm_material_recode omr LEFT JOIN input_stock_recode i ON i.barcode = omr.barcode left JOIN material_deptbasic mdp ON mdp.id = i.mdp_id left JOIN material_basic mb ON mb.material_code = mdp.material_code && mb.ref_code = mdp.ref_code WHERE omr.barcode='$outdepartmentbarcode'");
		$qt2 = find_by_sql("SELECT patient_history FROM otm_material_recode where barcode='$outdepartmentbarcode' order by otm_material_recode.id desc limit 0,1");
		
		if($rs2[0]['special']==1){
			if($rs1[0]['otm_state']==0 && $rs3[0]['out_quantity']==1){
				$session->msg('d','此醫材已出庫，因此不適用此功能！');
				redirect('test_otm.php', false);
			}elseif($rs1[0]['otm_state']==0){
				$session->msg('s','此醫材已重消回庫！');
				redirect('test_otm.php', false);
			}elseif($qt1[0]['total_peoples'] >= $qt1[0]['basic_peoples'] || $qt1[0]['total_times'] >= $qt1[0]['basic_times'] || $rs3[0]['out_quantity']==1){
				$session->msg('d','此醫材已達使用上限或是醫材已經出庫，無法使用此功能！');
				//$_SESSION['JSfunction']= "link2output('".$outdepartmentbarcode."','".$backdate."','".$outusername."','".$qt2[0]['patient_history']."');";//outdepartmentbarcode,backdate,outusername,qt2
				redirect('test_otm.php', false);
			}else{
				$query = "INSERT INTO otm_material_recode (";
				$query .="barcode, use_times, time, employee_id, department_id, otm_state, remarks, patient_history, use_people_times";
				$query .=") VALUES (";
				$query .="'{$outdepartmentbarcode}', '{$useunit}', '{$backdate}', '{$outusername}', '{$outdepartmentid}', '{$outstate}', '{$outremarks}', '{$patientid}', '{$peopletimes}'";
				$query .=")";
			}
		}else{
			$session->msg('d','此醫材不適用此功能！');
			redirect('test_otm.php', false);
		}
		
		if($db->query($query)){
			$session->msg('s','醫材回庫成功！');
			redirect('test_otm.php', false);
		}else{
			$session->msg('d','醫材回庫失敗！請確認手邊醫材資訊 (例如:使用人數限制或是使用單位限制)。');
			redirect('test_otm.php', false);
		}
	
	}else{
     $session->msg("d", $errors);
     redirect('test_otm.php',false);
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
			<span>重消醫材回庫</span> 
		</strong> 
	  </div>
      <div class="panel-body">
	  <font color="#FF0000">* 表示欄位必填</font>
        <form method="post" action="test_otm.php"  class="clearfix">
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
					<option value="">請輸入醫材名稱</option>
                </select>
			  </div>
              <div class="col-md-2">
                <font color="#FF0000">*</font> <label for="out-ref-deptbasic">REF</label>
                <select name="out-ref-deptbasic" required class="form-control" id="out-ref-deptbasic">
                <option value="">請輸入REF碼</option>
                </select>              
			  </div>
              <div class="col-md-2">
                <font color="#FF0000">*</font> <label for="out-batch-number">批號</label>
                <select name="out-batch-number" required class="form-control" id="out-batch-number">
				<option value="">請輸入批號</option>
				</select>
              </div>
            </div>
          </div>
          <div class="row" >


            <div class="col-md-3">
              <label for="back-date">回庫日期</label>
              <input name="back-date" value ="<?php echo date('Y-m-d')?>"type="date" class="form-control" readonly>
            </div>
			<!--
            <div class="col-md-3">
              <font color="#FF0000">*</font> <label for="out-late-date">到期日期</label>
              <input name="out-late-date" type="date" class="form-control" placeholder="到期日">
            </div>
			-->
            <div class="col-md-2">
              <label for="out-username">回庫人員</label>
              <input name="out-username" type="text" class="form-control" value="" placeholder="<?php echo $now_user['username'];?>" readonly>
            </div>
          </div>
          <div class="row" >
            <div class="col-md-2">
              <label for="out-remarks">備註</label>
              <textarea name="out-remarks" style="width:800px;height:50px;" placeholder="有任何註記需求可填寫此欄位"></textarea>
            </div>
          </div>          
          <button type="submit" name="add_backotm" class="btn btn-primary">重消回庫</button>
        </form>     
    </div>
  </div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>

<script>

<?php
	echo empty($_SESSION['JSfunction'])?"":$_SESSION['JSfunction'];
	$_SESSION['JSfunction']= "";
?>
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
/*
function link2output(outdepartmentbarcode,backdate,outusername,qt2){
	var ans = confirm("因無法再將此重消醫材回庫，請問要將此醫材出庫嗎?");
	if(ans){
		$.ajax({
		type: 'GET',
		url: 'myajax(dp).php?type=link2output&outdepartmentbarcode='+outdepartmentbarcode+"&backdate="+backdate+
		"&outusername="+outusername+"&qt2="+qt2,
		success: 
		function(msg){
			if(msg){
				alert("已將該重消醫材出庫。");
			}else{
				alert("此醫材不適用此功能！。");
			}
		}
		});
	}else{
		return false;
	}
}*/
</script>