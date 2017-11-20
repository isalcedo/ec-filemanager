<?php

	use yii\helpers\Html;
	use yii\widgets\ActiveForm;

	/* @var $this yii\web\View */
	/* @var $model isalcedo\filemanager\models\Folders */
	/* @var $form yii\widgets\ActiveForm */
?>

<div class="folders-form">

	<?php
		$form = ActiveForm::begin();

		echo $form->field($model, 'category')->textInput(['maxlength' => true]);

		echo $form->field($model, 'parent_folder')->dropDownList(
		        $model->getFolders(),
                [
	                'prompt'=>yii::t('filemanager', '-- Select one')
                ]
        );
    ?>
    <p class="help-block">
        <?= yii::t('filemanager', 'This selection is optional. Just usefull if you need a folders tree. Just 1 level is posible.')?>
    </p>
    <?php
		echo $form->field($model, 'path')->textInput(['maxlength' => true]);
	?>
    <div class="form-group">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('filemanager', 'Create') : Yii::t('filemanager', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
