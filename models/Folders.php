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

		/*
			  ["manufacturers_media"]=>
			  array(2) {
			    ["fuyue"]=>
			    array(0) {
			    }
			    ["radwag"]=>
			    string(6) "radwag"
			  }
			  ["nivel_1"]=>
			  array(1) {
			    ["nivel_2"]=>
			    array(1) {
			      ["nivel_3"]=>
			      string(7) "nivel_3"
			    }
			  }
			  ["product_media"]=>
			  array(0) {
			  }
		 */


		private function ToUl($input)
		{
			$list = '<ul>';
			foreach ($input as $key => $value)
			{
				$listItem = '';
				if (is_array($value)) {
					$listItem = $key;
					$list .= "<li id='$key'>";
				} else {
					$list .= "<li id='$value'>";
				}
				$listItem .= (is_array($value) ? $this->ToUl($value) : $value);
				$list .= "$listItem</li>";
			}
			$list .= '</ul>';
			return $list;
		}

		private function getInnerFolders($path)
		{
			$dir_array   = array();
			$directories = scandir($path);
			foreach ($directories as $result)
			{
				if ($result != '.' && $result != '..' && is_dir($path . '/' . $result))
				{
					$innerResults = scandir($path . '/' . $result);

					foreach ($innerResults as $key => $inner)
					{
						if ($inner == '.' || $inner == '..')
						{
							if (!is_dir($path . $result . '/' . $inner))
							{
								unset($innerResults[$key]);
							} else {
								$dir_array[$result] = $this->getInnerFolders($path . '/' . $result);
							}
						}
					}

					if (count($innerResults) > 0)
					{
						$dir_array[$result] = $this->getInnerFolders($path . '/' . $result);
					}
					else
					{
						$dir_array[] = $result;
					}
				}
			}

			return $dir_array;
		}

		public function getFolders()
		{
			$module = Yii::$app->getModule('filemanager');
			if ($module->public_path)
			{
				$path = Yii::getAlias('@webroot' . '/../..' . $module->public_path);
			}
			else
			{
				$path = Yii::getAlias($module->directory);
			}
			$dir_arrays = $this->getInnerFolders($path);

			return $this->ToUl($dir_arrays);
		}

	}
