<?php
namespace frontend\controllers\auth;

use shop\forms\auth\SignupForm;
use shop\services\auth\SignupService;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\web\Controller;

class SignupController extends Controller
{

    private $signupService;

    public function __construct(string $id, Module $module, SignupService $signupService,array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->signupService = $signupService;
    }

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->signupService->signup($form);
                Yii::$app->session->setFlash('success', 'Check your email for further instruction.');
                return $this->goHome();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('index', [
            'model' => $form,
        ]);
    }


    public function actionConfirm($token){
        try {
            $this->signupService->confirm($token);
            Yii::$app->session->setFlash('success', 'Your email is confirmed.');
            return $this->redirect(['/login']);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->goHome();
        }
    }

}