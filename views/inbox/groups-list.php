<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

$module = \Yii::$app->controller->module;

?>
<?php Pjax::begin([
    'id'=>'id-conversation-list',
    'scrollTo' => false,
    'options' => [
        'class' => ['abc'],
        'data' => ['has-new-messages' => (\Yii::$app->modules['messaging']->messages->hasUnreadMessages(Yii::$app->user->identity) ? 'true' : 'false')]
    ]
]); ?>

    <div id="contact-list-container" data-has-new-messages="<?=\Yii::$app->modules['messaging']->messages->hasUnreadMessages(Yii::$app->user->identity) ? 'true' : 'false'?>">

        <div class="px-4 d-none d-md-block" data-has-new-messages="<?=\Yii::$app->modules['messaging']->messages->hasUnreadMessages(Yii::$app->user->identity) ? 'true' : 'false'?>">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <input type="text" class="form-control my-3" placeholder="Search..." disabled>
                </div>
            </div>
        </div>

        <?php foreach($groups as $group): ?>

            <?php 
                $participants = $group->otherParticipants;
                $activeClass = Yii::$app->request->get('groupId') == $group->id ? ' active' : '';
            ?>

            <a href="<?=Url::to(['/messaging/inbox/index', 'groupId' => $group->id])?>" class="list-group-item list-group-item-action border-0<?=$activeClass?>">

                <?php if($module->groups->isUnread(Yii::$app->user->identity, $group)): ?>
                    <div class="badge bg-success float-right"><?php echo $module->groups->countUnread(Yii::$app->user->identity, $group); ?></div>
                <?php endif; ?>

                <div class="d-flex align-items-start">

                    <?php if(sizeof($participants) == 1): ?>
                        <!-- Image shown only if a conversation has no more than two participants -->
                        <div class="position-relative">
                            <img id="my-chat-photo" src="<?=$participants[0]->avatar?>" class="rounded-circle mr-1" alt="<?=$participants[0]->fullName?>" width="40" height="40">
                        </div>
                    <?php endif; ?>

                    <div class="flex-grow-1 ml-3">
                        <?php if($group->name != ''): ?>
                            <strong>
                                <?php echo $group->name != '' ? $group->name : '' ?>
                            </strong>
                            <br>
                        <?php endif; ?>

                        <?=trim(implode(', ', ArrayHelper::map($participants, 'receipent_id', function($model){ return $model->getFullName(); })), ',')?>
                    </div>
                </div>
            </a>

        <?php endforeach; ?>

        <hr class="d-block d-lg-none mt-1 mb-0">

    </div>
<?php Pjax::end(); ?>