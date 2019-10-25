<?php
  $page_title = 'Home Page';
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <!--會出現歡迎原作系統字樣
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  -->
 <div class="col-md-12">
    <div class="panel">
      <div class="jumbotron text-center">
         <h1>歡迎使用醫材庫存追蹤系統！</h1>
         <p>點選左方功能選項</p>
      </div>
    </div>
 </div>
</div>
<?php include_once('layouts/footer.php'); ?>
