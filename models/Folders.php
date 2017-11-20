<?php

	namespace isalcedo\filemanager\models;

	use Yii;

	/**
	 * This is the model class for table "folders".
	 *
	 * @property integer $folder_id
	 * @property string  $category
	 * @property string  $path
	 * @property string  $storage
	 *
	 * @property Files[] $files
	 */
	class Folders extends \yii\db\ActiveRecord
	{

		/**
		 * @inheritdoc
		 */
		public static function tableName()
		{
			return 'folders';
		}

		/**
		 * @inheritdoc
		 */
		public function rules()
		{
			return [
				[['category', 'path', 'storage'], 'required'],
				[['category'], 'string', 'max' => 64],
				[['path', 'parent_folder'], 'string', 'max' => 255],
				[['storage'], 'string', 'max' => 32],
				[['path'], 'unique']
			];
		}

		/**
		 * @inheritdoc
		 */
		public function attributeLabels()
		{
			return [
				'folder_id'     => Yii::t('filemanager', 'Folder ID'),
				'category'      => Yii::t('filemanager', 'Category'),
				'path'          => Yii::t('filemanager', 'Path'),
				'parent_folder' => Yii::t('filemanager', 'Main folder'),
				'storage'       => Yii::t('filemanager', 'Storage'),
			];
		}

		/**
		 * @return \yii\db\ActiveQuery
		 */
		public function getFiles()
		{
			return $this->hasMany(Files::className(), ['folder_id' => 'folder_id']);
		}

		public function getFolders()
		{
			$module = Yii::$app->getModule('filemanager');
			if ($module->public_path)
			{
				$dir = Yii::getAlias('@webroot'.'/../..'.$module->public_path);
			}
			else
			{
				$dir = Yii::getAlias($module->directory);
			}
			$results = scandir($dir);
			$dir_arrays = array();
			foreach ($results as $result) {
				if ($result != '.' && $result != '..' && is_dir($dir . '/' . $result)) {
					$dir_arrays[$result] = $result;
				}
			}

			return $dir_arrays;

		}

	}
