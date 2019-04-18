<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use dkhlystov\uploadimage\widgets\UploadImage;

$cancelUrl = ['gallery/index'];
if ($model->object !== null)
    $cancelUrl['id'] = $model->getObject()->id;

?>
<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableClientValidation' => false,
]); ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <?= $form->field($model, 'image')->widget(UploadImage::className(), [
        'thumbAttribute' => 'thumb',
        'options' => ['class' => ''],
    ]) ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'alias') ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <?= Html::submitButton(Yii::t('gallery', 'Save'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('gallery', 'Cancel'), $cancelUrl, ['class' => 'btn btn-default']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
