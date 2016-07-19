<?php
/**
 * Created by PhpStorm.
 * User: 长春
 * Date: 2016/7/19
 * Time: 18:08
 */

class Goods{

    //获得所有在售的商品信息
    public function getGoods(){
        $data = file_get_contents('market.dat');
        $marketInfo = json_decode($data,true);
        var_export($marketInfo['ALL_GOODS']);
    }

    private function get_php_file($filename) {
        return trim(substr(file_get_contents($filename)));
    }
}
