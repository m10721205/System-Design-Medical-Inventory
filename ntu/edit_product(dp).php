<?php
  $page_title = '編輯醫材';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
  $product = find_by_id('material_deptbasic',(int)$_GET['id']);
  $all_photo = find_all('media');
  $all_baisc = find_all('material_basic');
  $now_user = current_user();
  $department_deptbasic = input_find_by_department($now_user['department_id']);
  $code_depname = input_find_by_name(remove_junk($db->escape($_POST['product-code'])));

  if(!$product){
  $session->msg("d","Missing product id.");
  redirect('product(dp).php');
}
?>
<?php
 if(isset($_POST['update_data'])){
	$req_fields = array('product-in_unit','product-uni_quan','product-out_uni','product-save_stock','product-highest_stock','product-location','product-employee_id2');
	validate_fields($req_fields);

    if(empty($errors)){

     $p_code  = remove_junk($db->escape($_POST['product-code']));
	 $p_ref = remove_junk($db->escape($_POST['product-ref']));
	 $p_name = remove_junk($db->escape($_POST['in-dep-dpname']));
	 $p_in_unit = remove_junk($db->escape($_POST['product-in_unit']));
	 $p_uni_quan = remove_junk($db->escape($_POST['product-uni_quan']));
	 $p_out_uni = remove_junk($db->escape($_POST['product-out_uni']));
	 $p_save_stock = remove_junk($db->escape($_POST['product-save_stock']));
	 $p_highest_stock = remove_junk($db->escape($_POST['product-highest_stock']));
	 $p_location = remove_junk($db->escape($_POST['product-location']));
	 $p_employee_id2 = remove_junk($db->escape($_POST['product-employee_id2']));
   if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
         $media_id = '0';
       } else {
         $media_id = remove_junk($db->escape($_POST['product-photo']));
       }
	   $date2    = make_date();
       $query   = "UPDATE material_deptbasic SET";
       $query  .=" material_code='{$p_code}',ref_code='{$p_ref}', material_dpname ='{$p_name}', in_unit='{$p_in_unit}', unit_quantity='{$p_uni_quan}', out_unit='{$p_out_uni}', safety_stock='{$p_save_stock}', highest_stock='{$p_highest_stock}', stock_location='{$p_location}', employee_id2='{$p_employee_id2}', date2='{$date2}',";
       $query  .=" media_id='{$media_id}'";
       $query  .=" WHERE id ='{$product['id']}'";
       $result = $db->query($query);
               if($result && $db->affected_rows() === 1){
                 $session->msg('s',"Product updated ");
                 redirect('product(dp).php', false);
               } else {
                 $session->msg('d',' Sorry failed to updated!');
                 redirect('edit_product(dp).php?id='.$product['id'], false);
               }

   } else{
       $session->msg("d", $errors);
       redirect('edit_product(dp).php?id='.$product['id'], false);
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
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>編輯醫材</span>
         </strong>
        </div>
<!-- 整個表格開始 -->
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="edit_product(dp).php?id=<?php echo (int)$product['id'] ?>">
<!-- 以下為同一列 -->            
			<div class="form-group">
			  <div class="row">
				<div class="col-md-1">
				 <label for="product-dept_id">部門</label>
					<input class="form-control" value="<?php echo $now_user['department_id'];?>" name="in-department-id" readonly >
                  </div>
                </div>
              </div>				
<!-- 以下為同一列 -->
			<form method="post" action="edit_product(dp).php?id=<?php echo (int)$product['id'] ?>">             
			<div class="form-group">
			   <div class="row">
			   <div class="col-md-2">
				 <label for="product-code">醫材碼</label>
				 <div class="input-group">
				  <input class="form-control" name="product-code" value="<?php echo remove_junk($product['material_code']);?>" readonly>
				  </div>
				  </div>	
			     <div class="col-md-2">
				 <label for="product-ref">REF碼</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-list-alt"></i>
                  </span>
                  <input type="text" class="form-control" name="product-ref" value="<?php echo remove_junk($product['ref_code']);?>" readonly>
				 </div>
				</div>
			  </div>
			</div>
<!-- 以下為同一列 -->
				<div class="col-md-2">
				 <label for="in-dep-dpname">醫材名稱</label>
				 <div class="input-group">
                    <input type="text" class="form-control" name="in-dep-dpname" value="<?php echo remove_junk($product['material_dpname']);?>" readonly>

				 </div>
				</div>
				<div class="col-md-3">
				 <label for="product-save_stock">基本量</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-save_stock" placeholder="基本量" value="<?php echo remove_junk($product['safety_stock']);?>">
				 </div>
				</div>
			  </div>
			</div>
<!-- 以下為同一列 -->
          <form method="post" action="edit_product(dp).php?id=<?php echo (int)$product['id'] ?>">
			<div class="form-group">
			   <div class="row">
				<div class="col-md-2">
				 <label for="product-highest_stock">最高庫存量</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-highest_stock" placeholder="最高庫存量" value="<?php echo remove_junk($product['highest_stock']);?>">
				 </div>
				</div>
				<div class="col-md-2">
				 <label for="product-location">存放庫位</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-location" placeholder="存放庫位" value="<?php echo remove_junk($product['stock_location']);?>">
				 </div>
				</div>
				<div class="col-md-2">
				 <label for="product-employee_id">異動人</label>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-pencil"></i>
                  </span>
				   <input name="product-employee_id2" type="text" class="form-control" value = "<?php echo $now_user['username'];?>" readonly>
                  <!--input type="text" class="form-control" name="product-employee_id" placeholder="建檔人"-->
				 </div>
				</div>
			  </div>
			</div>
<!-- 以下為同一列 -->
          <form method="post" action="edit_product(dp).php?id=<?php echo (int)$product['id'] ?>">
            <div class="form-group">
			  <div class="row">
				<div class="col-md-2">
				  <label for="product-in_unit">入庫單位</label>
			        <div class="input-group">
					<span class="input-group-addon">
                      <i class="glyphicon glyphicon-log-in" aria-hidden="true"></i>
                     </span>
					 <select class="form-control" name="product-in_unit">
					     <option value="<?php echo remove_junk($product['in_unit']);?>">入庫單位</option>
						 <option value="個">個</option>
						 <option value="盒">盒</option>
						 <option value="包">包</option>						 
						 <option value="袋">袋</option>
						 <option value="箱">箱</option>
						 <option value="套">套</option>
					</select>
                </div>
				</div>
<!-- 以下為同一列 -->

				 <form method="post" action="edit_product(dp).php?id=<?php echo (int)$product['id'] ?>">
				 <div class="col-md-2">    <!-- 轉換單位 -->
				  <label for="product-uni_quan">轉換數量</label>
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>   <!-- 插入入庫數量圖案 -->
                     </span>
                     <input type="number" class="form-control" name="product-uni_quan" placeholder="轉換數量" value = "<?php echo $product['unit_quantity'];?>">
                  </div>
                 </div>
				 
				 <div class="col-md-2">  <!-- 這裡是出庫 -->
				  <label for="product-out_uni">出庫單位</label>
				   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-log-out" aria-hidden="true"></i>
                     </span>
				   <form method="post" action="edit_product(dp).php?id=<?php echo (int)$product['id'] ?>">
                   <select class="form-control" name="product-out_uni">
					     <option value="<?php echo remove_junk($product['out_unit']);?>">出庫單位</option>
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
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
				   <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                    <select class="form-control" name="product-photo">
                      <option value=""> No image</option>
                      <?php  foreach ($all_photo as $photo): ?>
                        <option value="<?php echo (int)$photo['id'];?>" <?php if($product['media_id'] === $photo['id']): echo "selected"; endif; ?> >
                          <?php echo $photo['file_name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
			</div>
           </div>
			<!---
              <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                  <div class="form-group">
                    <label for="qty">Quantity</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                       <i class="glyphicon glyphicon-shopping-cart"></i>
                      </span>
                      <input type="number" class="form-control" name="product-quantity" value="</?php echo remove_junk($product['quantity']); ?>">
                   </div>
                  </div>
                 </div>
				 <!--
                 <div class="col-md-4">
                  <div class="form-group">
                    <label for="qty">Buying price</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-usd"></i>
                      </span>
                      <input type="number" class="form-control" name="buying-price" value="</?php echo remove_junk($product['buy_price']);?>">
                      <span class="input-group-addon">.00</span>
                   </div>
                  </div>
                 </div>
                  <div class="col-md-4">
                   <div class="form-group">
                     <label for="qty">Selling price</label>
                     <div class="input-group">
                       <span class="input-group-addon">
                         <i class="glyphicon glyphicon-usd"></i>
                       </span>
                       <input type="number" class="form-control" name="saleing-price" value="</?php echo remove_junk($product['sale_price']);?>">
                       <span class="input-group-addon">.00</span>
                    </div>
                   </div>
                  </div>
               </div>
			   -->
              </div>			  
              <button type="submit" name="update_data" class="btn btn-danger">Update</button>
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
	}else $('#in-dep-dpname').html("<option value=\"\">醫材名稱</option>");
	}
</script>