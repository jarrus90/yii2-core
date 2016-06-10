<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

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
     * @param  string $id
     * @return \jarrus90\User\models\Role|\jarrus90\User\models\Permission
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
        $filterModel = Yii::createObject(['class' => $this->searchClass]);
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
        /** @var \jarrus90\User\models\Role|\jarrus90\User\models\Permission $model */
        $model = Yii::createObject([
                    'class' => $this->formClass,
                    'scenario' => 'create',
                    'item' => Yii::createObject([
                        'class' => $this->modelClass,
                    ])
        ]);
        $this->performAjaxValidation($model);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->item->id]);
        }

        return $this->render('item', [
                    'model' => $model,
        ]);
    }

    /**
     * Shows update form.
     * @param  string $name
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id) {
        /** @var \jarrus90\User\models\Role|\jarrus90\User\models\Permission $model */
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
     * @param  string $name
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id) {
        $item = $this->getItem($id);
        $item->delete();
        return $this->redirect(['index']);
    }

}
