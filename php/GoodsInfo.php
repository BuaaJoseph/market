<?php
/**
 * Created by PhpStorm.
 * User: 长春
 * Date: 2016/7/19
 * Time: 18:08
 */
header("content-Type: text/html; charset=Utf-8");
class GoodsInfo{
    protected $allGoods = array();                  //所有商品
    protected $threeToTwo = array();                //满二减一商品条形码
    protected $nintyFivePercent = array();          //九五折商品条形码

    protected $userBuy;                             //用户购买商品条形码清单 string
    protected $buyResult = array();                 //小票结果
    protected $threeToTwoResult = array();          //满二减一的商品清单
    protected $nintyFivePercentResult = array();    //九五折商品清单
    protected $totalMoney = 0;                      //总价
    protected $reduceMoney = 0;                     //节省总价

    /**
     * 类初始化接收用户输入
     * GoodsInnfo constructor.
     * @param $input string 用户输入
     */
    public function __construct($input) {
        $data = file_get_contents("../data/market.dat");
        $marketInfo = json_decode($data,true);
        $this->allGoods = $marketInfo['ALL_GOODS'];
        $this->threeToTwo = $marketInfo['THREE_TO_TWO'];
        $this->nintyFivePercent = $marketInfo['NINTY_FIVE_PERCENT'];
        $this->userBuy = trim($input);
    }


}
