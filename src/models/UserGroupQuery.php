<?php

namespace app\modules\messaging\models;

/**
 * This is the ActiveQuery class for [[UserGroup]].
 *
 * @see UserGroup
 */
class UserGroupQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return UserGroup[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserGroup|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byId(int $id)
    {
        $this->where(['id' => $id]);
        return $this;
    }
}
