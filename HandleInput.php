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
        $this->calResult();               //计算最终结果
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
            $singleBarcode = trim($singleBarcode);
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


    /**
     * 计算最终输出小票数据
     * 核心逻辑
     */
    private function calResult(){
        foreach ($this->formatedInput as $barCode => $buyNum){
            $singleReduce = 0;      //该商品节省金钱
            $line = array(
                'name' => $this->allGoods[$barCode]['name'],
                'barcode' => $barCode,
                'num' => $buyNum,
                'price' => sprintf("%.2f", $this->allGoods[$barCode]['price']),
                'unit' => $this->allGoods[$barCode]['unit'],
                'money' => 0,
            );

            if (in_array($barCode, $this->threeToTwo) && $buyNum > 2){     //优先使用满二减一优惠
                $reduction = intval($buyNum) / 3;                           //优惠商品个数
                $line['money'] = sprintf("%.2f", $line['price'] * ($buyNum - $reduction));
                $singleReduce = sprintf("%.2f", $reduction * $line['price']);

                $this->threeToTwoResult[] = array(
                    'name' => $this->allGoods[$barCode]['name'],
                    'num' => $reduction,
                    'unit' => $this->allGoods[$barCode]['unit'],
                );
            }elseif(in_array($barCode, $this->nintyFivePercent)){          //载判断是否满足九五折优惠,金额保留两位小数
                $line['money'] = sprintf("%.2f", $line['num'] * $line['price'] * 0.95);
                $line['reduce'] = sprintf("%.2f", $line['num'] * $line['price'] * 0.05);
                $singleReduce = sprintf("%.2f", $line['num'] * $line['price'] * 0.05);
            }else{                                                         //该商品不参加优惠
                $line['money'] = sprintf("%.2f", $line['num'] * $line['num']);
            }
            $this->totalMoney = sprintf("%.2f", $this->totalMoney + floatval($singleReduce));          //计算总价
            $this->reduceMoney = sprintf("%.2f", $this->reduceMoney + floatval($singleReduce));       //计算节省总钱数
            $this->buyResult[] = $line;
        }
    }

}