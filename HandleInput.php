<?php
/**
 * 数据处理核心逻辑类
 * User: 长春
 * Date: 2016/7/19
 * Time: 21:18
 */
require_once "GoodsInfo.php";
class HandleInput extends GoodsInfo{
    protected $formatedInput = array();     //格式化为数组之后的用户输入

    /**
     * 对外接口，该类其他函数均为私有函数，外部无法调用，直接运行该方法即可
     */
    public function calculate(){
        $this->formatInput();             //格式化输入
        var_export($this->formatedInput);
    }

    /**
     * 格式化输入为php数组
     * 输入格式举例：[ 'ITEM000001', 'ITEM000003-2', 'ITEM000005', 'ITEM000005', 'ITEM000005' ]
     * 返回值举例：array('ITEM000001' => 1,'ITEM000003'=>2,'ITEM000005'=>3)
     */
    private function formatInput(){
        $userInput = substr($this->userBuy, 1, -1);         //去掉输入前后的中括号
        $userInput = str_replace(array("'","‘","’"),"",str_replace("，", ",", $userInput));      //去掉条形码的引号，消除中文符号的影响
        $barcodeArray = explode(',', $userInput);

        foreach ($barcodeArray as $key => $singleBarcode){
            $goodAndNum = explode('-', $singleBarcode);
            $addNum = 1;
            if(count($goodAndNum) == 2){        //'ITEM000003-2'形条形码
                $addNum = intval($goodAndNum[1]);
            }

            if (isset($this->formatedInput[$goodAndNum[0]])){
                $this->formatedInput[$goodAndNum[0]] += $addNum;
            }else{
                $this->formatedInput[$goodAndNum[0]] = 1;
            }
        }
    }

}