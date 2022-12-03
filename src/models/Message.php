<?php

namespace bronek911\messages\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property string|null $subject
 * @property string|null $message
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $parent_message_id
 *
 * @property User $creadtedBy
 * @property MessageReceipent[] $messageReceipents
 * @property Message[] $messages
 * @property Message $parentMessage
 */
class Message extends \yii\db\ActiveRecord
{
    private $userClass;

    public function init()
    {
        parent::init();
        $this->userClass = \Yii::$app->modules['messaging']->userClass;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['created_at', 'created_by', 'parent_message_id'], 'integer'],
            [['subject'], 'string', 'max' => 100],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => $this->userClass, 'targetAttribute' => ['created_by' => 'id']],
            [['parent_message_id'], 'exist', 'skipOnError' => true, 'targetClass' => Message::class, 'targetAttribute' => ['parent_message_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subject' => Yii::t('app', 'Subject'),
            'message' => Yii::t('app', 'Message'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Creadted By'),
            'parent_message_id' => Yii::t('app', 'Parent Message ID'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'id',
            'message',
            'created_at',
            'created_by' => function($model){
                return $model->getCreatedByApiModels();
            },
        ];
    }

    /**
     * Gets query for [[CreadtedBy]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne($this->userClass, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[MessageReceipents]].
     *
     * @return \yii\db\ActiveQuery|MessageReceipentQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'receipent_group_id'])->via('messageReceipents');
    }

    /**
     * Gets query for [[MessageReceipents]].
     *
     * @return \yii\db\ActiveQuery|MessageReceipentQuery
     */
    public function getMessageReceipents()
    {
        return $this->hasMany(MessageReceipent::class, ['message_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery|MessageQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::class, ['parent_message_id' => 'id']);
    }

    /**
     * Gets query for [[ParentMessage]].
     *
     * @return \yii\db\ActiveQuery|MessageQuery
     */
    public function getParentMessage()
    {
        return $this->hasOne(Message::class, ['id' => 'parent_message_id']);
    }

    /**
     * {@inheritdoc}
     * @return MessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MessageQuery(get_called_class());
    }

    /**
     * Gets query for [[MessageReceipents]].
     *
     * @return \yii\db\ActiveQuery|MessageReceipentQuery
     */
    public function getCreatedByApiModels()
    {
        return $this
            ->getCreatedBy()
            ->joinWith('userProfile', false)
            ->select(['user.id', "user.username", "CONCAT(user_profile.firstname, ' ', user_profile.lastname) as fullname"])
            ->asArray()
            ->one();
    }
}
