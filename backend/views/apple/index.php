<?php

use yii\helpers\Html;
use backend\models\Apple;

/* @var $this yii\web\View */
/* @var $apples array */
?>
<?= common\widgets\Alert::widget() ?>

<h1>Яблоки</h1>

<p>
    <?= Html::a('Сгенерировать яблоки', ['apple/generate'], ['class' => 'btn btn-outline-secondary']) ?>
</p>

<div class="apple-container">
    <?php foreach ($apples as $apple) : ?>
        <?php $is_spoilt = $apple->fell_at && time() - $apple->fell_at > Apple::HOURS_TO_SPOIL * 60 * 60; ?>
        <div>
            Цвет: <?= $apple->color ?> <br>
            Дата появления: <?= date('d/m/Y H:i:s', $apple->created_at) ?> <br>
            Дата падения: <?= $apple->fell_at ? date('d/m/Y H:i:s', $apple->fell_at) : 'нету' ?> <br>
            Съедено: <?= $apple->eat ?>% <br>
            Состояние:
            <?php if (!$apple->fell_at) : ?>
                висит на дереве
            <?php elseif ($is_spoilt) : ?>
                гнилое яблоко
            <?php else : ?>
                упало/лежит на земле
            <?php endif; ?>

            <div class="mt-1">
                <?php if (!$apple->fell_at) : ?>
                    <?= Html::a('Упасть', ['apple/fall', 'id' => $apple->id], ['class' => 'btn btn-primary']) ?>
                <?php elseif (!$is_spoilt) : ?>
                    <?= Html::beginForm(['apple/eat', 'id' => $apple->id], 'POST', ['class' => 'form-inline']) ?>
                    <div class="form-group mr-2">
                        <?= Html::input('number', 'percent', null, ['min' => 0, 'max' => 100 - $apple->eat, 'required' => 'required', 'class' => 'form-control mr-1']) ?>
                        %
                    </div>
                    <?= Html::submitButton('Съесть', ['class' => 'btn btn-primary']) ?>
                    <?= Html::endForm() ?>
                <?php else : ?>
                    <?= Html::a('Удалить', ['apple/delete', 'id' => $apple->id], ['class' => 'btn btn-primary']) ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
