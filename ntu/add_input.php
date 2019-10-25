<?php
  $page_title = '新增入庫';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  // 用Add Product改的
  $all_deptbasic = find_all('material_depbtasic');
  $now_user = current_user();
  $department_deptbasic = input_find_by_department_unique($now_user['department_id']);
  $department = departmentid_find_by_department($now_user['department_id']);
  
 ?>
<?php
//這裡只把價格刪除，其他沒更改，沒看懂 
 if(isset($_POST['add_input'])){
   $req_fields = array('in-department-deptbasic','in-ref-deptbasic','in-batch-number','in-number','in-unit','in-date','in-late-date','in-category'); //判斷有無值
   validate_fields($req_fields);
   if(empty($errors)){
		 $indepartmentid        = remove_junk($db->escape($_POST['in-department-id']));
		 $indepartmentdeptbasic = remove_junk($db->escape($_POST['in-department-deptbasic']));
		 $inrefdeptbasic        = remove_junk($db->escape($_POST['in-ref-deptbasic']));
		 $inbatchunmber         = remove_junk($db->escape($_POST['in-batch-number']));
		 $inmdpid               = input_find_id($indepartmentdeptbasic,$inrefdeptbasic,$indepartmentid);
		 $innumber              = remove_junk($db->escape($_POST['in-number']));
		 $inunit                = remove_junk($db->escape($_POST['in-unit']));
		 $inusername            = $now_user['username'];
		 $indate                = make_date();
		 $inlatedate            = remove_junk($db->escape($_POST['in-late-date']));
		 $inremarks             = remove_junk($db->escape($_POST['in-remarks']));
		 $incategory            = remove_junk($db->escape($_POST['in-category']));
		 $date= make_bardate();

		 $query1 = find_by_sql("SELECT mb.special FROM material_deptbasic mdp LEFT JOIN material_basic mb ".
		 "ON mb.material_code=mdp.material_code && mb.ref_code=mdp.ref_code WHERE mdp.department_id ='".$indepartmentid.
		 "' && mdp.material_code ='".$indepartmentdeptbasic."' && mdp.ref_code ='".$inrefdeptbasic."';");
		 
		 if($query1[0]['special'] == 0){
			$query  = "INSERT INTO input_stock_recode (";
			$query .="barcode,mdp_id,lot_num,in_quantity,in_unit,employee_id,in_time,expiry_date,remarks,category";
			$query .=") VALUES (";
			$query .="'{$indepartmentid}{$date}', '{$inmdpid[0]['id']}', '{$inbatchunmber}', '{$innumber}', '{$inunit}',  '{$inusername}', '{$indate}', '{$inlatedate}', '{$inremarks}', '{$incategory}'";
			$query .=");";
		 }else{
			$query ="";
			$query .="INSERT INTO input_stock_recode (";
			$query .="barcode,mdp_id,lot_num,in_quantity,in_unit,employee_id,in_time,expiry_date,remarks,category";
			$query .=") VALUES ";
			for($i=1;$i<($innumber+1);$i++){
			$query .="('{$indepartmentid}{$date}-{$i}', '{$inmdpid[0]['id']}', '{$inbatchunmber}', '1', '{$inunit}',  '{$inusername}', '{$indate}', '{$inlatedate}', '{$inremarks}', '{$incategory}'";
			$query .=")";
			if($i<$innumber){
			$query .=",";
			}
			}
				$query .=";";
		 }
		 
		 if($db->query($query)){
		   if($query1[0]['special'] == 0){
			   $session->msg('s',"一般醫材入庫成功!");
		   }else{
			   $session->msg('s',"重消醫材入庫成功，請注意條碼格式!");
		   }
		   redirect('add_input.php', false);
		 } else {
		   $session->msg('d',' 注意，入庫失敗!');
		   redirect('add_input.php', false);
		 }	
   } else{
     $session->msg("d", $errors);
     redirect('add_input.php',false);
   }
}
?>
<?php include_once('layouts/header.php'); ?>
    <div class="row">
      <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
    </div>
    <div class="row">
      <div class="col-md-11">
        <div class="panel panel-default">
          <div class="panel-heading"> <strong> <span class="glyphicon glyphicon-th"></span> <span>新增入庫</span> </strong> </div>
          <div class="panel-body">
		  <font color="#FF0000">* 表示欄位必填</font>
            <form action="add_input.php" method="post" class="center-block">
              <div class="form-group">
                <div class="row">
                  <div class="col-md-1">
                    <label for="in-department-id">部門</label>
                    <input name="in-department-id" type="text" class="form-control" value="<?php echo $now_user['department_id'];?>" readonly>
                  </div>
                  <div class="col-md-3">
                    <font color="#FF0000">*</font><label for="in-department-deptbasic">醫材碼</label>
					<div class="input-group">
					  <span class="input-group-addon">
					    <i class="glyphicon glyphicon-barcode" aria-hidden="true"></i>
					  </span>
						<select class="form-control" autofocus name="in-department-deptbasic" id="in-department-deptbasic" onChange="changeName($(this).val())">
						  <option value="">醫材碼</option>
                           <?php foreach ($department_deptbasic as $dept): ?>
                             <option value="<?php echo $dept['material_code'];?>"><?php echo $dept['material_code'];?></option>
                           <?php endforeach;?>
                        </select>
				     </div>
                  </div>
                  <div class="col-md-2">
				    <font color="#FF0000">*</font><label for="in-ref-deptbasic">REF</label>
						<select name="in-ref-deptbasic" required class="form-control" id="in-ref-deptbasic" onChange="changeUnit()"  onblur="changeUnit()">
						  <option value="">REF</option>
						</select>
                  </div>
                  <div class="col-md-2">
                    <font color="#FF0000">*</font> <label for="in-batch-number">批號</label>
                    <input name="in-batch-number" type="text" required class="form-control" placeholder="批號">
                  </div>
                  <div class="col-md-2">
                    <font color="#FF0000">*</font> <label for="in-number">入庫數量</label>
                    <input type="number" required class="form-control" name="in-number" id="in-number" placeholder="數量" min="0">
                  </div>
				  <div class="col-md-2">
                  <label for="in-unit">單位</label>
                  <select name="in-unit" required class="form-control" id="in-unit" readonly >
                    <option value="">單位</option>
                  </select>
                </div>
                </div>
              </div>
              <div class="row" >
                <div class="col-md-4">
                  <font color="#FF0000">*</font> <label for="in-late-date">到期日期</label>
                  <input name="in-late-date" type="date" class="form-control" placeholder="到期日" >
                </div>
                <div class="col-md-2">
                  <font color="#FF0000">*</font> <label for="in-category">類別</label>
				  <select class="form-control" name="in-category">
						 <option value="#">請選擇</option>
						 <option value="3">入庫</option>
						 <option value="4">借入</option>
						 <option value="5">特殊</option>
				  </select>
                </div>
				<div class="col-md-2">
                  <label for="in-username">入庫人員</label>
                  <input name="in-username" type="text" class="form-control" value="" placeholder="<?php echo $now_user['username'];?>" readonly>
                </div>
                <div class="col-md-3">
                  <label for="in-date">入庫日期</label>
                  <input name="in-date" value ="<?php echo date('Y-m-d')?>"type="date" class="form-control" readonly>
                </div>
              </div></P>
              <div class="row" >
                <div class="col-md-3">
                  <label for="in-remarks">備註 (如特殊入庫請填寫)</label>
                  <textarea name="in-remarks" type="text" style="width: 300px;height: 70px;" ></textarea>
                </div>
              </div>
              </P>      
                <button type="submit" name="add_input" class="btn btn-primary">新增</button>
			  
		  </form>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
<script>
function changeName(id){
	if(id){
		$.ajax({
		type: 'GET',   //使用get方式傳送
		url: 'myajax.php?type=changeName&id='+id,  //要執行的檔案名稱，若有參數，則加在後面
		success: function(msg){ //若ajax執行成功，則執行下方程式碼
			$('#in-ref-deptbasic').html(msg); //將ajax.php回傳的內容，寫入id=“num”的標籤中	
			}//end success
		});//end ajax
	}else $('#in-ref-deptbasic').html("<option value=\"\">REF</option>");
	
	setTimeout(changeUnit,500);
}
	
function changeUnit(){	
	var id=$('#in-ref-deptbasic').val();
	if(id){
		$.ajax({
		type: 'GET',   //使用get方式傳送
		url: 'myajax.php?type=changeUnit&id='+id,  //要執行的檔案名稱，若有參數，則加在後面
		success: function(msg){ //若ajax執行成功，則執行下方程式碼
			$('#in-unit').html(msg); //將ajax.php回傳的內容，寫入id=“num”的標籤中
			}//end success
		});//end ajax
	}else $('in-unit').html("<option value=\"\">單位</option>");
}	
</script>