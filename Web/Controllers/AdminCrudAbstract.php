<?php

namespace jarrus90\Core\Web\Controllers;

use Yii;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;
use jarrus90\Core\Web\Controllers\AdminController as Controller;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
abstract class AdminCrudAbstract extends Controller {

    use \jarrus90\Core\Traits\AjaxValidationTrait;

    /**
     * @param  int $id
     */
    abstract protected function getItem($id);

    /**
     * @var string
     */
    protected $modelClass;

    /**
     * @var string
     */
    protected $formClass;

    /**
     * @var string
     */
    protected $searchClass;

    /**
     * @throws InvalidConfigException
     */
    public function init() {
        parent::init();
        if ($this->modelClass === null) {
            throw new InvalidConfigException('Model class should be set');
        }
        if ($this->formClass === null) {
            throw new InvalidConfigException('Form class should be set');
        }
        if ($this->searchClass === null) {
            throw new InvalidConfigException('Search class should be set');
        }
    }

    /**
     * Lists all created items.
     * @return string
     */
    public function actionIndex() {
        $filterModel = Yii::createObject([
                    'class' => $this->searchClass
        ]);
        $filterModel->scenario = (array_key_exists('search', $filterModel->scenarios()) ? 'search' : 'default');
        return $this->render('index', [
                    'filterModel' => $filterModel,
                    'dataProvider' => $filterModel->search(Yii::$app->request->get()),
        ]);
    }

    /**
     * Shows create form.
     * @return string|Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate() {
        $model = Yii::createObject([
                    'class' => $this->formClass,
                    'scenario' => 'create',
                    'item' => Yii::createObject([
                        'class' => $this->modelClass,
                    ])
        ]);
        $this->performAjaxValidation($model);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('item', [
                    'model' => $model,
        ]);
    }

    /**
     * Shows update form.
     * @param  int $id Item id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id) {
        $item = $this->getItem($id);
        $model = Yii::createObject([
                    'class' => $this->formClass,
                    'scenario' => 'update',
                    'item' => $item,
        ]);

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->refresh();
        }

        return $this->render('item', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes item.
     * @param  int $id Item id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id) {
        $item = $this->getItem($id);
        $item->delete();
        return $this->redirect(['index']);
    }

}
