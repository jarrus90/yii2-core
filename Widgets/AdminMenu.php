<?php

/**
 * Class AdminMenu
 * 
 * @package app\modules\core\widgets
 */

namespace jarrus90\Core\widgets;

use Yii;
use yii\helpers\Url;

/**
 * Admin menu
 * 
 * Builds menu of adminpanel checking if user can access menu item
 */
class AdminMenu extends \yii\bootstrap\Widget {

    /**
     * List of menu items
     * @var srray
     */
    protected $_menuItems;

    /**
     * Initialization
     * 
     * Initialize widget and build menu elements
     */
    public function init() {
        parent::init();
        $this->_menuItems = [];
        if (!Yii::$app->user->isGuest) {
            $this->_menuItems = $this->buildMenu();
        }
    }

    /**
     * Build menu
     * 
     * Passes through modules and builds menu
     * 
     * @return array Menu items
     */
    protected function buildMenu() {
        $menuItems = [];
        $currentModule = Yii::$app->controller->module->id;
        $currentRoute = Yii::$app->requestedRoute;
        $currentUrl = substr(Url::toRoute([Yii::$app->request->url]), 1);
        $startPos = 999999;
        foreach (Yii::$app->params['admin']['menu'] AS $key => $list) {
            if ($list instanceof \Closure) {
                $list = $list();
            }
            if (!empty($list['items'])) {
                $moduleMenuItems = $this->buildModuleMenu($list['items']);
                if (count($moduleMenuItems) > 0) {
                    $active = false;
                    if ($currentModule == $key) {
                        $active = true;
                    } else if(!empty(Yii::$app->params['admin']['active']) && Yii::$app->params['admin']['active'] == $key) {
                        $active = true;
                    } else {
                        foreach ($moduleMenuItems AS $item) {
                            if(isset($item['active']) && $item['active'] == true) {
                                $active = true;
                            }
                        }
                        foreach ($moduleMenuItems AS $item) {
                            $path = (strpos('/', $item['url']) == 0 ) ? substr($item['url'], 1) : $item['url'];
                            if ($currentUrl == $path) {
                                $active = true;
                            }
                        }
                    }
                    $menuItems[] = [
                        'label' => $list['label'],
                        'icon' => ISSET($list['icon']) ? $list['icon'] : '',
                        'active' => $active,
                        'childs' => $moduleMenuItems,
                        'position' => !empty($list['position']) ? $list['position'] : $startPos++
                    ];
                }
            } else if (!empty($list['url'])) {
                if ($this->getIsAllowed($list['url'])) {
                    $current = Yii::$app->request->pathInfo;
                    $path = (strpos('/', $list['url']) == 0 ) ? substr($list['url'], 1) : $list['url'];
                    $active = substr($current, 0, strrpos($current, '/') + 1) == substr($path, 0, strrpos($path, '/') + 1);

                    if(!$active && isset($list['active']) && $list['active'] instanceof \Closure) {
                        $active = $list['active']();
                    }
                    
                    $menuItems[] = [
                        'label' => $list['label'],
                        'url' => Url::toRoute($list['url']),
                        'icon' => ISSET($list['icon']) ? $list['icon'] : '',
                        'active' => $active,
                        'position' => !empty($list['position']) ? $list['position'] : $startPos++
                    ];
                }
            }
        }
        usort($menuItems, function($a, $b) {
            return $a['position'] > $b['position'];
        });
        return $menuItems;
    }

    /**
     * Build module menu
     * 
     * Passes through the menu items described in module
     * and checks their availability for the current user
     * 
     * @param array $items Module menu items
     * @return array Available items
     */
    protected function buildModuleMenu($items) {
        $list = [];
        foreach ($items AS $item) {
            if (!$this->getIsAllowed($item['url'])) {
                continue;
            }
            $listItem = [
                'title' => $item['label'],
                'url' => Url::toRoute($item['url'])
            ];
            if(isset($item['active']) && $item['active'] instanceof \Closure) {
                $listItem['active'] = $item['active']();
            }
            $list[] = $listItem;
        }
        return $list;
    }

    protected function _getRouteStructure($url) {
        $itemStructure = explode('/', $url);
        if ($itemStructure[0] == '') {
            array_shift($itemStructure);
        }
        return $itemStructure;
    }

    /**
     * Is allowed
     * 
     * Check if user can access specified menu item
     * 
     * @param string $route Route
     * @param string $actionId action
     * @return boolean Is user allowed to access
     */
    protected function getIsAllowed($route) {

        $struct = explode('/', $route);
        $actionId = $struct[count($struct) - 1];
        $request = new yii\web\Request;
        $request->setUrl($route);
        $resolve = $request->resolve();

        $controller = Yii::$app->createController($resolve[0])[0];
        $action = $controller->createAction($actionId);
        $behaviors = $controller->behaviors();
        if (ISSET($behaviors['access'])) {
            $access = Yii::createObject([
                        'class' => $behaviors['access']['class'],
                        'rules' => $behaviors['access']['rules']
            ]);
            $access->init();
            foreach ($access->rules AS $rule) {
                if ($allow = $rule->allows($action, Yii::$app->user, Yii::$app->getRequest())) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Render widget
     * @return string
     */
    public function run() {
        return $this->render('adminMenu', [
                    'items' => $this->_menuItems
        ]);
    }

}
