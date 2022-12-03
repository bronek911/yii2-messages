<?php

namespace app\modules\messaging;

use Yii;
use app\models\User;
use app\modules\messaging\models\Group;
use app\modules\messaging\models\Message;
use app\modules\messaging\facade\GroupFacade;
use app\modules\messaging\facade\MessageFacade;

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
    public $controllerNamespace = 'app\modules\messaging\controllers';

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
        $this->groups = new GroupFacade();
        $this->messages = new MessageFacade();
    }
}
