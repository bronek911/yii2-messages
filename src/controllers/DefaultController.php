<?php

namespace app\modules\messaging\controllers;

use Yii;
use yii\web\Controller;
use app\modules\messaging\models\Group;

/**
 * Default controller for the `messaging` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        // makes a new group based on an array of user id's (or opens an existing one)
        $newgroup = $this->module->groups->create([1,39]);

        // //check if you have unread messages
        // $hasMessages = $this->module->messages->hasUnreadMessages();

        // //check if you have unread messages in a group
        // $group = Group::findOne(12); 
        // $hasGroupMessages1 = $this->module->groups->isUnread(Yii::$app->user->identity, $group);

        //send a new message to the group
        $this->module->messages->send($newgroup, "First message");

        // //check this groups messages as 'read'
        // $this->module->groups->markAsRead($group);
        // $hasGroupMessages3 = $this->module->groups->isUnread(Yii::$app->user->identity, $group);

        // //get all your groups
        // $allgroups = $this->module->groups->getOpened(Yii::$app->user->identity);

        // //get all your unread groups
        // $unreadgroups = $this->module->groups->getUnread(Yii::$app->user->identity);

        // //easy to loop over the groups and messages
        // foreach ($allgroups as $group) {
        //     //gets the messages of a group
        //     $messages = $this->module->groups->getMessages($group);
        //     foreach ($messages as $message) {
        //         echo $message->group->id . ":" . $message->message . ($this->module->messages->isUnread(Yii::$app->user->identity, $message) ? " [Unread] " : "[_]") . "<br />";
        //     }
        // }

        // echo '<pre>';
        // var_dump($unreadgroups);
        // echo '</pre>';
        die;

        return $this->render('index');
    }
}
