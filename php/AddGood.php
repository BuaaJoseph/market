<?php
/**
 * Created by PhpStorm.
 * User: 长春
 * Date: 2016/7/20
 * Time: 1:31
 */
require_once "GoodsConfig.php";
$runner = new GoodsConfig();
$runner->addGood($_POST);