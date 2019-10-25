<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>

<?php
 // Auto suggetion
    $html = '';
   if(isset($_POST['product_barcode']) && strlen($_POST['product_barcode']))
   {
     $barcodes = find_barcode_by_title($_POST['product_barcode']);
     if($barcodes){
        foreach ($barcodes as $barcode):
           $html .= "<li class=\"list-group-item\" style=\"width: 455px;\">";
           $html .= "{$barcodes['barcode']}}";
           $html .= "</li>";
         endforeach;
      } else {

        $html .= '<li onClick=\"fill(\''.addslashes().'\')\" class=\"list-group-item\">';
        $html .= 'Not found';
        $html .= "</li>";

      }

      echo json_encode($html);
   }
 ?>