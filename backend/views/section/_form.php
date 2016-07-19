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
	'options' => ['class' => 'gallery-form'],
]); ?>

	<?= $form->field($model, 'title') ?>

	<?php if ($model->object !== null) echo $form->field($model, 'alias'); ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('gallery', 'Save'), ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('gallery', 'Cancel'), $cancelUrl, ['class' => 'btn btn-default']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
