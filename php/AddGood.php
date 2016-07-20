<?php
/**
 * Created by PhpStorm.
 * User: 长春
 * Date: 2016/7/20
 * Time: 1:31
 */
header("content-Type: text/html; charset=Utf-8");
require_once "GoodsConfig.php";
$type = $_POST['type'];
echo $type;
$runner = new GoodsConfig();
switch ($type){
    case 0:     //添加商品
        $runner->addGood($_POST);
        break;
    case 1:     //添加满二赠一优惠
        $runner->addThreeToTwo($_POST['barcode']);
        break;
    case 2:     //添加九五折优惠
        $runner->addNintyFive($_POST['barcode']);
        break;
    default:
        echo "输入错误！";
        break;
}

