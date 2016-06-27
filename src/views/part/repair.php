<?php
use hipanel\helpers\Url;
use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hipanel\widgets\Box;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Repair parts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'id' => 'dynamic-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => reset($models)->scenario]),
]) ?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => count($models), // the maximum times, an element can be cloned (default 999)
    'min' => count($models), // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => reset($models),
    'formId' => 'dynamic-form',
    'formFields' => [
        'partno',
        'src_id',
        'dst_id',
        'serials',
        'move_type',
        'supplier',
        'order_no',
        'order_no',
        'descr',
        'price',
        'currency',
    ],
]) ?>
<div class="container-items"><!-- widgetContainer -->
    <?php foreach ($models as $i => $model) : ?>
        <?= Html::activeHiddenInput($model, "[$i]id") ?>
        <div class="item">
            <?php Box::begin() ?>
            <div class="row input-row margin-bottom">
                    <div class="col-md-6">
                        <?= $form->field($model, "[$i]partno")->widget(PartnoCombo::className(), [
                            'inputOptions' => [
                                'disabled' => true,
                                'readonly' => true,
                            ],
                        ]) ?>
                        <?= $form->field($model, "[$i]src_id")->widget(SourceCombo::className(), [
                            'inputOptions' => [
                                'disabled' => true,
                                'readonly' => true,
                            ],
                        ]) ?>
                        <?= $form->field($model, "[$i]dst_id")->widget(DestinationCombo::className()) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, "[$i]serial")->textInput(['disabled' => true]) ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($model, "[$i]move_type")->dropDownList($model->filterTypes($moveTypes, $model->scenario)) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, "[$i]supplier")->dropDownList($suppliers) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, "[$i]order_no") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, "[$i]move_descr") ?>
                            </div>
                        </div>
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