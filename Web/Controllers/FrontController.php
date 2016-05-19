<?php

/**
 * core module
 *
 * AdminController
 * 
 * @package jarrus90\CoreAdmin\Controllers
 */

namespace jarrus90\Core\Web\Controllers;

/**
 * AdminController
 * 
 * By default set layout
 * Checks if user can access admin part of site
 * Registers AdminAsset
 */
class FrontController extends \yii\web\Controller {
    /**
     * Layout type
     * @var string
     */
    public $layout = '/frontend';
    
}