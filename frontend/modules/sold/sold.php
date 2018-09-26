<?php

namespace app\modules\sold;

/**
 * calc module definition class
 */
class sold extends \yii\base\Module
{

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\sold\controllers';

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