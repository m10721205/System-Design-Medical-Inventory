<?php
  $page_title = '新增庫房醫材';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  //修改過等級，2改3
  $all_deptbasic = find_all('material_deptbasic');
  $all_baisc = find_by_sql("SELECT * FROM material_basic ORDER BY material_code,ref_code");
  $all_photo = find_all('media');
  $now_user = current_user();
  $department_deptbasic = input_find_by_department($now_user['department_id']);

?>
<?php
//這裡只把價格刪除，其他沒更改，沒看懂 
 if(isset($_POST['add_product'])){
   $req_fields = array('product-in_unit','product-uni_quan','product-out_uni','product-save_stock','product-highest_stock','product-location','product-employee_id');
   validate_fields($req_fields);
   if(empty($errors)){
  $p_dept_id  = remove_junk($db->escape($_POST['in-department-id']));
  $p_code  = remove_junk($db->escape($_POST['product-dept_id']));
  $p_name = remove_junk($db->escape($_POST['in-dep-dpname']));
  $p_in_unit = remove_junk($db->escape($_POST['product-in_unit']));
  $p_uni_quan = remove_junk($db->escape($_POST['product-uni_quan']));
  $p_out_uni = remove_junk($db->escape($_POST['product-out_uni']));
  $p_save_stock = remove_junk($db->escape($_POST['product-save_stock']));
  $p_highest_stock = remove_junk($db->escape($_POST['product-highest_stock']));
  $p_location = remove_junk($db->escape($_POST['product-location']));
  $p_employee_ID = remove_junk($db->escape($_POST['product-employee_id']));
  $p_ref = remove_junk($db->escape($_POST['product-ref']));
     
     $date    = make_date();
     $query  = "INSERT INTO material_deptbasic (";
     $query .=" department_id, material_code, ref_code, material_dpname, in_unit, unit_quantity, out_unit,safety_stock, highest_stock, stock_location, employee_id, date";
     $query .=") VALUES (";
     $query .="'{$p_dept_id}', '{$p_code}', '{$p_ref}', '{$p_name}', '{$p_in_unit}', '{$p_uni_quan}', '{$p_out_uni}', '{$p_save_stock}', '{$p_highest_stock}', '{$p_location}','{$p_employee_ID}', '{$date}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE material_dpname='{$p_name}'";
     if($db->query($query)){
       $session->msg('s',"Product added ");
       redirect('add_product(dp).php', false);
     } else {
       $session->msg('d',' Sorry failed to added!');
       redirect('product(dp).php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_product(dp).php',false);
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
            <span>新增庫房醫材</span>
         </strong>
        </div>
<!-- 整個表格開始 -->
        <div class="panel-body">
		<font color="#FF0000">* 表示欄位必填</font> 
         <div class="col-md-12">
          <form method="post" action="add_product(dp).php" class="clearfix">
<!-- 以下為同一列 -->
              <div class="form-group">
                <div class="row">
                  <div class="col-md-1">
				   <label for="product-dept_id">部門</label>
					<input class="form-control" style="width: 55px;" value="<?php echo $now_user['department_id'];?>" name="in-department-id" readonly >
                  </div>
                </div>
              </div>
<!-- 以下為同一列 -->
			<div class="form-group">
			   <div class="row">
				<div class="col-md-3">
				 <font color="#FF0000">*</font> <label for="product-code">醫材碼</label>
				 <div class="input-group">
				  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-barcode"></i>
                  </span>
				  <select class="form-control" name="product-dept_id" onChange="changeName($(this).val()) ; changeRef($(this).val())">
                      <option value="">醫材碼</option>
			         <?php  foreach ($all_baisc as $basic): ?>
                      <option value="<?php echo $basic['material_code'];?>">     
				    <?php echo $basic['material_code'];?></option>
                    <?php endforeach; ?>
                    </select>
				 </div>
				</div>
				<div class="col-md-3">
				 <font color="#FF0000">*</font> <label for="product-ref">REF碼</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-barcode"></i>
                  </span>
                  <select name="product-ref" required class="form-control" id="product-ref">
                   <option value="">REF</option>
                    </select>				 
				 </div>
				</div>
				<div class="col-md-3">
				 <label for="in-dep-dpname">醫材名稱</label>
				 <div class="input-group">
				   <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                   </span>
                    <select name="in-dep-dpname" required class="form-control" id="in-dep-dpname">
                      <option value="">醫材名稱</option>
                    </select>
				 </div>
				</div>
			  </div>
			</div>
<!-- 以下為同一列 -->
			<div class="form-group">
			   <div class="row">
				<div class="col-md-3">
				 <font color="#FF0000">*</font> <label for="product-save_stock">基本量</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-save_stock" placeholder="基本量">
				 </div>
				</div>
				<div class="col-md-3">
				 <font color="#FF0000">*</font> <label for="product-highest_stock">最高庫存量</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-highest_stock" placeholder="最高庫存量">
				 </div>
				</div>
			  </div>
			</div>
<!-- 以下為同一列 -->
			<div class="form-group">
			   <div class="row">
			    <div class="col-md-3">    <!-- 欄位大小 -->
					<font color="#FF0000">*</font> <label for="product-in_unit">入庫單位</label>
                   <!--<div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>   <!-- 插入入庫數量圖案 -->
                     <!--</span>
                     <input type="number" class="form-control" name="product-quantity" placeholder="入庫數量">
                  </div>
                 </div>
				 <div class="col-md-3">   <!-- 這裡是入庫 -->
                   <div class="input-group">
					<span class="input-group-addon">
                      <i class="glyphicon glyphicon-log-in" aria-hidden="true"></i>
                     </span>
					 <select class="form-control" name="product-in_unit">
					     <option value="">入庫單位</option>
						 <option value="個">個</option>
						 <option value="盒">盒</option>
						 <option value="包">包</option>						 
						 <option value="袋">袋</option>
						 <option value="箱">箱</option>
						 <option value="套">套</option>
					</select>
                </div>

				   <!-- 
				   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-log-in" aria-hidden="true"></i>
                     </span>
                     <input type="text" class="form-control" name="in-nit" placeholder="入庫單位">
                  </div> -->
                 </div>
				 <div class="col-md-3">    <!-- 轉換單位 -->
				  <font color="#FF0000">*</font> <label for="product-uni_quan">轉換數量</label>
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>   <!-- 插入入庫數量圖案 -->
                     </span>
                     <input type="number" class="form-control" name="product-uni_quan" placeholder="轉換數量">
                  </div>
                 </div>
				 
				 <div class="col-md-3">  <!-- 這裡是出庫 -->
				  <font color="#FF0000">*</font> <label for="product-out_uni">出庫單位</label>
				   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-log-out" aria-hidden="true""></i>
                     </span>
					 
                    <select class="form-control" name="product-out_uni">
					     <option value="">出庫單位</option>
						 <option value="個">個</option>
						 <option value="瓶">瓶</option>
						 <option value="包">包</option>						 
						 <option value="袋">袋</option>
						 <option value="片">片</option>
						 <option value="套">套</option>
					</select>
				   </div>
				 </div>
			  </div>
			</div>

<!-- 以下為同一列 -->
              <div class="form-group">
               <div class="row">
                 <div class="col-md-3">
				 <font color="#FF0000">*</font> <label for="product-location">存放庫位</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-location" placeholder="存放庫位">
				 </div>
				</div>
				<div class="col-md-2">
				 <label for="product-employee_id">建檔人</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-pencil"></i>
                  </span>
				   <input name="product-employee_id" type="text" class="form-control" value = "<?php echo $now_user['username'];?>" readonly>
                  <!--input type="text" class="form-control" name="product-employee_id" placeholder="建檔人"-->
				 </div>
				</div>
			  </div>
			 </div>
<!-- 以下為同一列 -->
              <button type="submit" name="add_product" class="btn btn-info" onclick="return confirm('是否確定要執行這個動作？');" >新增醫材</button>
          </form>
         </div>
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
	url: 'myajax(dp).php?type=changeName&id='+id,  //要執行的檔案名稱，若有參數，則加在後面
	success: function(msg){ //若ajax執行成功，則執行下方程式碼
		$('#in-dep-dpname').html(msg); //將ajax.php回傳的內容，寫入id=“num”的標籤中
		}//end success
	});//end ajax
 }else{ 
 
	$('#in-dep-dpname').html("<option value=\"\">醫材名稱</option>");
 }
 }

function changeRef(id){
 if(id){
	$.ajax({
	type: 'GET',   //使用get方式傳送
	url: 'myajax(dp).php?type=changeRef&id='+id,  //要執行的檔案名稱，若有參數，則加在後面
	success: function(msg){ //若ajax執行成功，則執行下方程式碼
	$('#product-ref').html(msg); //將ajax.php回傳的內容，寫入id=“num”的標籤中
	}//end success
 });//end ajax
	}else 
	{  
		$('#product-ref').html("<option value=\"\">REF</option>");
    }
}
</script>