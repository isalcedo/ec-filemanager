<?php

namespace isalcedo\filemanager;

use yii\web\AssetBundle;

class FilemanagerAsset extends AssetBundle {

    public $sourcePath = '@isalcedo/filemanager/assets';
    public $css = [
        'css/filemanager.css'
    ];
    public $js = [
	    //'js/jstree/jstree.js',
        'js/filemanager.js',
        'js/folders.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'kartik\file\FileInputAsset',
        'kartik\editable\EditableAsset',
        'kartik\select2\Select2Asset'
    ];

    /**
     * uncomment in localhost for debug purpose
     */
//    public $publishOptions = [
//        'forceCopy' => true
//    ];
}
