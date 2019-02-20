<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.02.2019
 * Time: 16:07
 */
namespace app\components;

use yii\base\Widget;
use app\modules\calc\models\Product;

class ConvertingMeasure extends Widget
{
    public $from = null;
    public $to = null;
    public $val = null;


    public function run()
    {
        return $this->Measure_convert($this->from, $this->to, $this->val);

    }

    public function Measure_convert($pd,  $to='', $val)
    {
        $ms = new Product();
        $ms = $pd;
        $ms->convertibles = array($to);
        $conv = $ms->converting();



        switch ($conv)
        {
            case 'TON':
                $val = $val / 1000;
                break;
            case 'KG':
                $val = $val * 1000;
                break;
            case 'm50':
                $val = $val / 50;
                break;
            case 'm45':
                $val = $val / 45;
                break;
            case 'm25':
                $val = $val / 25;
                break;
            case 'm40':
                $val = $val / 40;
                break;

        }
        return $val;

    }

}