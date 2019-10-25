<?php
  $page_title = 'Find';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php

  if(isset($_POST['add_sale'])){
    $req_fields = array('s_id','quantity','price','total', 'date' );
    validate_fields($req_fields);
        if(empty($errors)){
          $p_id      = $db->escape((int)$_POST['s_id']);
          $s_qty     = $db->escape((int)$_POST['quantity']);
          $s_total   = $db->escape($_POST['total']);
          $date      = $db->escape($_POST['date']);
          $s_date    = make_date();

          $sql  = "INSERT INTO sales (";
          $sql .= " product_id,qty,price,date";
          $sql .= ") VALUES (";
          $sql .= "'{$p_id}','{$s_qty}','{$s_total}','{$s_date}'";
          $sql .= ")";

                if($db->query($sql)){
                  update_product_qty($s_qty,$p_id);
                  $session->msg('s',"Sale added. ");
                  redirect('add_sale.php', false);
                } else {
                  $session->msg('d',' Sorry failed to add!');
                  redirect('add_sale.php', false);
                }
        } else {
           $session->msg("d", $errors);
           redirect('add_sale.php',false);
        }
  }

?>
<?php include_once('layouts/header.php'); ?>
<!--
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">Find It</button>
            </span>
            <input type="text" id="sug_input" class="form-control" name="title"  placeholder="Search for product name">
         </div>
         <div id="result" class="list-group"></div>
        </div>
    </form>
  </div>
</div>
-->
<!-- 以下為同一列 -->
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
            <span>條件篩選</span>
         </strong>
        </div>
<!-- 整個表格開始 -->
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
<!-- 以下為同一列 -->
             <div class="form-group">
			   <div class="row">
                <div class="col-md-4">
                 <div class="input-group">
                   <span class="input-group-addon">
				     <i>
						<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>
					 </i>
                   </span>
                  <input type="text" class="form-control" name="product-title" placeholder="醫材碼">
				 </div>
				</div>
				<div class="col-md-4">
                 <div class="input-group">
                   <span class="input-group-addon">
				     <i>
						<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>
					 </i>
                   </span>
                  <input type="text" class="form-control" name="product-title" placeholder="REF碼">
				 </div>
				</div>
				<div class="col-md-4">
                 <div class="input-group">
                   <span class="input-group-addon">
				     <i>
						<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>
					 </i>
                   </span>
                  <input type="text" class="form-control" name="product-title" placeholder="病人編號">
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
                     <input type="number" class="form-control" name="product-quantity" placeholder="有效日期">
                  </div>
                 </div>
				  <div class="col-md-3">    <!-- 欄位大小 -->
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>   <!-- 插入入庫數量圖案 -->
                     </span>
                     <input type="number" class="form-control" name="product-quantity" placeholder="入庫日期">
                  </div>
                </div>
				  <div class="col-md-3">    <!-- 欄位大小 -->
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>   <!-- 插入入庫數量圖案 -->
                     </span>
                     <input type="number" class="form-control" name="product-quantity" placeholder="出庫日期">
                  </div>
                </div>
				<div class="col-md-3">
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" placeholder="出庫人員">
				 </div>
				</div>
			  </div>
			  </div>
			  <button type="submit" name="add_product" class="btn btn-danger">確定</button>
			</div>
		   </div>
		  </div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>查詢結果</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_sale.php">
         <table class="table table-bordered">
           <thead>
            <th> 品項 </th>
            <!--<th> Price </th>-->
            <th> 數量 </th>
            <th> Total </th>
            <th> 日期</th>
            <th> 操作</th>
           </thead>
             <tbody  id="product_info"> </tbody>
         </table>
       </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
