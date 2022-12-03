<?php

namespace bronek911\messages\assets;

use \yii\web\AssetBundle;

class PopperAsset extends AssetBundle {
    
    public $sourcePath = '@bower/jquery.nicescroll/dist';

    public $js = [
        'jquery.nicescroll.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}