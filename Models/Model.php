<?php

/**
 * Class Model
 *
 * Default form model
 * 
 * @package jarrus90\Core\Models
 */

namespace jarrus90\Core\Models;

use jarrus90\Core\Traits\TextEditorCleanupTrait;
use jarrus90\Core\Traits\TextLineCleanupTrait;
/**
 * Model
 * 
 * Provides basic cleanup functionnality for forms
 */
class Model extends \yii\base\Model {

    use TextEditorCleanupTrait;
    use TextLineCleanupTrait;
    /**
     * Allowed textarea tag attributes
     * @var array
     */
    protected $_safeAttributes = [
        'style'
    ];

    /**
     * Model
     * Modelitem to be updated by form
     * @var \yii\db\ActiveRecord 
     */
    protected $_model;

    /**
     * Get form _model item
     * @return \yii\db\ActiveRecord
     */
    public function getItem() {
        return $this->_model;
    }
    
    /**
     * Get form _model item
     * @return \yii\db\ActiveRecord
     */
    public function setItem($model) {
        $this->_model = $model;
        return $this->_model;
    }

}
