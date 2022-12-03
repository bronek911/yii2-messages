<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

?>

<?php if($group !== null): ?>

    <?php $participants = $group->otherParticipants; ?>

    <div class="py-2 px-4 border-bottom d-none d-lg-block">
        <div id="group-details" class="d-flex align-items-center py-1" data-group-id="<?=$group->id?>" data-user="<?=Yii::$app->user->identity->userProfile->fullName?>">

            <?php if(sizeof($participants) == 1): ?>
                <!-- Image shown only if a conversation has no more than two participants -->
                <div class="position-relative">
                    <img id="my-chat-photo" src="<?=$participants[0]->avatar?>" class="rounded-circle mr-1" alt="<?=$participants[0]->receipent->userProfile->fullname?>" width="40" height="40">
                </div>
            <?php endif; ?>

            <!-- Name or list of names of another participants -->
            <div class="flex-grow-1 pl-3">
                <strong>
                    <?php echo $group->name != '' ? $group->name : '' ?>
                </strong>
                <br>
                <small>
                    <?=trim(implode(', ', ArrayHelper::map($participants, 'receipent_id', function($model){ return $model->getFullName(); })), ',')?>
                </small>
            </div>

        </div>
    </div>

    <div class="card chat-box" id="mychatbox">

        <div class="card-body chat-content">

            <?php foreach(Yii::$app->controller->module->groups->getMessages($group) as $message): ?>

                <?php $side = $message->created_by == Yii::$app->user->id ? 'chat-right' : 'chat-left'; ?>

                <div class="chat-item <?=$side?>" style="">
                    <img src="<?=$message->createdBy->userProfile->avatar?>">
                    <div class="chat-details">
                        <div class="chat-text"><?=$message->message?></div>
                        <div class="chat-time"><?=(new \DateTime())->setTimestamp($message->created_at)->format('Y-m-d H:i:s')?></div>
                        <div class="chat-author"><?= $message->createdBy->userProfile->fullName ?></div>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

    </div>

<?php else: ?>

    <div class="card chat-box" id="mychatbox">
        <div class="card-body chat-content" style="display: flex; justify-content: center; align-items: center;">
            <h3>Wybierz konwersację</h3>
        </div>
    </div>

<?php endif; ?>