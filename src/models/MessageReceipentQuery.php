<?php

namespace bronek911\messages\models;

/**
 * This is the ActiveQuery class for [[MessageReceipent]].
 *
 * @see MessageReceipent
 */
class MessageReceipentQuery extends \yii\db\ActiveQuery
{

    /**
     * {@inheritdoc}
     * @return MessageReceipent[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MessageReceipent|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param integer $user_id
     * @return MessageReceipentQuery
     */
    public function byReceipent(int $user_id): MessageReceipentQuery
    {
        $this->andWhere(['receipent_id' => $user_id]);
        return $this;
    }

    /**
     * @param integer $group_id
     * @return MessageReceipentQuery
     */
    public function byReceipentGroup(int $group_id): MessageReceipentQuery
    {
        $this->andWhere(['receipent_group_id' => $group_id]);
        return $this;
    }

    public function byMessageId(int $id)
    {
        $this->andWhere(['message_id' => $id]);
        return $this;
    }

    /**
     * @param integer $group_id
     * @return MessageReceipentQuery
     */
    public function unread(): MessageReceipentQuery
    {
        $this->andWhere(['is_read' => [0, null]]);
        return $this;
    }
}
