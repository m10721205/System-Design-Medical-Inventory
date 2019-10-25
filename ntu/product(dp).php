<?php
  $page_title = '所有庫房醫材';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $now_user = current_user();
  $products = join_product_table_dp($now_user['department_id']);
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
				<span>所有庫房醫材</span>
			</strong>
         <div class="pull-right">
           <a href="add_product(dp).php" class="btn btn-primary">新增醫材</a>
         </div>
		 </P>
		 <label for="product-dept_id">部門:</label>
			<div class="input-group">	
				<input class="form-control" style="width: 50px;" value="<?php echo $now_user['department_id'];?>" name="in-department-id" readonly >
			</div>
        </div>
        <div class="panel-body" style="width:1000px;overflow-x:scroll">
          <table class="table table-bordered table-striped" style="width:1200px;">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
				<!--<th class="text-center" style="width: 10%;"> 醫材碼 </th>
                <th> 照片</th>-->
                <th> 部門代碼 </th>
				<th> 醫材碼 </th>
				<th> REF </th>
				<th> 醫材名稱 </th>
				<th> 入庫單位 </th>
				<th> 轉換數量 </th>
				<th> 出庫單位 </th>
				<th> 基本量 </th>
				<th> 最高存量 </th>
				<th> 存放位置 </th>
				<th> 新增日期 </th>
				<th> 建檔人 </th>
                <!--<th class="text-center" style="width: 50px;"> 部門 </th>
                <th class="text-center" style="width: 5%;"> 安全存量 </th>
                <!--
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
                <!--<td>
				<?php if($product['media_id'] === '0'): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="">
                  <?php else: ?>
                    <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                  <?php endif; ?>
                </td>-->
				<td> <?php echo remove_junk($product['department_id']); ?></td>
                <td> <?php echo remove_junk($product['material_code']); ?></td>
				<td> <?php echo remove_junk($product['ref_code']); ?></td>
				<td> <?php echo remove_junk($product['material_dpname']); ?></td>
				<td> <?php echo remove_junk($product['in_unit']); ?></td>
				<td> <?php echo remove_junk($product['unit_quantity']); ?></td>
				<td> <?php echo remove_junk($product['out_unit']); ?></td>
				<td> <?php echo remove_junk($product['safety_stock']); ?></td>
				<td> <?php echo remove_junk($product['highest_stock']); ?></td>
				<td> <?php echo remove_junk($product['stock_location']); ?></td>
				<td> <?php echo remove_junk($product['date']); ?></td>
				<td> <?php echo remove_junk($product['name']); ?></td>
				<!--
				<td> <?php// echo remove_junk($product['unit']); ?></td>
				<td> <?php// echo remove_junk($product['employee_ID']); ?></td>
				<td> <?php// echo remove_junk($product['date']); ?></td>
                <td class="text-center"> <?php// echo remove_junk($product['department']); ?></td>
                <td class="text-center"> <?php// echo remove_junk($product['safety_stock']); ?></td>
				
                <!--<td class="text-center"> <//?php echo remove_junk($product['buy_price']); ?></td>
                <td class="text-center"> <//?php echo remove_junk($product['sale_price']); ?></td>
                <td class="text-center"> <//?php echo read_date($product['date']); ?></td>-->
                
				<td class="text-center">
                  <div class="btn-group">
                    <a href="edit_product(dp).php?id=<?php echo (int)$product['id'];?>" onclick="return confirm('是否確定要執行這個動作？');" class="btn btn-info btn-xs"  title="編輯" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_product(dp).php?id=<?php echo (int)$product['id'];?>" onclick="return confirm('是否確定要執行這個動作？');" class="btn btn-danger btn-xs"  title="刪除" data-toggle="tooltip">
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
