<?php

namespace bronek911\messages\models;

/**
 * This is the ActiveQuery class for [[Message]].
 *
 * @see Message
 */
class MessageQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Message[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Message|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byGroupId(int $id)
    {
        $this->joinWith('messageReceipents');
        $this->andWhere(['message_receipent.receipent_group_id' => $id]);
        return $this;
    }
}
