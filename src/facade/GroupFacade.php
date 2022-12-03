<?php

namespace app\modules\messaging\facade;

use Yii;
use yii\helpers\Inflector;
use app\modules\messaging\models\Group;
use app\modules\messaging\models\UserGroup;
use app\modules\messaging\repositories\GroupRepository;

class GroupFacade
{
    private $userClass;
    private $repository;

    public function __construct()
    {
        $this->userClass = Yii::$app->modules['messaging']['userClass'];
        $this->repository = new GroupRepository();
    }

    /**
     * Opens a group for yourself and a set of other users (the group could've been previously made),
     * and returns the id of that group or false if an error has occured
      *
      * @param array $user
      * @return Group
      */
    public function create(array $ids): Group
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {

            $groupModel = new Group();
            $groupModel->is_active = true;

            if (!$groupModel->validate() || !$groupModel->save()) {
                throw new \Exception(Inflector::sentence(array_values($groupModel->getFirstErrors())));
            }

            if (!isset(array_flip($ids)[Yii::$app->user->id])) {
                $ids[] = Yii::$app->user->id;
            }

            foreach ($ids as $id) {

                $user = $this->userClass::findOne($id);

                if ($this->repository->userGroupExists($user, $groupModel)) {
                    continue;
                }

                $group = $this->addUser($user, $groupModel);

            }

            $groupModel->refresh();

            $transaction->commit();
            return $groupModel;

        } catch (\Throwable $th) {
            $transaction->rollBack();
            throw $th;
        }
    }

    /**
      * Adds a user to a group

      * @param [User] $user
      * @param Group $group
      * @return UserGroup
      */
    public function addUser($user, Group $group): UserGroup
    {
        try {

            if (!$user instanceof $this->userClass) {
                throw new \Exception('User not found!');
            }
    
            $userGroupModel = new UserGroup();
            $userGroupModel->user_id = $user->id;
            $userGroupModel->group_id = $group->id;
            $userGroupModel->is_active = true;
    
            if (!$userGroupModel->validate() || !$userGroupModel->save()) {
                throw new \Exception(Inflector::sentence(array_values($userGroupModel->getFirstErrors())));
            }
    
            return $userGroupModel;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Returns an array of opened groups
     *
     * @param [User] $user
     * @return array
     */
    public function getOpened($user): array
    {
        if (!$user instanceof $this->userClass) {
            throw new \Exception('User not found!');
        }

        return $this->repository->getOpened($user->id);
    }

    /**
     * Returns all group id's (array) that contain unread messages.
     *
     * @param [User] $user
     * @return array
     */
    public function getUnread($user): array
    {
        if (!$user instanceof $this->userClass) {
            throw new \Exception('User not found!');
        }

        return $this->repository->getUnread($user->id);
    }
    
    /**
     * Gets your messages by group
     *
     * @param Group $group
     * @return void
     */
    public function getMessages(Group $group)
    {
        return $this->repository->getMessages($group);
    }
    
    /**
     * Gets your messages by group
     *
     * @param Group $group
     * @return void
     */
    public function getMessageReceipents(Group $group)
    {
        return $this->repository->getMessageReceipents($group);
    }

    /**
     * Returns true if the user has unread messages in a certain group
     *
     * @param [User] $user
     * @param Group $group
     * @return boolean
     */
    public function countUnread($user, Group $group): int
    {
        return $this->repository->findUnreadMessages($user, $group)->count();
    }

    /**
     * Returns true if the user has unread messages in a certain group
     *
     * @param [User] $user
     * @param Group $group
     * @return boolean
     */
    public function isUnread($user, Group $group): bool
    {
        return $this->repository->findUnreadMessages($user, $group)->count() > 0;
    }

    /**
     * Updates unread status of a group
     *
     * @param [User] $user
     * @param Group $group
     * @return void
     */
    public function markAsRead($user, Group $group)
    {
        if ($this->isUnread($user, $group)) {
            foreach($this->repository->getUnreadMessages($user, $group) as $message){
                $message->setRead()->save();
            }
        }
    }

    /**
     * Sets a conversation name
     *
     * @param Group $group
     * @param string $name
     * @return Group
     */
    public function setGroupName(Group $group, string $name): Group
    {
        $group->name = $name;

        if (!$group->validate() || !$group->save()) {
            throw new \Exception(Inflector::sentence(array_values($group->getFirstErrors())));
        }

        return $group;
    }

    /**
     * Finds the last used group
     * @return type
     */
    private function findLast()
    {

    }

    /**
     * Sets the updated field for a user in a group
     *
     * @param [User] $user
     * @param Group $group
     * @param boolean $unread
     * @return void
     */
    public function setUnread($user, Group $group, $unread = true)
    {
        
    }

}
