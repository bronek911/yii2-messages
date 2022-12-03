<?php

namespace bronek911\messages;

use Yii;
use app\models\User;
use bronek911\messages\models\Group;
use bronek911\messages\models\Message;
use bronek911\messages\facade\GroupFacade;
use bronek911\messages\facade\MessageFacade;

/**
 * messaging module definition class
 */
class Module extends \yii\base\Module

{
    public $userClass = 'common\models\User';

    private $user;

    public $groups;
    public $messages;

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'bronek911\messages\controllers';

    /**
     * {@inheritdoc}
     */
    public $defaultRoute = 'inbox';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->user = \Yii::$app->user->identity;
        $this->groups = new GroupFacade($this->userClass);
        $this->messages = new MessageFacade($this->userClass);
    }
}
