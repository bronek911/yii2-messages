<?php

namespace bronek911\messages\facade;

use Yii;
use yii\helpers\Inflector;
use bronek911\messages\models\Group;
use bronek911\messages\models\Message;
use bronek911\messages\models\MessageReceipent;
use bronek911\messages\repositories\MessageRepository;

class MessageFacade
{
    private $userClass;
    private $repository;

    public function __construct(string $userClass)
    {
        $this->userClass = $userClass;
        $this->repository = new MessageRepository();
    }

    /**
     * Sends a message to a group and notifies all those users
     *
     * @param Group $group
     * @param string $message
     * @return Message
     */
    public function send(Group $group, string $message): Message
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            
            $messageModel = new Message();

            $messageModel->message = $message;
    
            if (!$messageModel->validate() || !$messageModel->save()) {
                throw new \Exception(Inflector::sentence(array_values($messageModel->getFirstErrors())));
            }
    
            foreach($group->userGroups as $userGroup){
    
                $receipentModel = new MessageReceipent();
    
                $receipentModel->receipent_id = $userGroup->user_id;
                $receipentModel->receipent_group_id = $group->id;
                $receipentModel->message_id = $messageModel->id;
                $receipentModel->is_read = false;
    
                if (!$receipentModel->validate() || !$receipentModel->save()) {
                    throw new \Exception(Inflector::sentence(array_values($receipentModel->getFirstErrors())));
                }
    
            }

            $transaction->commit();
            return $messageModel;
        } catch (\Throwable $th) {
            $transaction->rollBack();
            throw $th;
        }
    }

    /**
     * Returns true if the user has unread messages
     *
     * @return boolean
     */
    public function hasUnreadMessages()
    {
        return $this->repository->findUnreadMessages(Yii::$app->user->identity)->count() > 0;
    }
    
    /**
     * Returns true if the user has unread messages in a certain group
     *
     * @param [User] $user
     * @param Message $message
     * @return boolean
     */
    public function isUnread($user, Message $message)
    {
        if (!$user instanceof $this->userClass) {
            throw new \Exception('User not found!');
        }

        $userMessage = $this->repository->getUserMessage($user, $message);

        if (!$userMessage) {
            throw new \Exception('User message not found!');
        }

        return !$userMessage->isRead();
    }

    /**
     * Sets the updated field for a user
     * @param type $user
     * @param type $unread (default: sets to unread)
     */
    public function setUnread(User $user, bool $unread = true)
    {

    }
}
