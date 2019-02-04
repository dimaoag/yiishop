<?php
namespace frontend\controllers\contact;

use shop\forms\contact\ContactForm;
use shop\useCases\ContactService;
use yii\base\Module;
use yii\web\Controller;
use Yii;

class ContactController extends Controller
{
    private $contactService;

    public function __construct(string $id, Module $module, ContactService $contactService,array $config = []){
        parent::__construct($id, $module, $config);
        $this->contactService = $contactService;
    }


    public function actionIndex(){
        $form = new ContactForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->contactService->send($form);
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }
            return $this->refresh();
        } else {
            return $this->render('index', [
                'model' => $form,
            ]);
        }
    }

}