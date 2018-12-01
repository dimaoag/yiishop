<?php
namespace frontend\controllers\cabinet;


use yii\web\Controller;
use yii\filters\AccessControl;

class DefaultController extends Controller {

    public $layout = 'cabinet';


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }


    public function actionIndex(){

        return $this->render('index');
    }

}