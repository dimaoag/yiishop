<?php
namespace frontend\controllers\auth;



use yii\authclient\ClientInterface;
use yii\authclient\AuthAction;
use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Module;
use yii\web\Controller;
use shop\services\auth\NetworkService;

class NetworkController extends Controller
{
    private $networkService;

    public function __construct(string $id, Module $module, NetworkService $networkService, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->networkService = $networkService;
    }

    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess(ClientInterface $client): void
    {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes, 'id');
        try {
            $user = $this->networkService->auth($network, $identity);
            Yii::$app->user->login($user, Yii::$app->params['rememberMeDuration']);
            $this->goHome();
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

}