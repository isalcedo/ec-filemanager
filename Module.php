<?php

namespace isalcedo\filemanager;

use Yii;
use yii\helpers\BaseFileHelper;

class Module extends \yii\base\Module {

    public $directory = '@webroot';

    /**
     * @var array 
     * 
     * 1. Upload files to local directory (files will be store in @common in order to let backend/frontend application to access):
     * $storage = ['local'];
     *      
     * 2. Upload files to AWS S3:
     * $storage = [
     *      's3' => [
     *          'host' => '',
     *          'key' => '',
     *          'secret' => '',
     *          'bucket' => '',
     *          'cdnDomain' => '',
     *          'prefixPath' => '',
     *          'cacheTime' => '', // if empty, by default is 2592000 (30 days)
     *      ]
     * ];
     */
    public $storage = ['local'];
    public $cache = 'cache';
    public $public_path = false;

    /**
     * @var array 
     * Configure to use own models function
     */
    public $models = [
        'files' => 'isalcedo\filemanager\models\Files',
        'filesSearch' => 'isalcedo\filemanager\models\FilesSearch',
        'filesRelationship' => 'isalcedo\filemanager\models\FilesRelationship',
        'filesTag' => 'isalcedo\filemanager\models\FilesTag',
        'folders' => 'isalcedo\filemanager\models\Folders',
    ];
    public $acceptedFilesType = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/pdf'
    ];
    public $maxFileSize = 8; // MB
    public $thumbnailSize = [120, 120]; // width, height
    /**
     * This configuration will be used in 'filemanager/files/upload'
     * To support dynamic multiple upload
     * Default multiple upload is true, max file to upload is 10
     * @var type 
     */
    public $filesUpload = [
        'multiple' => true,
        'maxFileCount' => 10
    ];

    public function init() {
        if (empty($this->thumbnailSize)) {
            throw new \yii\base\InvalidConfigException("thumbnailSize cannot be empty.");
        } else if (!isset($this->thumbnailSize[0]) || !isset($this->thumbnailSize[1]) || empty($this->thumbnailSize[0]) || empty($this->thumbnailSize[1])) {
            throw new \yii\base\InvalidConfigException("Invalid thumbnailSize value.");
        }
        Yii::$app->i18n->translations['filemanager*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => "@isalcedo/filemanager/messages"
        ];
        parent::init();
    }

    public function getMimeType() {
        $extensions = $result = [];
        foreach ($this->acceptedFilesType as $mimeType) {
            $extensions[] = BaseFileHelper::getExtensionsByMimeType($mimeType);
        }

        foreach ($extensions as $ext) {
            $result = \yii\helpers\ArrayHelper::merge($result, $ext);
        }

        return $result;
    }

}
