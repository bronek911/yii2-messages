<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

?>

<?php \app\modules\messaging\assets\MessagingAsset::register($this); ?>

<main class="content">

    <div id="inbox-container" class="container p-0">

        <div class="card">
            <div class="row g-0">
                <div class="col-12 col-lg-5 col-xl-3 border-right contact-list">
                    <?php echo $this->render('groups-list', [ 'groups' => $groups ]) ?>
                </div>
                <div class="col-12 col-lg-7 col-xl-9 conversation-column">

                    <?php Pjax::begin([
                        'id'=>'id-conversation',
                        'clientOptions' => ['']
                    ]); ?>
                        <?php echo $this->render('conversation', ['group' => $group]); ?>
                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>

        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => Url::to(['/messaging/inbox/submit']),
            'options' => [
                'id' => 'chatbox-form',
                'style' => ['display' => 'flex']
            ]
        ]); ?>

            <div class="row g-0" style="width: 100%;>
                <div class="col-sm-10">
                    <input id="chat-message-input" type="text" class="form-control" placeholder="Type a message">
                </div>
                <div class="col-sm-2">
                    <button id="submit-message-btn" type="submit" class="btn btn-primary chat-btn">
                        <i class="far fa-paper-plane"></i>
                    </button>
                </div>
            </div>

        <?php ActiveForm::end(); ?>

    </div>
</main>
