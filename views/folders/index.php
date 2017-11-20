<?php

	use yii\helpers\Html;
	use kartik\grid\GridView;

	/* @var $this yii\web\View */
	/* @var $dataProvider yii\data\ActiveDataProvider */
	/* @var $model isalcedo\filemanager\models\Folders */

	$this->title                   = Yii::t('filemanager', 'Media Folder');
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header clearfix">
    <div class="pull-left">
        <h1><?php echo Html::encode($this->title); ?></h1>
    </div>
    <div class="pull-right">
		<?php echo Html::a(Yii::t('filemanager', 'Create Folder'), ['create'], ['class' => 'btn btn-success']); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
		<?php
			echo GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel'  => $model,
				'export'       => false,
				'columns'      => [
					['class' => 'yii\grid\SerialColumn'],
					'category',
					[
						'class'     => 'kartik\grid\DataColumn',
						'attribute' => 'path',
						'value'     => function ($model) {
							$path   = $model->path;
							$module = Yii::$app->controller->module;

							if ($model->storage == 'local')
							{
								if ($module->public_path)
								{
									if (!$model['parent_folder'])
									{
										$path = $module->public_path . $model['path'];
									}
									else
                                    {
                                        $path = $module->public_path . $model['parent_folder'] . '/' . $model['path'];
                                    }
								}
								else
								{
									if (!$model['parent_folder'])
									{
									    $path = Yii::getAlias('@webroot') . '/' . $model['path'];
									}
									else
									{
										$path = Yii::getAlias('@webroot') . '/' . $model['parent_folder'] . '/' . $model['path'];
									}
								}
							}

							return $path;
						}
					],
					['class' => 'yii\grid\ActionColumn'],
				],
			]);
		?>
    </div>
</div>
