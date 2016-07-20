<?php
/**
 * 表单提交入口
 * User: 长春
 * Date: 2016/7/19
 * Time: 21:17
 */
header("content-Type: text/html; charset=Utf-8");
require_once "HandleInput.php";
$runner = new HandleInput($_POST['goods']);
$runner->calculate();
