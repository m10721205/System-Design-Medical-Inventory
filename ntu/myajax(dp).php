<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}

switch($_GET['type']){
	case "changeName":
		$id=$_GET['id'];
		$department_deptbasic=input_find_by_code($id);
		foreach ($department_deptbasic as $dept){
             echo "<option value=\"".$dept['material_name']."\">".$dept['material_name']."</option>";
        };
	break;
	
	case "changeRef":
		$id=$_GET['id'];
		$ref_deptbasic=input_find_by_code($id);
		foreach ($ref_deptbasic as $dpref)
		{
             echo "<option value=\"".$dpref['ref_code']."\">".$dpref['ref_code']."</option>";
        };
	break;
	
	case "changeBarcodeName":
		$id=$_GET['id'];
		$barcodeName=output_find_by_barcode($id);
		foreach ($barcodeName as $b_n){
             echo "<option value=\"".$b_n['material_dpname']."\">".$b_n['material_dpname']."</option>";
        };
	break;
	
	case "changeBarcodeRef":
		$id=$_GET['id'];
		$barcodeRef=output_find_by_barcode($id);
		foreach ($barcodeRef as $b_ref){
             echo "<option value=\"".$b_ref['ref_code']."\">".$b_ref['ref_code']."</option>";
        };
	break;
	
	case "changeBarcodeLotnum":
		$id=$_GET['id'];
		$barcodeLot=output_find_by_barcode($id);
		foreach ($barcodeLot as $b_lot){
             echo "<option value=\"".$b_lot['lot_num']."\">".$b_lot['lot_num']."</option>";
        };
	break;
	
	case "link2output":
		$outdepartmentbarcode=$_GET['outdepartmentbarcode'];
		$backdate=$_GET['backdate'];
		$outusername=$_GET['outusername'];
		$qt2=$_GET['qt2'];
		
		$rs4  = "INSERT INTO output_stock_recode (";
		$rs4  .="barcode, out_quantity, category, time, employee_id, patient_history";
		$rs4  .=") VALUES (";
		$rs4  .="'{$outdepartmentbarcode}', '1', '0', '{$backdate}', '{$outusername}', '{$qt2}'";
		$rs4  .=");";
		if($db->query($rs4)){
			echo true;
		};
	break;
	
		case "link2output2":
		$outdepartmentbarcode=$_GET['outdepartmentbarcode'];
		$outdate=$_GET['outdate'];
		$outusername=$_GET['outusername'];
		$patientid=$_GET['patientid'];
		
		$rs11  = "INSERT INTO output_stock_recode (";
		$rs11 .="barcode, out_quantity, category, time, employee_id, patient_history";
		$rs11  .=") VALUES (";
		$rs11  .="'{$outdepartmentbarcode}', '1', '0', '{$outdate}', '{$outusername}', '{$patientid}'";
		$rs11  .=");";
		if($db->query($rs11)){
			echo true;
		};
	break;
	
		case "link2output3":
		$outdepartmentbarcode=$_GET['outdepartmentbarcode'];
		$outdate=$_GET['outdate'];
		$outusername=$_GET['outusername'];
		$patientid=$_GET['patientid'];
		
		$rs9  = "INSERT INTO output_stock_recode (";
		$rs9 .="barcode, out_quantity, category, time, employee_id, patient_history";
		$rs9  .=") VALUES (";
		$rs9  .="'{$outdepartmentbarcode}', '1', '0', '{$outdate}', '{$outusername}', '{$patientid}'";
		$rs9  .=");";
		if($db->query($rs9)){
			echo true;
		};
	break;
}
 ?>