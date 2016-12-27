<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$cancelUrl = ['index'];
if ($model->object !== null)
	$cancelUrl['id'] = $model->object->id;

?>
<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
]); ?>

	<?= $form->field($model, 'title') ?>

	<?php if (!$model->isEmpty()) {
		echo $form->field($model, 'thumbWidth')->staticControl();
	} else {
		echo $form->field($model, 'thumbWidth');
	} ?>

	<?php if (!$model->isEmpty()) {
		echo $form->field($model, 'thumbHeight')->staticControl();
	} else {
		echo $form->field($model, 'thumbHeight');
	} ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('gallery', 'Save'), ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('gallery', 'Cancel'), $cancelUrl, ['class' => 'btn btn-default']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
