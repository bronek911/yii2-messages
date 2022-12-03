<?php

namespace bronek911\messages\assets;

use yii\web\AssetBundle;

/**
 * @author Michał Bronowski <bronus911@gmail.com>
 */
class MessagingAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/modules/messaging/web';

    // public $basePath = __DIR__ . '/../web';

    /**
     * @inheritdoc
     */
    public function init()
    {

        parent::init();
    }

    public $css = [
        "https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css",
        'css/inbox.css',
    ];

    public $js = [
        'js/inbox.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'app\assets\SweetAlertAsset',
    ];
}
