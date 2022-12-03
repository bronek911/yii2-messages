<?php

namespace bronek911\messages\models;

use Yii;
use bronek911\messages\models\Group;

/**
 * This is the model class for table "message_receipent".
 *
 * @property int $id
 * @property int|null $receipent_id
 * @property int|null $receipent_group_id
 * @property int|null $message_id
 * @property int|null $is_read
 *
 * @property Message $message
 * @property User $receipent
 * @property UserGroup $receipentGroup
 */
class MessageReceipent extends \yii\db\ActiveRecord
{
    private $userClass;

    public function init()
    {
        parent::init();
        $this->userClass = \Yii::$app->modules['messaging']->userClass;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_receipent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['receipent_id', 'receipent_group_id', 'message_id'], 'integer'],
            [['is_read'], 'boolean'],
            [['message_id'], 'exist', 'skipOnError' => true, 'targetClass' => Message::class, 'targetAttribute' => ['message_id' => 'id']],
            [['receipent_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['receipent_group_id' => 'id']],
            [['receipent_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->userClass, 'targetAttribute' => ['receipent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'receipent_id' => Yii::t('app', 'Receipent ID'),
            'receipent_group_id' => Yii::t('app', 'Receipent Group ID'),
            'message_id' => Yii::t('app', 'Message ID'),
            'is_read' => Yii::t('app', 'Is Read'),
        ];
    }

    /**
     * Gets query for [[Message]].
     *
     * @return \yii\db\ActiveQuery|MessageQuery
     */
    public function getMessage()
    {
        return $this->hasOne(Message::class, ['id' => 'message_id']);
    }

    /**
     * Gets query for [[Receipent]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getReceipent()
    {
        return $this->hasOne($this->userClass, ['id' => 'receipent_id']);
    }

    /**
     * Gets query for [[ReceipentGroup]].
     *
     * @return \yii\db\ActiveQuery|UserGroupQuery
     */
    public function getReceipentGroup()
    {
        return $this->hasOne(UserGroup::class, ['id' => 'receipent_group_id']);
    }

    /**
     * {@inheritdoc}
     * @return MessageReceipentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MessageReceipentQuery(get_called_class());
    }

    /**
     * Gets full receipent name if exists, or username
     *
     * @return string
     */
    public function getFullName(): string
    {
        $fullname = $this->receipent->userProfile->fullname;
        return $fullname != '' ? $fullname : $this->receipent->username;
    }

    /**
     * Gets receipent avatar link
     *
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->receipent->userProfile->avatar;
    }

    /**
     * Checks if a user message is read
     *
     * @return boolean
     */
    public function isRead(): bool
    {
        return (bool) $this->is_read;
    }

    /**
     * Sets is_read as true
     *
     * @return MessageReceipent
     */
    public function setRead(): MessageReceipent
    {
        $this->is_read = true;
        return $this;
    }
}
