<?php

namespace bronek911\messages\repositories;

use bronek911\messages\models\Group;
use bronek911\messages\models\Message;
use bronek911\messages\models\UserGroup;
use bronek911\messages\models\GroupQuery;
use bronek911\messages\models\MessageQuery;
use bronek911\messages\models\UserGroupQuery;
use bronek911\messages\models\MessageReceipent;
use bronek911\messages\models\MessageReceipentQuery;

class GroupRepository
{
    /**
     * Checks if a user group exists
     *
     * @param [type] $user
     * @param Group $groupModel
     * @return boolean
     */
    public function userGroupExists($user, Group $groupModel): bool
    {
        $userClass = \Yii::$app->modules['messaging']->userClass;
        if (!$user instanceof $userClass) {
            return false;
        }

        return $this->findUserGroups($user, $groupModel)->exists();
    }

    /**
     * Returns query fore a user groups
     *
     * @param [type] $user
     * @param Group $groupModel
     * @return UserGroupQuery
     */
    public function findUserGroups($user, Group $groupModel): UserGroupQuery
    {
        $userClass = \Yii::$app->modules['messaging']->userClass;
        if (!$user instanceof $userClass) {
            return false;
        }

        return UserGroup::find()->andFilterWhere(['and',
            ['user_id' => $user->id],
            ['group_id' => $groupModel->id],
        ]);
    }

    /**
     * Returns array of user groups
     *
     * @param [type] $user
     * @param Group $groupModel
     * @return array
     */
    public function getUserGroups($user, Group $groupModel): array
    {
        return $this->findUserGroups($user, $groupModel)->all();
    }

    /**
     * Returns query of a active groups searched by a user
     *
     * @param integer $userId
     * @return GroupQuery
     */
    public function findOpened(int $userId): GroupQuery
    {
        return Group::find()->active()->byUserId($userId);
    }

    /**
     * Returns array of active groups searched by a user
     *
     * @param integer $userId
     * @return array
     */
    public function getOpened(int $userId): array
    {
        return $this->findOpened($userId)->all();
    }

    /**
     * Returns query of a unread groups searched by a user
     *
     * @param integer $userId
     * @return GroupQuery
     */
    public function findUnread(int $userId): GroupQuery
    {
        return $this->findOpened($userId)->unread();
    }

    /**
     * Returns array of unread groups searched by a user
     *
     * @param integer $userId
     * @return array
     */
    public function getUnread(int $userId): array
    {
        return $this->findUnread($userId)->all();
    }

    /**
     * Returns query for a unread receipent messages in a group
     *
     * @param [type] $user
     * @param Group $group
     * @return MessageReceipentQuery
     */
    public function findUnreadMessages($user, Group $group): MessageReceipentQuery
    {
        $userClass = \Yii::$app->modules['messaging']->userClass;
        if (!$user instanceof $userClass) {
            return null;
        }

        return MessageReceipent::find()
            ->byReceipent($user->id)
            ->byReceipentGroup($group->id)
            ->unread();
    }

    /**
     * Returns array of unread receipent messages in a group
     *
     * @param [type] $user
     * @param Group $group
     * @return array
     */
    public function getUnreadMessages($user, Group $group): array
    {
        return $this->findUnreadMessages($user, $group)->all();
    }

    /**
     * Returns query for all messages in a group
     *
     * @param Group $group
     * @return MessageQuery
     */
    public function findMessages(Group $group): MessageQuery
    {
        return Message::find()->byGroupId($group->id);
    }

    /**
     * Returns array of all messages in a group
     *
     * @param Group $group
     * @return array
     */
    public function getMessages(Group $group): array
    {
        return $this->findMessages($group)->all();
    }

    /**
     * Returns query for all messages in a group
     *
     * @param Group $group
     * @return MessageQuery
     */
    public function findMessageReceipents(Group $group): MessageReceipentQuery
    {
        return MessageReceipent::find()->byReceipentGroup($group->id);
    }

    /**
     * Returns array of all messages in a group
     *
     * @param Group $group
     * @return array
     */
    public function getMessageReceipents(Group $group): array
    {
        return $this->findMessageReceipents($group)->all();
    }
}
