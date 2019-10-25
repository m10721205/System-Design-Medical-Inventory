<?php
  $page_title = '編輯醫材';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
$product = find_by_id('material_basic',(int)$_GET['id']);
$all_categories = find_all('department');
$all_photo = find_all('media');
$now_user = current_user();

if(!$product){
  $session->msg("d","Missing product id.");
  redirect('product.php');
}
?>
<?php
 if(isset($_POST['update_data'])){
 $req_fields = array('product-code', 'product-ref', 'product-name','product-format', 'product-buy_price', 'product-special','product-use_people_times','product-use_times','product-employee_id2');
 validate_fields($req_fields);

   if(empty($errors)){
       $p_code  = remove_junk($db->escape($_POST['product-code']));
	   $p_ref  = remove_junk($db->escape($_POST['product-ref']));
	   $p_name  = remove_junk($db->escape($_POST['product-name']));
	   $p_format = remove_junk($db->escape($_POST['product-format']));
	   $p_special = remove_junk($db->escape($_POST['product-special']));
	   $p_use_people_times = remove_junk($db->escape($_POST['product-use_people_times']));
	   $p_use_times = remove_junk($db->escape($_POST['product-use_times']));
	   $p_employee_id2 = remove_junk($db->escape($_POST['product-employee_id2']));
	   //$p_cat   = (int)$_POST['product-categorie'];
       //$p_qty   = remove_junk($db->escape($_POST['product-quantity']));
       $p_buy   = remove_junk($db->escape($_POST['product-buy_price']));
       //$p_sale  = remove_junk($db->escape($_POST['saleing-price']));
       if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
         $media_id = '0';
       } else {
         $media_id = remove_junk($db->escape($_POST['product-photo']));
       }
	   $date2    = make_date();
       $query   = "UPDATE material_basic SET";
       $query  .=" material_code='{$p_code}', material_name ='{$p_name}', material_format='{$p_format}', ref_code='{$p_ref}', buy_price='{$p_buy}',  special='{$p_special}', use_people_times='{$p_use_people_times}', use_times='{$p_use_times}',  employee_id2='{$p_employee_id2}', date2='{$date2}',";
       $query  .=" media_id='{$media_id}'";
       $query  .=" WHERE id ='{$product['id']}'";
       $result = $db->query($query);
               if($result && $db->affected_rows() === 1){
                 $session->msg('s',"Product updated ");
                 redirect('product.php', false);
               } else {
                 $session->msg('d',' Sorry failed to updated!');
                 redirect('edit_product.php?id='.$product['id'], false);
               }

   } else{
       $session->msg("d", $errors);
       redirect('edit_product.php?id='.$product['id'], false);
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
        <div class="panel-body">
         <div class="col-md-7">
           <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>">
		  	
<!-- 以下為同一列 -->
             <div class="form-group">
			   <div class="row">
                <div class="col-md-6">
				 <th> 醫材碼 </th>
                 <div class="input-group">
                   <span class="input-group-addon">
				     <i>
						<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>
					 </i>
                   </span>
                  <input type="text" class="form-control" name="product-code" placeholder="醫材碼" value="<?php echo remove_junk($product['material_code']);?>">
				 </div>
				</div>
				
				<div class="col-md-6">
				 <th> REF </th>
                 <div class="input-group">
                   <span class="input-group-addon">
				     <i>
						<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>
					 </i>
                   </span>
                  <input type="text" class="form-control" name="product-ref" placeholder="REF" value="<?php echo remove_junk($product['ref_code']);?>">
				 </div>
				</div>
			   </div>
			</div>
<!-- 以下為同一列 -->
             <div class="form-group">
			   <div class="row">
			    <div class="col-md-6">
				 <th> 醫材名稱 </th>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-name" placeholder="醫材名稱" value="<?php echo remove_junk($product['material_name']);?>">
				 </div>
				</div>
				
                <div class="col-md-6">
				 <th> 規格 </th>
                 <div class="input-group">
                   <span class="input-group-addon">
				     <i>
						<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
					 </i>
                   </span>
                  <input type="text" class="form-control" name="product-format" placeholder="規格" value="<?php echo remove_junk($product['material_format']);?>">
				 </div>
				</div>
				</div>
				</div>
<!-- 以下為同一列 -->
             <div class="form-group">
			   <div class="row">
                <div class="col-md-6">
				<th> 成本 </th>
                 <div class="input-group">
                   <span class="input-group-addon">
				     <i>
						<span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
					 </i>
                   </span>
                  <input type="text" class="form-control" name="product-buy_price" placeholder="成本" value="<?php echo remove_junk($product['buy_price']);?>">
				 </div>
				</div>

				<div class="col-md-6">
				<th> 特殊醫材 </th>
				<!--<select class="form-control" name="product-special" placeholder="特殊醫材" value="<?php echo remove_junk($product['special']);?>">
					<option value="1">特殊</option>
					<option value="0">一般</option>
				</select>-->
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-tasks"></i>
                  </span>
                  <input type="text" class="form-control" name="product-special" placeholder="特殊醫材" value="<?php echo remove_junk($product['special']);?>">
				 </div>
				</div>
			  </div>
			 </div>
<!-- 以下為同一列 -->
             <div class="form-group">
			   <div class="row">
                <div class="col-md-6">
				<th> 使用人次上限 </th>
                 <div class="input-group">
                   <span class="input-group-addon">
				     <i>
						<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
					 </i>
                   </span>
                  <input type="text" class="form-control" name="product-use_people_times" placeholder="使用人次上限" value="<?php echo remove_junk($product['use_people_times']);?>">
				 </div>
				</div>
				<div class="col-md-6">
				<th> 使用次數上限 </th>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-repeat"></i>
                  </span>
                  <input type="text" class="form-control" name="product-use_times" placeholder="使用次數上限" value="<?php echo remove_junk($product['use_times']);?>">
				 </div>
				</div>
				</div>
				</div>
<!-- 以下為同一列 -->
             <div class="form-group">
			   <div class="row">
				<div class="col-md-6">
				<th> 異動人 </th>
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-pencil"></i>
                  </span>
                  <input type="text" class="form-control" name="product-employee_id2"  placeholder="異動人" value="<?php echo $now_user['username'];?>" readonly>
				 </div>
				</div>
                  <div class="col-md-6">
				  <th> 照片 </th>
				  <div class="input-group">
				  	<span class="input-group-addon">
				     <i>
						<span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
					 </i>
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
<!-- 以下為同一列 -->				  
		
				 <button type="submit" name="update_data" class="btn btn-danger">Update</button>
				
              
			  
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
              			  
          </form>
         
        </div>
      </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
