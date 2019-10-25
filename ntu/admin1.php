<?php
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>


<?php
 $c_categorie     = count_by_id('categories');
 $c_product       = count_by_id('products');
 $c_sale          = count_by_id('sales');
 $c_user          = count_by_id('users');
 $products_sold   = find_higest_saleing_product('10');
 $recent_products = find_recent_product_added('5');
 $recent_sales    = find_recent_sale_added('5')
?>
<?php include_once('layouts/header.php'); ?>

<!--<div class="row">
   <div class="col-md-6>
     <?php echo display_msg($msg); ?>
   </div>
</div>-->

<!--<div class="row">
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-green">
          <i class="glyphicon glyphicon-user"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php//  echo $c_user['total']; ?> </h2>
          <p class="text-muted">使用者</p>
        </div>
       </div>
    </div>
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-red">
          <i class="glyphicon glyphicon-list"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php//  echo $c_categorie['total']; ?> </h2>
          <p class="text-muted">部門分類</p>
        </div>
       </div>
    </div>
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-blue">
          <i class="glyphicon glyphicon-shopping-cart"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php // echo $c_product['total']; ?> </h2>
          <p class="text-muted">醫材</p>
        </div>
       </div>
    </div>
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-yellow">
          <i class="glyphicon glyphicon-usd"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php//  echo $c_sale['total']; ?></h2>
          <p class="text-muted">Sales</p>
        </div>
       </div>
    </div>
</div>-->
 
<div class="row">
   <div class="col-md-12">
      <div class="panel">
        <div class="jumbotron text-center">
           <h1>歡迎使用醫材庫存追蹤系統</h1>
            <!--<p> <strong>OSWA-INV v2</strong> way more better then <strong> v1 </strong>.
           </br>If you have a question regarding the usage of this applications, please ask on <a href="https://www.facebook.com/oswapp" title="Facebook" target="_blank">Facebook</a> OSWA Fan page.</p>
			-->
        </div>
      </div>
   </div>
</div>
  <div class="row">
   <!--<div class="col-md-4">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>最近使用</span>
         </strong>
       </div>
       <div class="panel-body">
         <table class="table table-striped table-bordered table-condensed">
          <<thead>
           <tr>
             <th>品名</th>
             <th>使用量</th>
             <th>庫存</th>這邊還沒改，系統原始計算是總使用量
           <tr>
          </thead>
          <tbody>
            <?php// foreach ($products_sold as  $product_sold): ?>
              <tr>
                <td><?php// echo remove_junk(first_character($product_sold['name'])); ?></td>
                <td><?php// echo (int)$product_sold['totalSold']; ?></td>
                <td><?php// echo (int)$product_sold['totalQty']; ?></td>
              </tr>
            <?php// endforeach; ?>
          <tbody>
         </table>
       </div>
     </div>
   </div>-->
   
   
   
  <!-- <div class="col-md-7">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>最近使用</span>
          </strong>
        </div>
        <div class="panel-body">
          <table class="table table-striped table-bordered table-condensed">
       <thead>
         <tr>
           <th class="text-center" style="width: 50px;">#</th>
		   <th>醫材碼</th>
           <th>醫材名稱</th>
           <th>使用使間</th><!--原始只有日期沒有時間
           <th>總使用量</th>
         </tr>
       </thead>
       <tbody>
         <?php// foreach ($recent_sales as  $recent_sale): ?>
         <tr>
           <td class="text-center"><?php //echo count_id();?></td>
		   <td class="text-center"></td>
           <td>
            <a href="edit_sale.php?id=<?php// echo (int)$recent_sale['id']; ?>">
             <?php //echo remove_junk(first_character($recent_sale['name'])); ?>
           </a>
           </td>
           <td class="text-center"><?php //echo read_date(ucfirst($recent_sale['date'])); ?></td>

           <td>$<?php //echo remove_junk(first_character($recent_sale['price'])); ?></td>

        </tr>

       <?php// endforeach; ?>
       </tbody>
     </table>
    </div>
   </div>
  </div>-->
  <!--<div class="col-md-5">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Recently Added Products</span>
        </strong>
      </div>
      <div class="panel-body">-->

        <!--<div class="list-group">
      <?php// foreach ($recent_products as  $recent_product): ?>
            <a class="list-group-item clearfix" href="edit_product.php?id=<?php// echo    (int)$recent_product['id'];?>">
                <h4 class="list-group-item-heading">
                 <?php //if($recent_product['media_id'] === '0'): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="">
                  <?php //else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php// echo $recent_product['image'];?>" alt="" />
                <?php// endif;?>
                <?php// echo remove_junk(first_character($recent_product['name']));?>
                  <span class="label label-warning pull-right">
                 $<?php //echo (int)$recent_product['sale_price']; ?>
                  </span>
                </h4>
                <span class="list-group-item-text pull-right">
                <?php //echo remove_junk(first_character($recent_product['categorie'])); ?>
              </span>
          </a>
      <?php //endforeach; ?>
    </div>-->
  </div>
 </div>
</div>
 </div>
  <div class="row">

  </div>



<?php include_once('layouts/footer.php'); ?>
