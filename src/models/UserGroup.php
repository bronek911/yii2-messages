<?php

namespace bronek911\messages\models;

use bronek911\messages\models\Group;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_group".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $group_id
 * @property int|null $created_at
 * @property int|null $is_active
 *
 * @property Group $group
 * @property MessageReceipent[] $messageReceipents
 * @property User $user
 */
class UserGroup extends \yii\db\ActiveRecord
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
        return 'user_group';
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'group_id', 'created_at'], 'integer'],
            [['is_active'], 'boolean'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['group_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->userClass, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'group_id' => Yii::t('app', 'Group ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * Gets query for [[Group]].
     *
     * @return \yii\db\ActiveQuery|GroupQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id']);
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne($this->userClass, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return UserGroupQuery the active query used by this AR class.
     */
    public static function find($user = null, ?Group $group = null)
    {
        if (!$user instanceof (Yii::$app?->controller?->module?->userClass ?? '') && $user != null) {
            $user = null;
        }

        $query = new UserGroupQuery(get_called_class());
        $query->andFilterWhere(['and',
            ['user_id' => $user?->id],
            ['group_id' => $group?->id],
        ]);
    
        return $query;
    }
}
