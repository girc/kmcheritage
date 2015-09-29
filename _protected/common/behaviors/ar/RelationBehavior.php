<?php

namespace common\behaviors\ar;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Save related
 *
 * ~~~
 * $order = new Order();
 *
 * // associated array
 * $item1 = [
 *     'product_id' => 1,
 *     'qty' = 10,
 * ];
 * // as object
 * $item2 = new Item();
 * $item2->product_id = 2;
 *
 * $order->items = [
 *     $item1,
 *     $item2,
 * ];
 *
 * $order->save();
 * ~~~
 *
 * @property ActiveRecord $owner
 *
 * @author Ram Shrestha <sendmail4ram@gmail.com>
 */
class RelationBehavior extends \yii\base\Behavior
{
    const TYPE_HAS_ONE = 'has_one';
    const TYPE_HAS_MANY = 'has_many';
    const TYPE_HAS_ONE_VIA_RELATION = 'has_one_via_relation';
    const TYPE_HAS_MANY_VIA_RELATION = 'has_many_via_relation';

    public $isRelatedToSelf = false;
    /**
     * @var string type of relation such as HAS_ONE, HAS_MANY, HAS_ONE_VIA_RELATION, HAS_MANY_VIA_RELATION
     */
    public $relationType;
    /**
     * @var array scenario for relation
     */
    public $relatedScenarios = [];

    /**
     * @var \Closure callback execute before related validate.
     *
     * ```php
     * function($model,$index,$relationName){
     *
     * }
     * ```
     */
    public $beforeRValidate;

    /**
     * @var \Closure Execute before relation save
     * When return false, save will be canceled
     * @see [[$beforeRValidate]]
     * If function return `false`, save will be canceled
     */
    public $beforeRSave;

    /**
     * @var \Closure Execute after relation save
     * @see [[$beforeRValidate]]
     */
    public $afterRSave;

    /**
     * @var boolean If true clear related error
     */
    public $clearError = true;

    /**
     * @var boolean
     */
    public $deleteUnsaved = true;

    /**
     * @var \Closure function to check is two model is equal.
     *
     * ```
     * function ($model1, $model2, $keys){
     *     return $model1['id'] == $model2['id'];
     * }
     * ```
     */
    public $isEqual;

    /**
     * @var array
     */
    private $_old_relations = [];

    /**
     * @var array
     */
    private $_original_relations = [];

    /**
     * @var array
     */
    private $_process_relation = [];

