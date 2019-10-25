<?php
  $page_title = '領取未用';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  // 用Add Product改的
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
?>
<?php
//這裡只把價格刪除，其他沒更改，沒看懂 
 if(isset($_POST['add_product'])){
   $req_fields = array('product-title','product-categorie','product-quantity');
   validate_fields($req_fields);
   if(empty($errors)){
     $p_name  = remove_junk($db->escape($_POST['product-title']));
     $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
     $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
     if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
       $media_id = '0';
     } else {
       $media_id = remove_junk($db->escape($_POST['product-photo']));
     }
     $date    = make_date();
     $query  = "INSERT INTO products (";
     $query .=" name,quantity,categorie_id,media_id,date";
     $query .=") VALUES (";
     $query .=" '{$p_name}', '{$p_qty}',  '{$p_cat}', '{$media_id}', '{$date}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE name='{$p_name}'";
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
            <span>重新入庫</span>
         </strong>
        </div>
<!-- 整個表格開始 -->
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
<!-- 以下為同一列 -->
             <div class="form-group">
			   <div class="row">
                <div class="col-md-6">
                 <div class="input-group">
                   <span class="input-group-addon">
				     <i>
						<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>
					 </i>
                   </span>
                  <input type="text" class="form-control" name="product-title" placeholder="醫材碼">
				 </div>
				</div>
				<div class="col-md-6">
                 <div class="input-group">
                   <span class="input-group-addon">
				     <i>
						<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>
					 </i>
                   </span>
                  <input type="text" class="form-control" name="product-title" placeholder="REF碼">
				 </div>
				</div>
			   </div>
              </div>
<!-- 以下為同一列 -->
              <div class="form-group">
                <div class="row">
				<div class="col-md-6">
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" placeholder="醫材名稱">
					</div>
					</div>
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                      <option value="">選擇科室</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
<!-- 以下為同一列 -->
              <div class="form-group">
               <div class="row">
                 <div class="col-md-3">    <!-- 欄位大小 -->
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>   <!-- 插入入庫數量圖案 -->
                     </span>
                     <input type="number" class="form-control" name="product-quantity" placeholder="入庫數量">
                  </div>
                 </div>
<!--
				 <div class="col-md-3">    這裡是入庫 
                   <div class="input-group">
					<span class="input-group-addon">
                      <i class="glyphicon glyphicon-log-in" aria-hidden="true"></i>
                     </span>
					 <select class="form-control" name="in-nit">
					     <option value="">入庫單位</option>
						 <option value="">盒</option>
						 <option value="">包</option>						 
						 <option value="">袋</option>
					</select>
                </div>

				   
				   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-log-in" aria-hidden="true"></i>
                     </span>
                     <input type="text" class="form-control" name="in-nit" placeholder="入庫單位">
                  </div> 
                 </div>
				 <div class="col-md-3">     轉換單位 
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>   <!-- 插入入庫數量圖案 
                     </span>
                     <input type="number" class="form-control" name="product-quantity" placeholder="轉換單位">
                  </div>
                 </div>
				 <div class="col-md-3">  <!-- 這裡是出庫 
				   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-log-out" aria-hidden="true""></i>
                     </span>
             
					 <select class="form-control" name="out-nit">
					     <option value="">出庫單位</option>
						 <option value="">瓶</option>
						 <option value="">包</option>						 
						 <option value="">袋</option>
						 <option value="">片</option>

					</select>
				   </div>
				 </div>
				 -->
			  </div>
			 </div>
<!-- 以下為同一列 -->
			
              <button type="submit" name="add_product" class="btn btn-danger">重新入庫</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>
  <!-- 以下為同一列 -->
  <!--  <div class="row">
  <div class="col-md-10">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>預覽入庫</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_input.php">
         <table class="table table-bordered">
           <thead>
            <th> 品項 </th>
            <th> Price </th>
            <th> 數量 </th>
            <th> Total </th>
            <th> 日期</th>
            <th> 操作</th>
           </thead>
             <tbody  id="product_info"> </tbody>
         </table>

              <button type="submit" name="add_product" class="btn btn-danger">確定入庫</button>
          </form>
         </div>
        </div>
      </div>
    </div>-->
  </div>
	
<?php include_once('layouts/footer.php'); ?>
