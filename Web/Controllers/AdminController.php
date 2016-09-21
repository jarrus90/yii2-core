<?php

/**
 * core module
 *
 * AdminController
 * 
 * @package jarrus90\CoreAdmin\Controllers
 */

namespace jarrus90\Core\Web\Controllers;

use Yii;

/**
 * AdminController
 * 
 * By default set layout
 * Checks if user can access admin part of site
 * Registers AdminAsset
 */
class AdminController extends \yii\web\Controller {
    /**
     * Layout type
     * @var string
     */
    public $layout = '@jarrus90/Core/Web/layouts/backend';
    /**
     * BeforeAction
     * 
     * Check if user is blocked
     * @param \yii\base\Action $action the action to be executed.
     * @return boolean whether the action should continue to run.
     */
    /*
    public function beforeAction($action) {
        if(Yii::$app->user->can('admin') && !Yii::$app->user->can('blocked')){
            return parent::beforeAction($action);
        } else {
            return false;
        }
    }*/
}