    /**
     * @var array
     */
    private $_relatedErrors = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_VALIDATE => 'afterValidate',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',

        ];
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if ($this->setRelated($name, $value) === false) {
            parent::__set($name, $value);
        }
    }

    /**
     * Populate relation
     * @param string $name
     * @param array||\yii\db\ActiveRecord||\yii\db\ActiveRecord[] $values
     * @return boolean
     */
    public function setRelated($name, $values)
    {
        $relation = $this->owner->getRelation($name, false);


        if ($relation === null) {
            return false;
        }


        if ($this->owner->tableName() == (new $relation->modelClass)->tableName()) {
            // The relation has  same source and destination Database table
            $this->isRelatedToSelf = true;
        } else {
            $this->isRelatedToSelf = false;
        }

        if (!$relation->multiple && !$relation->via) {
            $this->relationType = $this::TYPE_HAS_ONE;
        } elseif ($relation->multiple && !$relation->via) {
            $this->relationType = $this::TYPE_HAS_MANY;
        } elseif (!$relation->multiple && $relation->via) {
            $this->relationType = $this::TYPE_HAS_ONE_VIA_RELATION;
        } elseif ($relation->multiple && $relation->via) {
            $this->relationType = $this::TYPE_HAS_MANY_VIA_RELATION;
        }

        $class = $relation->modelClass;
        $multiple = $relation->multiple;
        $link = $relation->link;
        $uniqueKeys = array_flip($class::primaryKey());

        foreach (array_keys($link) as $from) {
            unset($uniqueKeys[$from]);
        }
        $uniqueKeys = array_keys($uniqueKeys);
        if (isset($this->_original_relations[$name])) {
            $children = $this->_original_relations[$name];
        } else {
            $this->_original_relations[$name] = $children = $this->owner->$name;
        }
        if ($multiple) {
            $newChildren = [];
            foreach ($values as $index => $value) {
                // get from current relation
                // if has child with same primary key, use this
                /* @var $newChild \yii\db\ActiveRecord */
                $newChild = null;
                if (empty($relation->indexBy)) {
                    foreach ($children as $i => $child) {
                        if ($this->isEqual($child, $value, $uniqueKeys)) {
                            if ($value instanceof $class) {
                                $newChild = $value;
                                $newChild->isNewRecord = $child->isNewRecord;
                                $newChild->oldAttributes = $child->oldAttributes;
                            } else {
                                $newChild = $child;
                            }
                            unset($children[$i]);
                            break;
                        }
                    }
                } elseif (isset($children[$index])) {
                    $child = $children[$index];
                    if ($value instanceof $class) {
                        $newChild = $value;
                        $newChild->isNewRecord = $child->isNewRecord;
                        $newChild->oldAttributes = $child->oldAttributes;
                    } else {
                        $newChild = $child;
                    }
                    unset($children[$index]);
                }
                if ($newChild === null) {
                    $newChild = $value instanceof $class ? $value : new $class;
                }
                if (isset($this->relatedScenarios[$name])) {
                    $newChild->scenario = $this->relatedScenarios[$name];
                }
                if (!$value instanceof $class) {
                    $newChild->load($value, '');
                }
                foreach ($link as $from => $to) {
                    if (!$this->isRelatedToSelf){
                        /**
                         *  Exchanging Database primary key to foreign key
                         *  only if Owner and Relation model refer to different Database Table
                         */
                        $newChild->$from = $this->owner->$to;
                    }
                }
                $newChildren[$index] = $newChild;

            }
            $this->_old_relations[$name] = $children;
            $this->owner->populateRelation($name, $newChildren);
            $this->_process_relation[$name] = true;



        } else {
            $newChild = null;
            if ($children === null) {
                if ($values !== null) {
                    $newChild = $values instanceof $class ? $values : new $class;
                    $this->_process_relation[$name] = true;
                }
            } else {
                if ($values !== null) {
                    $newChild = $values instanceof $class ? $values : $children;
                    if ($values instanceof $class) {
                        $newChild = $values;
                        $newChild->oldAttributes = $children->oldAttributes;
                        $newChild->isNewRecord = $children->isNewRecord;
                    } else {
                        $newChild = $children;
                    }
                } else {
                    $this->_old_relations[$name] = [$children];
                }
                $this->_process_relation[$name] = true;
            }
            if ($newChild !== null) {
                if (isset($this->relatedScenarios[$name])) {
                    $newChild->scenario = $this->relatedScenarios[$name];
                }
                if (!$values instanceof $class) {
                    $newChild->load($values, '');
                }
                foreach ($link as $from => $to) {
                    if (!$this->isRelatedToSelf){
                        /**
                         *  Exchanging Database primary key to foreign key
                         *  only if Owner and Relation model refer to different Database Table
                         */
                        $newChild->$from = $this->owner->$to;
                    }
                }
            }
            $this->owner->populateRelation($name, $newChild);
        }
        return true;
    }

    /**
     * Check is boot of model is equal
     * @param \yii\db\ActiveRecord|array $model1
     * @param \yii\db\ActiveRecord|array $model2
     * @param array $keys
     * @return boolean
     */
    protected function isEqual($model1, $model2, $keys)
    {
        if ($this->isEqual !== null) {
            return call_user_func($this->isEqual, $model1, $model2, $keys);
        }
        foreach ($keys as $key) {
            if (ArrayHelper::getValue($model1, $key) != ArrayHelper::getValue($model2, $key)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return $this->owner->getRelation($name, false) !== null || parent::canSetProperty($name, $checkVars);
    }

    /**
     * Handler for event afterValidate
     */
    public function afterValidate()
    {
        /* @var $child \yii\db\ActiveRecord */
        foreach ($this->_process_relation as $name => $process) {

            //https://github.com/yiisoft/yii2/issues/1256
            $relation = $this->owner->getRelation($name, false);
            $modelClassShortName = (new \ReflectionClass($relation->modelClass))->getShortName();


            if (!$process) {
                continue;
            }
            if ($this->clearError) {
                //$this->_relatedErrors[$name] = [];
                $this->_relatedErrors[$modelClassShortName] = [];
            }
            $error = false;
            $relation = $this->owner->getRelation($name);
            $children = $this->owner->$name;
            if ($relation->multiple) {
                foreach ($children as $index => $child) {
                    if (isset($this->beforeRValidate)) {
                        call_user_func($this->beforeRValidate, $child, $index, $name);
                    }
                    if (!$child->validate()) {
                        //$this->_relatedErrors[$name][$index] = $child->getFirstErrors();
                        $this->_relatedErrors[$modelClassShortName][$index] = $child->getFirstErrors();
                        $error = true;
                    }
                }
            } else {
                if (isset($this->beforeRValidate)) {
                    call_user_func($this->beforeRValidate, $children, null, $name);
                }
                if (!$children->validate()) {
                    //$this->_relatedErrors[$name] = $child->getFirstErrors();
                    $this->_relatedErrors[$modelClassShortName] = $child->getFirstErrors();
                    $error = true;
                }
            }
            if ($error) {
                //$this->owner->addError($name, $this->_relatedErrors);
                $this->owner->addError($modelClassShortName, $this->_relatedErrors);
            }
        }

    }

    /**
     * Handler for event afterSave
     */
    public function afterSave()
    {
        foreach ($this->_process_relation as $name => $process) {
            if (!$process) {
                continue;
            }
            // delete old related
            /* @var $child \yii\db\ActiveRecord */
            if (isset($this->_old_relations[$name])) {
                foreach ($this->_old_relations[$name] as $child) {
                    $child->delete();
                }
                unset($this->_old_relations[$name]);
            }
            // save new relation
            $relation = $this->owner->getRelation($name);
            $link = $relation->link;
            $children = $this->owner->$name;



            if ($relation->multiple) {

                foreach ($children as $index => $child) {
                    foreach ($link as $from => $to) {
                        if(!$relation->via){
                            //  If there is no via relation
                            $child->$from = $this->owner->$to;
                        }

                    }
                    if ($this->beforeRSave === null || call_user_func($this->beforeRSave, $child, $index, $name) !== false) {
                        $child->save(false);
                        if (isset($this->afterRSave)) {
                            call_user_func($this->afterRSave, $child, $index, $name);
                        }
                    } elseif ($this->deleteUnsaved && !$child->getIsNewRecord()) {
                        $child->delete();
                    }
                }
            } else {
                /* @var $children \yii\db\ActiveRecord */
                if ($children !== null) {
                    foreach ($link as $from => $to) {
                        if(!$relation->via){
                            //  If there is no via relation
                            $child->$from = $this->owner->$to;
                        }
                    }
                    if ($this->beforeRSave === null || call_user_func($this->beforeRSave, $children, null, $name) !== false) {
                        $children->save(false);
                        if (isset($this->afterRSave)) {
                            call_user_func($this->afterRSave, $children, null, $name);
                        } elseif ($this->deleteUnsaved && !$children->getIsNewRecord()) {
                            $child->delete();
                        }
                    }
                }
            }


            // Now the children have been saved so We are gonna link these children
            if($relation->via){
                $this->setViaRelated($name,$children);
            }
            unset($this->_process_relation[$name], $this->_original_relations[$name]);
        }

    }

    public function setViaRelated($relationName,$children){
        foreach($children as $child){
            $this->owner->link($relationName,$child);
        }
    }

    /**
     * Check if relation has error.
     * @param  string $relationName
     * @return boolean
     */
    public function hasRelatedErrors($relationName = null)
    {
        if ($relationName === null) {
            foreach ($this->_relatedErrors as $errors) {
                if (!empty($errors)) {
                    return true;
                }
            }
            return false;
        } else {
            return !empty($this->_relatedErrors[$relationName]);
        }
    }

    /**
     * Get related error(s)
     * @param string|null $relationName
     * @return array
     */
    public function getRelatedErrors($relationName = null)
    {
        if ($relationName === null) {
            return $this->_relatedErrors;
        } else {
            return isset($this->_relatedErrors[$relationName]) ? $this->_relatedErrors[$relationName] : [];
        }
    }
}