<?php
  $page_title = '新增醫材';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  //修改過等級，2改3
  $all_department = find_all('department');
  $all_photo = find_all('media');
  $now_user = current_user();
?>
<script type="text/javascript">
function funname() {
		var test = document.getElementById('product-special').value;
	if(test == '0'){
		document.getElementById('product-use_people_times').value = 1;
		document.getElementById('product-use_times').value = 1;
		document.getElementById('product-use_people_times').setAttribute("readonly",true);
		document.getElementById('product-use_times').setAttribute("readonly",true);
	}else if(test== '1'){
		document.getElementById('product-use_people_times').value = "";
		document.getElementById('product-use_times').value = "";
		document.getElementById('product-use_people_times').removeAttribute("readonly");
		document.getElementById('product-use_times').removeAttribute("readonly");
	}else{
		document.getElementById('product-use_people_times').value = "";
		document.getElementById('product-use_times').value = "";
		document.getElementById('product-use_people_times').setAttribute("readonly",true);
		document.getElementById('product-use_times').setAttribute("readonly",true);
	}
	
}
</script>
<?php
//這裡只把價格刪除，其他沒更改，沒看懂 
 if(isset($_POST['add_product'])){
   $req_fields = array('product-code', 'product-ref', 'product-name','product-format','product-buy_price','product-special','product-use_people_times','product-use_times','product-employee_id');
   validate_fields($req_fields);
   if(empty($errors)){
     $p_code  = remove_junk($db->escape($_POST['product-code']));
	 $p_ref  = remove_junk($db->escape($_POST['product-ref']));
	 $p_name = remove_junk($db->escape($_POST['product-name']));
	 $p_format = remove_junk($db->escape($_POST['product-format']));
	 $p_buy_price = remove_junk($db->escape($_POST['product-buy_price']));
	 $p_special = remove_junk($db->escape($_POST['product-special']));
	 $p_use_people_times = remove_junk($db->escape($_POST['product-use_people_times']));
	 $p_use_times = remove_junk($db->escape($_POST['product-use_times']));
	 $p_employee_id = remove_junk($db->escape($_POST['product-employee_id']));
	 
     if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
       $media_id = '0';
     } else {
       $media_id = remove_junk($db->escape($_POST['product-photo']));
     }
     $date    = make_date();
     $query  = "INSERT INTO material_basic (";
     $query .=" material_code,ref_code,material_name,material_format,media_id,buy_price,special,use_people_times,use_times,employee_id,date";
     $query .=") VALUES (";
     $query .="'{$p_code}', '{$p_ref}','{$p_name}', '{$p_format}', '{$media_id}','{$p_buy_price}',  '{$p_special}', '{$p_use_people_times}', '{$p_use_times}', '{$p_employee_id}', '{$date}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE material_name='{$p_name}'";
     if($db->query($query)){
       $session->msg('s',"Product added ");
       redirect('add_product.php', false);
     } else {
       $session->msg('d',' Sorry failed to added!');
       redirect('product.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_product.php',false);
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
            <span>新增醫材</span>
         </strong>
        </div>
<!-- 整個表格開始 -->
        <div class="panel-body">
		<font color="#FF0000">* 表示欄位必填</font>
         <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
<!-- 以下為同一列 -->
             <div class="form-group">
			   <div class="row">
                <div class="col-md-4">
				 <font color="#FF0000">*</font> <label for="product-code">醫材碼</label>
                 <div class="input-group">
                   <span class="input-group-addon">
				     <i>
						<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>
					 </i>
                   </span>
                  <input type="text" class="form-control" name="product-code" placeholder="醫材碼">
				 </div>
				</div>
				<div class="col-md-4">
				 <font color="#FF0000">*</font>  <label for="product-ref">REF碼</label>
                 <div class="input-group">
                   <span class="input-group-addon">
				     <i>
						<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>
					 </i>
                   </span>
                  <input type="text" class="form-control" name="product-ref" placeholder="REF">
				 </div>
				</div>

			   <div class="col-md-4">
			    <font color="#FF0000">*</font>  <label for="product-name">醫材名稱</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-name" placeholder="醫材名稱">
				 </div>
				</div>
				</div>
				</div>
<!-- 以下為同一列 -->
			<div class="form-group">
			   <div class="row">
				<div class="col-md-4">
				 <font color="#FF0000">*</font>  <label for="product-format">規格</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-list-alt"></i>
                  </span>
                  <input type="text" class="form-control" name="product-format" placeholder="規格">
				 </div>
				</div>

				<div class="col-md-4">
				 <font color="#FF0000">*</font>  <label for="product-buy_price">成本</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-usd"></i>
                  </span>
                  <input type="number" class="form-control" name="product-buy_price" placeholder="成本">
				 </div>
				</div>
				<div class="col-md-4">
				 <label for="product-photo">選擇醫材照片</label>
				<div class="input-group">
					<span class="input-group-addon">
                      <i class="glyphicon glyphicon-picture" aria-hidden="true"></i>
                     </span>
                    <select class="form-control" name="product-photo">
					 <option value="">選擇醫材照片</option>
                     <?php  foreach ($all_photo as $photo): ?>
                      <option value="<?php echo (int)$photo['id'] ?>">
                        <?php echo $photo['file_name'] ?></option>
                     <?php endforeach; ?>
                    </select>
                </div>
				</div>
			  </div>
			</div>
<!-- 以下為同一列 -->
			<div class="form-group">
			   <div class="row">
				<div class="col-md-4">   
				 <font color="#FF0000">*</font>  <label for="product-special">醫材特性</label>
                  <div class="input-group">
					<span class="input-group-addon">
                      <i class="glyphicon glyphicon-tasks" aria-hidden="true"></i>
                    </span>				 
					 <select class="form-control" name="product-special" id="product-special" onchange="funname()">
					     <option value="#">醫材特性</option>
						 <option value="0">一般醫材</option>
						 <option value="1">重消醫材</option>						 
					 </select>
                  </div>
				</div>

				<div class="col-md-4">
				  <label for="product-use_people_times">使用人次上限</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-user"></i>
                  </span>
                  <input type="number" class="form-control" name="product-use_people_times" id="product-use_people_times" placeholder="使用人次上限" readonly>
				  
				 </div>
				</div>
				<div class="col-md-4">
				 <label for="product-use_times">使用次數上限</label>
				  <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-repeat"></i>
                  </span>
                  <input type="number" class="form-control" name="product-use_times" id="product-use_times" placeholder="使用次數上限" readonly>
				 </div>
				</div>
			  </div>
			</div>
<!-- 以下為同一列 -->
			<div class="form-group">
			   <div class="row">
				<div class="col-md-4">
				 <label for="in-username">建檔人員</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-pencil"></i>
                  </span>
                  <input type="text" class="form-control" name="product-employee_id" value="<?php echo $now_user['username'];?>" readonly>
				 </div>
				</div>
				
				  
				
			  </div>
			</div>
<!-- 以下為同一列 -->
              <button type="submit" name="add_product" class="btn btn-info">新增醫材</button>
          </form>
        
        
      </div>
    </div>
  </div>
	
<?php include_once('layouts/footer.php'); ?>
