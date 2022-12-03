<?php

namespace bronek911\messages\assets;

use yii\web\AssetBundle;

/**
 * @author Michał Bronowski <bronus911@gmail.com>
 */
class MessagingAsset extends AssetBundle
{
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'bronek911\messages\assets\SweetAlertAsset',
        'bronek911\messages\assets\NiceScroll',
        'bronek911\messages\assets\MomentJs',
        'bronek911\messages\assets\Popper',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/../web');
        $this->setupAssets('css', ['css/inbox']);
        $this->setupAssets('js', ['js/inbox']);
        parent::init();
        
    }

    // "https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css",

    /**
     * Set up CSS and JS asset arrays based on the base-file names
     * @param string $type whether 'css' or 'js'
     * @param array $files the list of 'css' or 'js' basefile names
     */
    protected function setupAssets($type, $files = [])
    {
        $srcFiles = [];
        $minFiles = [];
        foreach ($files as $file) {
            $srcFiles[] = "{$file}.{$type}";
            $minFiles[] = "{$file}.min.{$type}";
        }
        if (empty($this->$type)) {
            $this->$type = YII_DEBUG ? $srcFiles : $minFiles;
        }
    }

    /**
     * Sets the source path if empty
     * @param string $path the path to be set
     */
    protected function setSourcePath($path)
    {
        if (empty($this->sourcePath)) {
            $this->sourcePath = $path;
        }
    }
}
