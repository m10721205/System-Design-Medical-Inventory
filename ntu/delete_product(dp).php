<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $product = find_by_id('material_deptbasic',(int)$_GET['id']);
  if(!$product){
    $session->msg("d","Missing Product id.");
    redirect('product(dp).php');
  }
?>
<?php
  $delete_id = delete_by_id('material_deptbasic',(int)$product['id']);
  if($delete_id){
      $session->msg("s","Products deleted.");
      redirect('product(dp).php');
  } else {
      $session->msg("d","Products deletion failed.");
      redirect('product(dp).php');
  }
?>
