<?php
  $page_title = '所有醫材';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
  $products = join_product_table();
?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
           <strong>
			<span class="glyphicon glyphicon-th"></span>
			<span>所有醫材</span>
		   </strong>
		 <div class="pull-right">
           <a href="add_product.php" class="btn btn-primary">新增醫材</a>
         </div>
        </div>
        <div class="panel-body" style="width:1000px;overflow-x:scroll">
          <table class="table table-bordered table-striped" style="width:1200px;">
            <thead>
              <tr>
                <th class="text-center" style="width: 30px;">#</th>
				<!--<th class="text-center" style="width: 10%;"> 醫材碼 </th>-->
                <!--<th> 照片</th>-->
                <th> 醫材碼 </th>
				<th> REF碼 </th>
				<th> 醫材名稱 </th>
				<th> 規格 </th>
				<th> 成本 </th>
				<th> 重消醫材 </th>
				<th> 使用人次上限 </th>
				<th> 使用次數上限 </th>
				<th> 新增人 </th>
				<th> 新增日期 </th>
				<!--
                <th class="text-center" style="width: 50px;"> 部門 </th>
                <th class="text-center" style="width: 5%;"> 安全存量 </th>
				<th class="text-center" style="width: 10%;"> Buying Price </th>
                <th class="text-center" style="width: 10%;"> Saleing Price </th>
                <th class="text-center" style="width: 10%;"> 新增時間 </th>-->
                <th class="text-center" style="width: 65px;"> 操作 </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
				<!--
                <td>
                  <?php if($product['media_id'] === '0'): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="">
                  <?php else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                <?php endif; ?>
                </td>-->
                <td> <?php echo remove_junk($product['material_code']); ?></td>
				<td> <?php echo remove_junk($product['ref_code']); ?></td>
				<td> <?php echo remove_junk($product['material_name']); ?></td>
				<td> <?php echo remove_junk($product['material_format']); ?></td>
				<td> <?php echo remove_junk($product['buy_price']); ?></td>
				<td> <?php echo remove_junk($product['special']); ?></td>
				<td> <?php echo remove_junk($product['use_people_times']); ?></td>
				<td> <?php echo remove_junk($product['use_times']); ?></td>
				<td> <?php echo remove_junk($product['name']); ?></td>
				<td> <?php echo remove_junk($product['date']); ?></td>
				
				<!--
                <td class="text-center"> <//?php echo remove_junk($product['department']); ?></td>
                <td class="text-center"> <//?php echo remove_junk($product['quantity']); ?></td>
				<td class="text-center"> <//?php echo remove_junk($product['buy_price']); ?></td>
                <td class="text-center"> <//?php echo remove_junk($product['sale_price']); ?></td>
                <td class="text-center"> <//?php echo read_date($product['date']); ?></td>-->
                
				<td class="text-center">
                  <div class="btn-group">
                    <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" onclick="return confirm('是否確定要執行這個動作？');" class="btn btn-info btn-xs"  title="編輯" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" onclick="return confirm('是否確定要執行這個動作？');" class="btn btn-danger btn-xs"  title="刪除" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
