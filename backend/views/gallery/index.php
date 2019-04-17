<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use yii\bootstrap\Dropdown;

use dkhlystov\widgets\NestedTreeGrid;
use cms\gallery\common\models\GallerySection;
use cms\gallery\common\models\GalleryCollection;
use cms\gallery\common\models\GalleryItem;

$title = Yii::t('gallery', 'Galleries');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
    $title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<div class="btn-toolbar" role="toolbar">
    <?= Html::a(Yii::t('gallery', 'Create section'), ['section/create'], ['class' => 'btn btn-primary']) ?>
</div>

<?= NestedTreeGrid::widget([
    'dataProvider' => $dataProvider,
    'initialNode' => $initial,
    'moveAction' => ['move'],
    'showRoots' => true,
    'tableOptions' => ['class' => 'table table-condensed'],
    'pluginOptions' => [
        'onMoveOver' => new JsExpression('function (item, helper, target, position) {
            if (item.data("depth") == 0 || target.data("depth") == 0)
                return false;

            if (item.data("tree") != target.data("tree"))
                return false;

            return position != 1 || target.data("type") != 2;
        }'),
    ],
    'rowOptions' => function ($model, $key, $index, $grid) {
        $options = ['data' => [
            'depth' => $model->depth,
            'tree' => $model->tree,
            'type' => $model->type,
        ]];

        if (!$model->active)
            Html::addCssClass($options, 'warning');

        return $options;
    },
    'columns' => [
        [
            'attribute' => 'title',
            'header' => Yii::t('gallery', 'Title'),
            'format' => 'html',
            'value' => function($model, $key, $index, $column) {
                $result = '';

                if ((($model instanceof GalleryCollection) || ($model instanceof GalleryItem)) && !empty($model->thumb))
                    $result .= Html::img($model->thumb, ['height' => 20]) . ' ';

                $result .= Html::encode($model->title);

                if ($model instanceof GalleryItem) {
                    $result .= ' ' . Html::tag('span', $model->imageCount, ['class' => 'badge']);
                }

                $result .= ' ' . Html::tag('span', Html::encode($model->alias), ['class' => 'label label-primary']);

                return $result;
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'options' => ['style' => 'width: 75px;'],
            'template' => '{update} {delete} {create}',
            'buttons' => [
                'create' => function($url, $model, $key) {
                    if (!($model instanceof GallerySection || $model instanceof GalleryCollection))
                        return '';

                    $title = Yii::t('gallery', 'Create');

                    $toggle = Html::a('<span class="glyphicon glyphicon-plus"></span>', '#', [
                        'title' => $title,
                        'aria-label' => $title,
                        'data-pjax' => 0,
                        'data-toggle' => 'dropdown',
                    ]);
                    
                    $dropdown = Dropdown::widget([
                        'items' => [
                            [
                                'label' => Yii::t('gallery', 'Create section'),
                                'url' => ['collection/create', 'id' => $model->id],
                            ],
                            [
                                'label' => Yii::t('gallery', 'Create gallery'),
                                'url' => ['item/create', 'id' => $model->id],
                            ],
                        ],
                        'options' => ['class' => 'dropdown-menu-right'],
                    ]);

                    return Html::tag('span', $toggle . $dropdown, ['class' => 'dropdown']);
                },
            ],
            'urlCreator' => function($action, $model, $key, $index) {
                $route = $action;

                if ($model instanceof GallerySection) {
                    $route = 'section/' . $route;
                } elseif ($model instanceof GalleryCollection) {
                    $route = 'collection/' . $route;
                } elseif ($model instanceof GalleryItem) {
                    $route = 'item/' . $route;
                }

                return [$route, 'id' => $model->id];
            },
        ],
    ],
]) ?>
