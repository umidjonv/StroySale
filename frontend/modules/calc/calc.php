<?php

namespace app\modules\calc;

/**
 * calc module definition class
 */
class calc extends \yii\base\Module
{

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\calc\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        //$this->layout = 'stroy';
        parent::init();

        // custom initialization code goes here
    }
}
