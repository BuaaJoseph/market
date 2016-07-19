<?php
/**
 * 输出类，输出最后的计算结果
 * User: 长春
 * Date: 2016/7/19
 * Time: 22:57
 */

require_once "GoodsInfo.php";
class HandleOutput extends GoodsInfo{
    protected $outputStr = '';
    protected $changeLine = "{changeLine}";     //换行符替换字符串,网页和控制台换行符不一样

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
        $this->outputStr = $this->outputStr . "--------------------------------------------" . $this->changeLine;

        //输出满二减一减免信息
        if (count($this->threeToTwoResult) != 0){
            $this->outputStr = $this->outputStr . "买二赠一商品：" . $this->changeLine;
            foreach ($this->threeToTwoResult as $value){
                $this->outputStr = $this->outputStr . "名称：{$value['name']}，数量：{$value['num']}{$value['unit']}" . $this->changeLine;
            }
            $this->outputStr = $this->outputStr . "--------------------------------------------" . $this->changeLine;
        }

        //输出统计信息
        $this->outputStr = $this->outputStr . "总计：" . $this->totalMoney . "(元)" . $this->changeLine;

        //输出减免统计信息
        if ($this->reduceMoney != 0){
            $this->outputStr = $this->outputStr . "节省：" . $this->reduceMoney . "(元)" . $this->changeLine;
        }


    }
}