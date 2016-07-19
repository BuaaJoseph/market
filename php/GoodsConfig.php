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

        if (!is_array($postData) || empty($postData) || empty($postData['barcode']) || empty($postData['name']) ||
        empty($postData['unit']) || empty($postData['category']) || empty($postData['subCategory']) || empty($postData['price'])){
            echo "输入错误！";
            exit(1);
        }else{
            $marketInfo['ALL_GOODS'][$postData['barcode']] = $postData;
            $saveData = json_encode($marketInfo);
            file_put_contents("../data/market.dat", utf8_encode($saveData));

            require_once "HandleInput.php";
            $runner = new HandleInput("");
            $runner->printGoods();
        }

    }


    //添加满二赠一优惠
    public function addThreeToTwo($barcode){
        $data = file_get_contents("../data/market.dat");
        $marketInfo = json_decode($data,true);

        if (!array_key_exists($barcode, $marketInfo['ALL_GOODS'])){
            echo "错误！该商品不存在<br>";
        }else{
            $marketInfo['THREE_TO_TWO'][] = $barcode;
            $saveData = json_encode($marketInfo);
            file_put_contents("../data/market.dat", utf8_encode($saveData));

            require_once "HandleInput.php";
            $runner = new HandleInput("");
            $runner->printGoods();
        }
    }

    //添加九五折优惠商品
    public function addNintyFive($barcode){
        $data = file_get_contents("../data/market.dat");
        $marketInfo = json_decode($data,true);

        if (!array_key_exists($barcode, $marketInfo['ALL_GOODS'])){
            echo "错误！该商品不存在<br>";
        }else{
            $marketInfo['NINTY_FIVE_PERCENT'][] = $barcode;
            $saveData = json_encode($marketInfo);
            file_put_contents("../data/market.dat", utf8_encode($saveData));

            require_once "HandleInput.php";
            $runner = new HandleInput("");
            $runner->printGoods();
        }
    }
}