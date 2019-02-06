<?php
namespace frontend\controllers\auth;

use common\auth\Identity;
use shop\forms\auth\LoginForm;
use shop\useCases\auth\AuthService;
use yii\base\Module;
use yii\web\Controller;
use Yii;



class AuthController  extends Controller
{
    private $authService;

    public function __construct(string $id, Module $module, \shop\useCases\auth\AuthService $authService, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->authService = $authService;
    }


    public function actionLogin(){
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->authService->auth($form);
                Yii::$app->user->login(new Identity($user),$form->rememberMe ? 3600 * 24 * 30 : 0);
                return $this->goBack();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('login', [
            'model' => $form,
        ]);
    }



    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->goHome();
    }

}