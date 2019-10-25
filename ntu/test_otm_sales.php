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
<script type="text/javascript">
function otmname() {
		var test = document.getElementById('out-category').value;
	if(test == '0'){
		document.getElementById('patient-id').value = "";
		document.getElementById('use-unit').value = 0;
		document.getElementById('out-number').value = "";
		document.getElementById('patient-id').removeAttribute("readonly");
		document.getElementById('use-unit').setAttribute("readonly",true);
		document.getElementById('out-number').removeAttribute("readonly");
	}else if(test== '1'){
		document.getElementById('patient-id').value = "null";
		document.getElementById('use-unit').value = 0;
		document.getElementById('out-number').value = "";
		document.getElementById('patient-id').setAttribute("readonly",true);
		document.getElementById('use-unit').setAttribute("readonly",true);
		document.getElementById('out-number').removeAttribute("readonly");
	}else{
		document.getElementById('patient-id').value = "";
		document.getElementById('use-unit').value = "";
		document.getElementById('out-number').value = 0;
		document.getElementById('patient-id').removeAttribute("readonly");
		document.getElementById('use-unit').removeAttribute("readonly");
		document.getElementById('out-number').setAttribute("readonly",true);
	}
}
</script>
<?php
//這裡只把價格刪除，其他沒更改，沒看懂 
if(isset($_POST['otm_output'])){
   $req_fields = array('out-batch-number','out-number','out-date');
   validate_fields($req_fields);   
   if(empty($errors)){
     $outdepartmentid  = remove_junk($db->escape($now_user['department_id']));
	 $outdepartmentbarcode = remove_junk($db->escape($_POST['out-department-barcode']));
     $outnumber   = remove_junk($db->escape($_POST['out-number']));
     $useunit   = remove_junk($db->escape($_POST['use-unit']));
	 $outusername  = $now_user['username'];
     $outdate   = make_date();
     $outremarks   = remove_junk($db->escape($_POST['out-remarks']));
     $outcategory   = remove_junk($db->escape($_POST['out-category']));
     $patientid  = remove_junk($db->escape($_POST['patient-id']));
	 $peopletimes = 1;
	 $outstate = 1;
	 $otmoutnum=1;
	 $otmoutcate=0;

     $rs3 = find_by_sql("Select SUM(use_times), SUM(use_people_times) from otm_material_recode where barcode='$outdepartmentbarcode'");
	 $rs4 = find_by_sql("SELECT mb.use_times, mb.use_people_times FROM input_stock_recode i left join material_deptbasic mdp ON mdp.id = i.mdp_id left join material_basic mb on mb.material_code = mdp.material_code && mb.ref_code = mdp.ref_code WHERE i.barcode='$outdepartmentbarcode' LIMIT 1"); 
     $rs5 = find_by_sql("Select otm_state from otm_material_recode where barcode='$outdepartmentbarcode' order by `id` desc limit 0,1");
	 $rs6 = find_by_sql("SELECT mb.special FROM input_stock_recode i left join material_deptbasic mdp ON mdp.id = i.mdp_id left join material_basic mb on mb.material_code = mdp.material_code && mb.ref_code = mdp.ref_code WHERE i.barcode='$outdepartmentbarcode'"); 
	 $rs7 = find_by_sql("SELECT out_quantity FROM output_stock_recode where barcode='$outdepartmentbarcode' order by output_stock_recode.id desc limit 0,1"); 
	 $rs8 = find_by_sql("SELECT mdp_id FROM v WHERE barcode = '$outdepartmentbarcode'");
	 $rs10 = find_by_sql("SELECT * FROM v WHERE v.mdp_id = '".$rs8[0]['mdp_id']."' && ((v.out_qty + '".$outnumber."') <= v.input_qty) ORDER BY v.expiry_date,v.barcode LIMIT 1");
	 	 
		if(($outcategory==2 && $rs6[0]['special']!=0)){
			if(($outcategory ==2 && $rs5[0]['otm_state']==0) || ($outcategory ==2 && count($rs5)==0)){
				$rs3[0]['SUM(use_times)']=$rs3[0]['SUM(use_times)']?$rs3[0]['SUM(use_times)']:0;
				$rs4[0]['use_times']=$rs4[0]['use_times']?$rs4[0]['use_times']:0;
				$rs7[0]['out_quantity']=$rs7[0]['out_quantity']?$rs7[0]['out_quantity']:0;
				
				if((($rs3[0]['SUM(use_times)']+$useunit > $rs4[0]['use_times']) && $rs7[0]['out_quantity']==0) || (($rs3[0]['SUM(use_people_times)']+$peopletimes > $rs4[0]['use_people_times'])&& $rs7[0]['out_quantity']==0)){
					$session->msg('d',' 此醫材已達到使用單位上限/使用人數上限，無法再行重消！');
					redirect('test_otm_sales.php', false);
				}elseif((($rs3[0]['SUM(use_times)']+$useunit > $rs4[0]['use_times']) && $rs7[0]['out_quantity']==1) || (($rs3[0]['SUM(use_people_times)']+$peopletimes > $rs4[0]['use_people_times'])&& $rs7[0]['out_quantity']==1)){
					$session->msg('d',' 已將此醫材出庫，請使用新醫材！');
					redirect('test_otm_sales.php', false);
				}elseif((($rs3[0]['SUM(use_times)']+$useunit == $rs4[0]['use_times']) && $rs7[0]['out_quantity']==0) || (($rs3[0]['SUM(use_people_times)']+$peopletimes == $rs4[0]['use_people_times'])&& $rs7[0]['out_quantity']==0)){
					$_SESSION['JSfunction2']= "link2output2('".$outdepartmentbarcode."','".$outdate."','".$outusername."','".$patientid."');";//outdepartmentbarcode,backdate,outusername,qt2
					$query2 = "INSERT INTO otm_material_recode (";
					$query2 .="barcode, use_times, time, employee_id, department_id, otm_state, remarks, patient_history, use_people_times";
					$query2 .=") VALUES (";
					$query2 .="'{$outdepartmentbarcode}', '{$useunit}', '{$outdate}', '{$outusername}', '{$outdepartmentid}', '{$outstate}', '{$outremarks}', '{$patientid}', '{$peopletimes}'";
					$query2 .=")";
					$db->query($query2);
					redirect('test_otm_sales.php', false);
				}else{
					$session->msg('s','醫材成功進入重消階段！');
					$query1  = "INSERT INTO output_stock_recode (";
					$query1 .="barcode, out_quantity, time, employee_id, remarks, patient_history, category";
					$query1 .=") VALUES (";
					$query1 .="'{$outdepartmentbarcode}','{$outnumber}', '{$outdate}', '{$outusername}', '{$outremarks}', '{$patientid}', '{$outcategory}'";
					$query1 .=");";
					$db->query($query1);
					$query2 = "INSERT INTO otm_material_recode (";
					$query2 .="barcode, use_times, time, employee_id, department_id, otm_state, remarks, patient_history, use_people_times";
					$query2 .=") VALUES (";
					$query2 .="'{$outdepartmentbarcode}', '{$useunit}', '{$outdate}', '{$outusername}', '{$outdepartmentid}', '{$outstate}', '{$outremarks}', '{$patientid}', '{$peopletimes}'";
					$query2 .=")";
					$db->query($query2);
					redirect('test_otm_sales.php', false);
				}	
			}elseif(($outcategory ==2 && $rs5[0]['otm_state']==1) || ($outcategory ==2 && count($rs5)==0)){
				if((($rs3[0]['SUM(use_times)']+$useunit <= $rs4[0]['use_times']) && $rs7[0]['out_quantity']==0) && (($rs3[0]['SUM(use_people_times)']+$peopletimes <= $rs4[0]['use_people_times'])&& $rs7[0]['out_quantity']==0)){
					$session->msg('d'," 此醫材還在重消，請利用 ''重消醫材回庫'' 功能將醫材回庫！");
					redirect('test_otm_sales.php', false);
				}else{
					if($rs7[0]['out_quantity']==0){
						$session->msg('d',' 此醫材已達到使用單位上限/使用人數上限，無法再行重消！');
						$_SESSION['JSfunction2']= "link2output2('".$outdepartmentbarcode."','".$outdate."','".$outusername."','".$patientid."');";//outdepartmentbarcode,backdate,outusername,qt2
						redirect('test_otm_sales.php', false);
					}else{
						$session->msg('d',' 注意，不能重複出庫！');
						redirect('test_otm_sales.php', false);
					}
				}
			}elseif(($outcategory !=2 && $rs5[0]['otm_state']==0) || count($rs5)==0){
				$session->msg('d'," 特殊醫材不能用一般出庫方式出庫！");
				redirect('test_otm_sales.php', false);			
			}else{
				$session->msg('d',' 注意，不能重複出庫！');
				redirect('test_otm_sales.php', false);
			}
		}elseif(($outcategory!=2 && $rs6[0]['special']==0)){
			if($rs10[0]['barcode']==$outdepartmentbarcode){
				$query  = "INSERT INTO output_stock_recode (";
				$query .="barcode, out_quantity, time, employee_id, remarks, patient_history, category";
				$query .=") VALUES (";
				$query .="'{$outdepartmentbarcode}','{$outnumber}', '{$outdate}', '{$outusername}', '{$outremarks}', '{$patientid}', '{$outcategory}'";
				$query .=");";
			}else{
				$session->msg('d',' 注意！請依照先進先出的原則使用醫材。');
				$_SESSION['JSfunction3']= "link2output3('".$outdepartmentbarcode."','".$outdate."','".$outusername."','".$patientid."');";//outdepartmentbarcode,backdate,outusername,qt2
				redirect('test_otm_sales.php', false);
			}
		}elseif(($outcategory==0 && $rs6[0]['special']==1) || ($outcategory==1 && $rs6[0]['special']==1)){
			$session->msg('d'," 此醫材為重消醫材，請重新選擇’’出貨類別’’！");
			redirect('test_otm_sales.php', false);
		}else{
			$session->msg('d'," 此醫材非重消醫材，請再確認手邊的醫材種類或是重新選擇’’出貨類別’’！");
			redirect('test_otm_sales.php', false);
		}
		
		
		if($db->query($query)){
			$session->msg('s',' 醫材出庫成功！');
			redirect('test_otm_sales.php', false);
	    }else{
			$session->msg('d',' 醫材出庫失敗，請檢查"出庫量"是否大於"入庫量"或是其他原因！');
			redirect('test_otm_sales.php', false);
		}
   }else{
	    $session->msg("d", $errors);
        redirect('test_otm_sales.php',false);
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
			<span>出庫</span> 
		</strong> 
	  </div>
      <div class="panel-body">
	  <font color="#FF0000">* 表示欄位必填</font>
        <form method="post" action="test_otm_sales.php"  class="clearfix">
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
            <div class="col-md-2">
              <font color="#FF0000">*</font> <label for="out-category">出庫類別</label>			  
			  	  <select class="form-control" name="out-category" id="out-category" onchange="otmname()">
					<option value="#">請選擇</option>
					<option value="0">出庫</option>
					<option value="1">借出</option>
					<option value="2">重消</option>
				  </select>
            </div>
			<div class="col-md-3">
              <font color="#FF0000">*</font> <label for="patient-id">病患帳號</label>
			  <div class="input-group">
			  <span class="input-group-addon">
				<i><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span></i>
			  </span>
              <input type="text" required class="form-control" name="patient-id" id="patient-id" placeholder="請輸入病患帳號" readonly>
			  </div>
            </div>
            <div class="col-md-3">
              <font color="#FF0000">*</font> <label for="use-unit">重消醫材單位數(EX:發)</label>
              <input type="number" min="0" required class="form-control" name="use-unit" id="use-unit" placeholder="請輸入數量" readonly>
            </div>
            <div class="col-md-2">
              <font color="#FF0000">*</font> <label for="out-number">出庫數量</label>
              <input type="number" min="0" required class="form-control" name="out-number" id="out-number" placeholder="請輸入數量" readonly>
            </div>
          </div>
		  <div class="row" >
		   </P><div class="col-md-2">
              <label for="out-username">出庫人員</label>
              <input name="out-username" type="text" class="form-control" value="" placeholder="<?php echo $now_user['username'];?>" readonly>
            </div>
		   <div class="col-md-3">
              <label for="out-date">出庫日期</label>
              <input name="out-date" value ="<?php echo date('Y-m-d')?>"type="date" class="form-control" readonly>
            </div>
		  </div>
          <div class="row" >
            <div class="col-md-3">
              <label for="out-remarks">備註 (如特殊出庫請填寫)</label>
              <textarea name="out-remarks" style="width: 300px;height: 70px;" ></textarea>
            </div>
          </div></P>          
          <button type="submit" name="otm_output" class="btn btn-primary">出庫</button>
        </form>     
    </div>
  </div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>



<script>

<?php
	echo empty($_SESSION['JSfunction2'])?"":$_SESSION['JSfunction2'];
	$_SESSION['JSfunction2']= "";
?>
<?php
	echo empty($_SESSION['JSfunction3'])?"":$_SESSION['JSfunction3'];
	$_SESSION['JSfunction3']= "";
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

function link2output2(outdepartmentbarcode, outdate, outusername, patientid){
	var ans = confirm("因無法再將此重消醫材回庫，請問要將此醫材出庫嗎?");
	if(ans){
		$.ajax({
		type: 'GET',
		url: 'myajax(dp).php?type=link2output2&outdepartmentbarcode='+outdepartmentbarcode+"&outdate="+outdate+
		"&outusername="+outusername+"&patientid="+patientid,
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
}

function link2output3(outdepartmentbarcode, outdate, outusername, patientid){
	var ans1 = confirm("還有效期更早的醫材，仍然要將這個醫材出庫嗎?");
	if(ans1){
		$.ajax({
		type: 'GET',
		url: 'myajax(dp).php?type=link2output3&outdepartmentbarcode='+outdepartmentbarcode+"&outdate="+outdate+
		"&outusername="+outusername+"&patientid="+patientid,
		success: 
		function(msg){
			if(msg){
				alert("出庫成功！。");
			}else{
				alert("此醫材不適用此功能！。");
			}
		}
		});
	}else{
		return false;
	}
}
</script>