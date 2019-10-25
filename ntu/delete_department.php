<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $delete_id = delete_by_id('department',(int)$_GET['id']);
  if($delete_id){
      $session->msg("s","Department deleted！");
      redirect('department.php');
  } else {
      $session->msg("d","Department deletion failed！");
      redirect('department.php');
  }
?>
