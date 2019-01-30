<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model isalcedo\filemanager\models\Folders */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs("
	    function convertToSlug(str)
        {
            str = str.replace(/^\s+|\s+$/g, '');
            str = str.toLowerCase();
            
            var from = \"ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;\";
            var to   = \"aaaaaeeeeeiiiiooooouuuunc------\";
            for (var i=0, l=from.length ; i<l ; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
            }

            str = str.replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
            
            return str;
        }
        
        $('#folders-path').change(function() {
            let value = convertToSlug($(this).val());
            $(this).val(value);
        });
	");
?>

<div class="folders-form">

    <?php
    $form = ActiveForm::begin();

    echo $form->field($model, 'category')->textInput(['maxlength' => true]);

    /*
    echo $form->field($model, 'parent_folder')->dropDownList(
            $model->getFolders(),
            [
                'prompt'=>yii::t('filemanager', '-- Select one')
            ]
    );
    */
    ?>
    <?= $form->field($model, 'parent_folder')->hiddenInput(); ?>
    <div id="folderstree">
        <?= $model->getFolders(); ?>
    </div>
    <p class="help-block">
        <?= yii::t('filemanager', 'This selection is optional. Just usefull if you need a folders tree.') ?>
    </p>
    <?php
    echo $form->field($model, 'path')->textInput(['maxlength' => true]);
    ?>
    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('filemanager', 'Create') : Yii::t('filemanager',
            'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
