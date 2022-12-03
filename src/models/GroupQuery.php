<?php

namespace bronek911\messages\models;

/**
 * This is the ActiveQuery class for [[Group]].
 *
 * @see Group
 */
class GroupQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['is_active' => 1]);
    }

    /**
     * {@inheritdoc}
     * @return Group[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Group|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byUserId(int $id)
    {
        $this->joinWith('messageReceipents');
        $this->andWhere(['message_receipent.receipent_id' => $id]);
        return $this;
    }

    public function unread()
    {
        $this->joinWith('messageReceipents');
        $this->andWhere(['message_receipent.is_read' => 0]);
        return $this;
    }


}
