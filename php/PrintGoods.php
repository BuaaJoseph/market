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