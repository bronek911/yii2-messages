<?php

namespace bronek911\messages\repositories;

use bronek911\messages\models\Message;
use bronek911\messages\models\MessageQuery;
use bronek911\messages\models\MessageReceipent;
use bronek911\messages\models\MessageReceipentQuery;

class MessageRepository
{
    /**
     * Returns a query for a message receipent model by a specific user message.
     *
     * @param [type] $user
     * @param Message $message
     * @return MessageQuery
     */
    public function findUserMessage($user, Message $message): MessageQuery
    {
        return MessageReceipent::find()
            ->byReceipent($user->id)
            ->byMessageId($message->id);
    }

    /**
     * Returns a message receipent model by a specific user message
     *
     * @param [type] $user
     * @param Message $message
     * @return MessageReceipent|null
     */
    public function getUserMessage($user, Message $message): ?MessageReceipent
    {
        $userClass = \Yii::$app->modules['messaging']->userClass;
        if (!$user instanceof $userClass) {
            return null;
        }

        return $this->findUserMessage($user, $message)->one();
    }

    /**
     * Checks if a user is valid, if so returnes query to find his unread messages
     *
     * @param [User] $user
     * @return MessageReceipentQuery
     */
    public function findUnreadMessages($user): MessageReceipentQuery
    {
        return MessageReceipent::find()
            ->byReceipent($user->id)
            ->unread();
    }

    /**
     * Gets user unread messages array
     *
     * @param [User] $user
     * @return array
     */
    public function getUnreadMessages($user): array
    {
        $userClass = \Yii::$app->modules['messaging']->userClass;
        if (!$user instanceof $userClass) {
            return null;
        }

        $this->findUnreadMessages($user)->all();
    }
}
