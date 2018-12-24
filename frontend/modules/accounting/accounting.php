<?php

namespace app\modules\accounting;

/**
 * accounting module definition class
 */
class accounting extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\accounting\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->layout = '@app/views/layouts/accounting';
        parent::init();

        // custom initialization code goes here
    }
}
