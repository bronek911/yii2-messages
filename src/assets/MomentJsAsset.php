<?php

namespace bronek911\messages\assets;

use \yii\web\AssetBundle;

class MomentJsAsset extends AssetBundle {
    
    public $sourcePath = '@bower/moment/min';

    public $js = [
        'moment.min.js',
    ];
}