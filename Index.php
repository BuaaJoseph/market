<?php
/**
 * 表单提交入口
 * User: 长春
 * Date: 2016/7/19
 * Time: 21:17
 */
require_once "Goods.php";
$goods = new Goods();
$goods->getGoods();