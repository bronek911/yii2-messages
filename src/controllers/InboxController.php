<?php

namespace bronek911\messages\controllers;

use Yii;
use yii\web\Controller;
use bronek911\messages\models\Group;

/**
 * Default controller for the `messaging` module
 */
class InboxController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(?int $groupId = null)
    {
        $userGroups = $this->module->groups->getOpened(\Yii::$app->user->identity);

        $group = Group::findOne($groupId);
        if($group instanceof Group){
            $this->module->groups->markAsRead(\Yii::$app->user->identity, $group);
        }

        return $this->render('index', [
            'group' => $group,
            'groups' => $userGroups,
        ]);
    }


    public function actionSubmit()
    {
		Yii::$app->response->format = 'json';

        $group = Group::findOne(Yii::$app->request->post('group_id'));

        $message = $this->module->messages->send($group, Yii::$app->request->post('message'));

        return $message;
    }

}
