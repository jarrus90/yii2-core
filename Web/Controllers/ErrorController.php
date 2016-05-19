<?php

namespace jarrus90\Core\Web\Controllers;

use Yii;
use yii\web\Controller;

class ErrorController extends Controller {

    public $layout = 'error';

    public function actionError() {
        chdir(Yii::getAlias('@webroot'));
        $exception = Yii::$app->errorHandler->exception;
        if ($exception) {
            if (Yii::$app->getRequest()->getIsAjax()) {
                return json_encode(['error' => Yii::t('yii', 'An internal server error occurred.')]);
            } else if ($exception instanceof \yii\web\HttpException && ($layout = Yii::$app->requestedAction->controller->layout)) {
                $this->layout = $layout;
                return $this->render('view', [
                    'exception' => $exception,
                    'handler' => Yii::$app->errorHandler
                ]);
            } else {
                return $this->render('layout', [
                    'exception' => $exception,
                    'handler' => Yii::$app->errorHandler
                ]);
            }
        }
    }

}
