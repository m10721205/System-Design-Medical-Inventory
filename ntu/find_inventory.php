<?php
  $page_title = '借貨查詢';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-4">
    <?php echo display_msg($msg); ?>
    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
        <div class="form-group">
          <div class="input-group">
		   <input type="text" style="width: 455px;" id="sug_input" class="form-control" name="title"  placeholder="輸入醫材碼" autofocus>
		   <span class="input-group-btn">
              <button type="submit" class="btn btn-primary" onclick="display()">搜尋</button>
           </span>
          </div>
         <div id="result" class="list-group"></div>
        </div>
    </form>
  </div>
</div>
<div class="row">

  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <P>
		  <span class="glyphicon glyphicon-th"></span>
          <span>查詢結果</span>
		  </P>
		  <P>
		  醫材碼 / 規格碼 : <span id="code"></span><br>
		  醫材名稱 : <span id="name"></span><br>
		  </P>
       </strong>
      </div>
      <div class="panel-body">
         <table class="table table-bordered">
           <thead>
		    <!--<th class="text-center" style="width: 25px;">#</th>-->
            <th> 部門 </th>
            <th> 線上數量 </th>
            <th> 聯絡分機 </th>
            <th> 管理人 </th>
           </thead>
             <tbody  id="product_info"> </tbody>
         </table>
      </div>
    </div>
  </div>

</div>
<script>
function display(){
	var sug_input=$('#sug_input').val();
	var arr=sug_input.split("_");
	$('#code').html(arr[0]+' / '+arr[1]);
	$('#name').html(arr[2]);
}
/*
function readBarCode(e){
	onkeypress="readBarCode(event)
	 if (e.keyCode == 13) {
        var sug_input=$('#sug_input').val();
    }
	
	console.log(sug_input);
}*/
</script>
<?php include_once('layouts/footer.php'); ?>
