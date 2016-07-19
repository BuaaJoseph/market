<?php
/**
 * 输出类，输出最后的计算结果
 * User: 长春
 * Date: 2016/7/19
 * Time: 22:57
 */

require_once "GoodsInfo.php";
class HandleOutput{
    protected $outputStr = '';
    protected $goodListStr = '';                //输出商品列表时字符串
    protected $changeLine = "{changeLine}";     //换行符替换字符串,网页和控制台换行符不一样

    protected $allGoods = array();                  //所有商品
    protected $threeToTwo = array();                //满二减一商品条形码
    protected $nintyFivePercent = array();          //九五折商品条形码

    protected $buyResult = array();                 //小票结果
    protected $threeToTwoResult = array();          //满二减一的商品清单
    protected $nintyFivePercentResult = array();    //九五折商品清单
    protected $totalMoney = 0;                      //总价
    protected $reduceMoney = 0;                     //节省总价


    /**
     * HandleOutput constructor.
     * @param $buyResult array 用户小票商品信息
     * @param $threeToTwoResult array 满二赠一商品信息
     * @param $nintyFivePercentResult array 九五折商品信息
     * @param $totalMoney float 总价
     * @param $reduceMoney float 节约金额
     */
    public function __construct($buyResult, $threeToTwoResult, $nintyFivePercentResult, $totalMoney, $reduceMoney){
        $this->buyResult = $buyResult;
        $this->threeToTwoResult = $threeToTwoResult;
        $this->nintyFivePercentResult = $nintyFivePercentResult;
        $this->totalMoney = $totalMoney;
        $this->reduceMoney = $reduceMoney;
        $data = file_get_contents('../data/market.dat');
        $marketInfo = json_decode($data,true);
        $this->allGoods = $marketInfo['ALL_GOODS'];
        $this->threeToTwo = $marketInfo['THREE_TO_TWO'];
        $this->nintyFivePercent = $marketInfo['NINTY_FIVE_PERCENT'];
    }

    /**
     * 输出到网页
     */
    public function outputToWeb(){
        $this->generateOutputStr();
        $str = str_replace($this->changeLine, "<br>", $this->outputStr);
        echo $str;
    }

    /**
     * 输出到控制台
     */
    public function outputToCmd(){
        $this->generateOutputStr();
        $str = str_replace($this->changeLine, "\n", $this->outputStr);
        echo $str;
    }

    /**
     * 输出商品列表和优惠商品信息网页
     */
    public function outputGoodslistToWeb(){
        $this->generateGoodsListStr();
        $str = str_replace("@", "&nbsp;", str_replace($this->changeLine, "<br>", $this->goodListStr));
        echo $str;
    }

    /**
     * 输出商品列表和优惠商品信息到控制台
     */
    public function outputGoodlistToCmd(){
        $this->generateGoodsListStr();
        $str = str_replace($this->changeLine, "\n", $this->goodListStr);
        echo $str;
    }

    /**
     * 生成输出字符串
     */
    private function generateOutputStr(){
        $this->outputStr = $this->outputStr . "***<没钱赚商店>购物清单***" . $this->changeLine;

        //输出小票信息
        foreach ($this->buyResult as $line){
            $this->outputStr = $this->outputStr . "名称：{$line['name']}，数量：{$line['num']}{$line['unit']}，";
            $this->outputStr = $this->outputStr . "单价：{$line['price']}(元)，小计：{$line['money']}(元)";

            if (isset($line['reduce'])){
                $this->outputStr = $this->outputStr . "，节省{$line['reduce']}(元)";
            }
            $this->outputStr = $this->outputStr . $this->changeLine;
        }
        $this->outputStr = $this->outputStr . "--------------------------------------------------------------------" . $this->changeLine;

        //输出满二减一减免信息
        if (count($this->threeToTwoResult) != 0){
            $this->outputStr = $this->outputStr . "买二赠一商品：" . $this->changeLine;
            foreach ($this->threeToTwoResult as $value){
                $this->outputStr = $this->outputStr . "名称：{$value['name']}，数量：{$value['num']}{$value['unit']}" . $this->changeLine;
            }
            $this->outputStr = $this->outputStr . "--------------------------------------------------------------------" . $this->changeLine;
        }

        //输出统计信息
        $this->outputStr = $this->outputStr . "总计：" . $this->totalMoney . "(元)" . $this->changeLine;

        //输出减免统计信息
        if ($this->reduceMoney != 0){
            $this->outputStr = $this->outputStr . "节省：" . $this->reduceMoney . "(元)" . $this->changeLine;
        }

    }

    /**
     * 生成商品列表输出字符串
     */
    private function generateGoodsListStr(){
        $this->goodListStr = $this->goodListStr . "商品列表" . $this->changeLine;
        $this->goodListStr = $this->goodListStr . '---------------------------------------------------------------------------------------' . $this->changeLine;

        //所有商品信息
        foreach ($this->allGoods as $good){
            $this->goodListStr = $this->goodListStr . $this->generateGoodStr($good);
        }
        $this->goodListStr = $this->goodListStr . $this->changeLine . $this->changeLine;

        //满二赠一商品信息
        if (!empty($this->threeToTwo)){
            $this->goodListStr = $this->goodListStr . "满二赠一商品列表" . $this->changeLine;
            $this->goodListStr = $this->goodListStr . '---------------------------------------------------------------------------------------' . $this->changeLine;
            foreach ($this->threeToTwo as $barcode){
                $barcode = trim($barcode);
                $good = $this->allGoods[$barcode];
                $this->goodListStr = $this->goodListStr . $this->generateGoodStr($good);
            }
            $this->goodListStr = $this->goodListStr . $this->changeLine . $this->changeLine;
        }

        //九五折商品信息
        if (!empty($this->nintyFivePercent)){
            $this->goodListStr = $this->goodListStr . "九五折商品列表" . $this->changeLine;
            $this->goodListStr = $this->goodListStr . '---------------------------------------------------------------------------------------' . $this->changeLine;
            foreach ($this->nintyFivePercent as $barcode){
                $barcode = trim($barcode);
                $good = $this->allGoods[$barcode];
                $this->goodListStr = $this->goodListStr . $this->generateGoodStr($good);
            }
            $this->goodListStr = $this->goodListStr . $this->changeLine . $this->changeLine;
        }

    }

    /**
     * 重复代码，根据good生成商品信息字符串
     * @param $good array 商品信息数组
     * @return $str str 商品信息字符串
     */
    private function generateGoodStr($good){
        $str = $this->getString($good['barcode'],8) . $this->getString($good['name'], 10) . $this->getString($good['unit'], 4) .
            $this->getString($good['category'], 10) . $this->getString($good['subCategory'],6) . $this->getString($good['price'], 4) .  $this->changeLine;
        return $str;
    }

    private function getString($string, $length){
        if(strlen($string)/3 < $length){
            for($i = strlen($string)/3; $i < $length; $i++){
                $string .= "@";
            }
        }
        return $string;
    }
}