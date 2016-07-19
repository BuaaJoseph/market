<?php
/**
 * Created by PhpStorm.
 * User: 长春
 * Date: 2016/7/20
 * Time: 1:34
 */

class GoodsConfig{

    //添加商品
    public function addGood($postData){
        $data = file_get_contents("../data/market.dat");
        $marketInfo = json_decode($data,true);

        $marketInfo['ALL_GOODS'][$postData['barcode']] = $postData;
        $saveData = json_encode($marketInfo);
        file_put_contents("../data/market.dat", utf8_encode($saveData));

        require_once "HandleInput.php";
        $runner = new HandleInput("");
        $runner->printGoods();

    }
}