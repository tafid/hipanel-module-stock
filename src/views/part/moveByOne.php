<?php

use hipanel\helpers\Url;
use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hipanel\widgets\Box;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$scenario = $this->context->action->scenario;
$this->title = Yii::t('app', 'Move by one');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin([
    'id' => 'dynamic-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => reset($models)->isNewRecord ? 'create' : 'update']),
]) ?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => 99, // the maximum times, an element can be cloned (default 999)
    'min' => 1, // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => reset($models),
    'formId' => 'dynamic-form',
    'formFields' => [
        'id',
        'move_type',
        'src_id',
        'dst_id',
        'descr',
        'remotehands',
        'remote_ticket',
        'hm_ticket',
    ],
]) ?>
<div class="container-items">
    <?php foreach ($models as $i => $model) : ?>
        <?php
        // necessary for update action.
        $model->scenario = $scenario;
        if ($scenario === 'update' || $scenario === 'move') {
            echo Html::activeHiddenInput($model, "[$i]id");
        }
        ?>
        <div class="item">
            <?php Box::begin() ?>
            <div class="row">
                <div class="col-md-12">
                    <?php if ($scenario === 'bulk-move') : ?>
                        <?php if (is_array($model->parts) || $model->parts instanceof Traversable) : ?>
                            <?php foreach ($model->parts as $part_id => $part) : ?>
                                <?php $ids[] = $part_id; ?>
                                <div><?= $part['partno'] ?> : <?= $part['serial'] ?></div>
                            <?php endforeach; ?>
                            <?= Html::activeHiddenInput($model, "[$i]ids", ['value' => $ids]); ?>
                        <?php endif; ?>
                    <?php else : ?>
                        <?= Html::activeHiddenInput($model, "[$i]ids", ['value' => $model->id]); ?>
                        <?= $form->field($model, "[$i]partno")->widget(PartnoCombo::className(), [
                            'inputOptions' => [
                                'disabled' => true,
                                'readonly' => true,
                            ],
                        ]) ?>
                        <?= $form->field($model, "[$i]serial")->textInput(['readonly' => true, 'disabled' => 'disabled']) ?>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, "[$i]src_id")->widget(SourceCombo::className(), [
                                'inputOptions' => [
                                    'disabled' => true,
                                    'readonly' => true,
                                ],
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, "[$i]dst_id")->widget(DestinationCombo::className()) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, "[$i]type")->dropDownList($types) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, "[$i]remotehands")->dropDownList($remotehands) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, "[$i]remote_ticket")->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, "[$i]hm_ticket")->textInput() ?>
                        </div>
                    </div>

                    <?= $form->field($model, "[$i]move_descr")->textarea() ?>
                </div>
            </div>
            <?php Box::end() ?>
        </div>
    <?php endforeach; ?>
</div>

<?php DynamicFormWidget::end() ?>
<?php Box::begin(['options' => ['class' => 'box-solid']]) ?>
<div class="row">
    <div class="col-md-12 no">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('app', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>
<?php Box::end() ?>
<?php ActiveForm::end() ?>
