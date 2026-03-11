<?php

namespace app\controllers;

use yii\web\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return 'Spark is alive 🚀';
    }

    public function actionError()
    {
        return 'Error occurred';
    }
}
