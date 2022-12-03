<?php

namespace app\modules\messaging\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "group".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $is_active
 *
 * @property User $createdBy
 * @property UserGroup[] $userGroups
 */
class Group extends \yii\db\ActiveRecord
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
        return 'group';
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
    public function rules()
    {
        return [
            [['created_at', 'created_by'], 'integer'],
            [['is_active'], 'boolean'],
            [['name'], 'string', 'max' => 100],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => $this->userClass, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'id',
            'name' => function($model){ 
                return $model->name != '' ? $model->name : implode(', ', $model->getOtherParticipantsNames());
            },
            'participants' => function($model){
                return $model->getOtherParticipantsApiModels();
            },
            'unread' => function($model){
                return \Yii::$app->getModule('messaging')->groups->countUnread(Yii::$app->user->identity, $model);
            },
            'created_at',
            'created_by' => function($model){
                return $model->getCreatedByApiModels();
            },
            'is_active',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
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
    public function getMessageReceipents()
    {
        return $this->hasMany(MessageReceipent::class, ['receipent_group_id' => 'id']);
    }

    /**
     * Gets query for [[UserGroups]].
     *
     * @return \yii\db\ActiveQuery|UserGroupQuery
     */
    public function getUserGroups()
    {
        return $this->hasMany(UserGroup::class, ['group_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return GroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupQuery(get_called_class());
    }

    /**
     * Gets query for [[MessageReceipents]].
     *
     * @return \yii\db\ActiveQuery|MessageReceipentQuery
     */
    public function findOtherParticipants()
    {
        return $this->getMessageReceipents()->where(['!=', 'receipent_id', \Yii::$app->user->id])->groupBy(['receipent_id']);
    }

    /**
     * Gets query for [[MessageReceipents]].
     *
     * @return \yii\db\ActiveQuery|MessageReceipentQuery
     */
    public function getOtherParticipants()
    {
        return $this->findOtherParticipants()->all();
    }

    /**
     * Gets query for [[MessageReceipents]].
     *
     * @return \yii\db\ActiveQuery|MessageReceipentQuery
     */
    public function getOtherParticipantsNames()
    {
        return ArrayHelper::map(
            $this->getMessageReceipents()->where(['!=', 'receipent_id', \Yii::$app->user->id])->distinct()->all(), 
            'receipent_id',
             function($model){ return $model->getFullName(); }
        );
    }

    /**
     * Gets query for [[MessageReceipents]].
     *
     * @return \yii\db\ActiveQuery|MessageReceipentQuery
     */
    public function getOtherParticipantsApiModels()
    {
        return $this
            ->findOtherParticipants()
            ->joinWith('receipent', false)
            ->joinWith('receipent.userProfile', false)
            ->select(['user.id', "user.username", "CONCAT(user_profile.firstname, ' ', user_profile.lastname) as fullname"])
            ->asArray()
            ->all();
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
