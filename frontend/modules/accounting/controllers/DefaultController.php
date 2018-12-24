<?php

namespace app\modules\accounting\controllers;

use yii\web\Controller;
use app\components;

/**
 * Default controller for the `accounting` module
 */
class DefaultController extends components\BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
