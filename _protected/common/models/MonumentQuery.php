<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Monument]].
 *
 * @see Monument
 */
class MonumentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Monument[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Monument|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}