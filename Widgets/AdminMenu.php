<?php

/**
 * Class AdminMenu
 * 
 * @package jarrus90\CoreAdmin\widgets
 */

namespace jarrus90\Core\Widgets;

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
        foreach (Yii::$app->params['core.adminMenu'] AS $module) {
            $moduleMenuItems = $this->buildModuleMenu($module['list']);
            if(count($moduleMenuItems) > 0){
                $menuItems[] = [
                    'label' => Yii::t($module['id'], $module['label']),
                    'prepend' => ISSET($module['prepend']) ? $module['prepend'] : '',
                    'active' => ( $currentModule == $module->id ) ? true : false,
                    'childs' => $moduleMenuItems
                ];
            }
        }
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
    protected function buildModuleMenu($items){
        $list = [];
        foreach($items AS $item){
            $itemStructure = explode('/', $item['url']);
            if($itemStructure[0] == '') {
                array_shift($itemStructure);
            }
            $module = Yii::$app->getModule($itemStructure[0]);
            if(!$module || !$this->getIsAllowed($itemStructure[0], $itemStructure[1], $itemStructure[2])) {
                continue;
            }
            $list[] = [
                'title' => Yii::t($itemStructure[0], $item['label']),
                'url' => Url::toRoute($item['url'])
            ];
        }
        return $list;
    }

    /**
     * Is allowed
     * 
     * Check if user can access specified menu item
     * 
     * @param string $moduleId module
     * @param string $controllerId controller
     * @param string $actionId action
     * @return boolean Is user allowed to access
     */
    protected function getIsAllowed($moduleId, $controllerId, $actionId) {
        $module = Yii::$app->getModule($moduleId);
        $controllerClass = $module->controllerMap[$controllerId];
        $controller = new $controllerClass($controllerId, $moduleId);
        $action = $controller->createAction($actionId);
        $behaviors = $controller->behaviors();
        if(ISSET($behaviors['access'])) {
            $access = new $behaviors['access']['class']();
            $access->rules = $behaviors['access']['rules'];
            $access->init();
            foreach($access->rules AS $rule){
                if($allow = $rule->allows($action, Yii::$app->user, Yii::$app->getRequest())){
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
        return $this->render('@app/modules/core/views/widgets/adminMenu', [
                    'items' => $this->_menuItems
        ]);
    }

}
