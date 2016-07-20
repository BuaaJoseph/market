<?php
/**
 * Created by PhpStorm.
 * User: 长春
 * Date: 2016/7/20
 * Time: 0:44
 */
require_once "HandleInput.php";
$runner = new HandleInput($_POST['goods']);
$runner->printGoods();

echo "<br><br>";
?>
<html>
<form>
<input id="barcode" placeholder="请输入商品条形码"/>
<input id="addType" name="addType"  type="hidden" value="0"/>
<input type="button" value="添加满二赠一" onclick="mySubmit(1)" />
<input type="button" value="添加九五折优惠" onclick="mySubmit(2)" />
</form>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript">
	function mySubmit(addType){
		var barcode = $("#barcode").val();
		$.ajax({
			type:"post",
			data:{
				barcode:barcode,
				type:addType
			},
			url:"AddGood.php",
			success:function(){
				alert("添加成功");
				location.reload();
			}
		})
	}
</script>
</html>
