<?php

/**
 * Class AdminMenu
 *
 * @package app\modules\core\widgets
 */

namespace jarrus90\Core\widgets;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dmstr\widgets\Menu;

/**
 * Admin menu
 *
 * Builds menu of adminpanel checking if user can access menu item
 */
class AdminMenu extends Menu {


    public $options = [
        'class' => 'sidebar-menu tree',
        'data' => [
            'widget' => 'tree'
        ]
    ];

    /**
     * Render widget
     * @return string
     */
    public function run() {
        Menu::$iconClassPrefix = '';
        $this->items = $this->getMenuList();
        $this->sortMenuItems();
        parent::run();
    }

    /**
     * Build menu
     *
     * Passes through modules and builds menu
     *
     * @return array Menu items
     */
    protected function getMenuList() {
        $items = [];
        foreach(Yii::$app->modules AS $module) {
            if(method_exists($module, 'getAdminMenu')) {
                $menu = $module->getAdminMenu();
                foreach($menu AS $menukey => $menuitem) {
                    $items[$menukey] = $menuitem;
                }
            }
        }
        return $items;
    }

    /**
     * Sorts menu from top to bottom
     *
     * @param array $items
     * @return array Sorted menu items
     */
    protected function sortMenuItems() {
        foreach($this->items AS $key => $item) {
            if(empty($item['position'])) {
                $this->items[$key]['position'] = 10000;
            }
        }
        uasort($this->items, function($a, $b) {
            return $a['position'] > $b['position'];
        });
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

        if(($controller = Yii::$app->createController($resolve[0])[0])) {
            $action = $controller->createAction($actionId);
            $behaviors = $controller->behaviors();
            if (ISSET($behaviors['access'])) {
                $access = Yii::createObject([
                            'class' => $behaviors['access']['class'],
                            'rules' => $behaviors['access']['rules']
                ]);
                $access->init();
                foreach ($access->rules AS $rule) {
                    if ($rule->allows($action, Yii::$app->user, Yii::$app->getRequest())) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

}
