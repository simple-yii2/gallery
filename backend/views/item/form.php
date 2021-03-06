<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use cms\gallery\backend\assets\GalleryFormAsset;
use dkhlystov\uploadimage\widgets\UploadImage;
use dkhlystov\uploadimage\widgets\UploadImages;

GalleryFormAsset::register($this);

?>
<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableClientValidation' => false,
    'options' => ['class' => 'gallery-form'],
]); ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <?= $form->field($model, 'image')->widget(UploadImage::className(), [
        'thumbAttribute' => 'thumb',
        'options' => ['class' => ''],
    ]) ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'alias') ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'images')->widget(UploadImages::className(), [
        'id' => 'gallery-images',
        'fileKey' => 'file',
        'thumbKey' => 'thumb',
        'data' => function($item) {
            return [
                'id' => $item['id'],
                'title' => $item['title'],
                'description' => $item['description'],
            ];
        },
        'buttons' => [
            'settings' => [
                'label' => '<i class="fa fa-bars"></i>',
                'title' => Yii::t('gallery', 'Settings'),
            ],
        ],
        'options' => [
            'class' => '',
            'data-text-modal' => Yii::t('gallery', 'Image settings'),
            'data-text-title' => Yii::t('gallery', 'Title'),
            'data-text-description' => Yii::t('gallery', 'Description'),
            'data-text-cancel' => Yii::t('gallery', 'Cancel'),
            'data-text-save' => Yii::t('gallery', 'Save'),
        ],
    ]) ?>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <?= Html::submitButton(Yii::t('gallery', 'Save'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('gallery', 'Cancel'), ['gallery/index', 'id' => $model->getObject()->id], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
