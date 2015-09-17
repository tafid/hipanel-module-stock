<?php
namespace hipanel\modules\stock\controllers;

use hipanel\base\CrudController;
use hipanel\models\Ref;
use hipanel\modules\stock\models\Model;
use Yii;

class ModelController extends CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'hipanel\actions\IndexAction',
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
            ],
            'create' => [
                'class' => 'hipanel\actions\SmartCreateAction',
                'success' => Yii::t('app', 'Model was created'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
            ],
            'update' => [
                'class' => 'hipanel\actions\SmartUpdateAction',
                'success' => Yii::t('app', 'Model was updated'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
            ],
            'validate-form' => [
                'class' => 'hipanel\actions\ValidateFormAction',
            ],
        ];
    }

    public function actionSubform()
    {
        $subFormName = Yii::$app->request->post('subFormName');
        $itemNumber = Yii::$app->request->post('itemNumber');
        if ($subFormName) {
            $validFormNames = $this->getCustomType();
            if (in_array($subFormName, $validFormNames)) {
                return $this->renderAjax('_' . $subFormName, ['model' => new Model(), 'i' => $itemNumber]);
            } else
                return '';
        } else
            return '';

    }

    public function getTypes()
    {
        return Ref::getList('type,model');
    }

    public function getDcs()
    {
        return Ref::getList('type,dc');
    }

    public function getBrands()
    {
        return Ref::getList('type,brand');
    }

    public function getCustomType()
    {
        return ['server', 'chassis', 'motherboard', 'ram', 'hdd', 'cpu'];
    }
}