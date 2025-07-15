<?php

namespace oonne\sortablegrid;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;

/**
 * Action for sortable Yii2 GridView widget.
 *
 * For example:
 *
 * ```php
 * public function actions()
 * {
 *    return [
 *       'sort' => [
 *          'class' => SortableGridAction::className(),
 *          'modelName' => Model::className(),
 *          'behaviorName'=>'sort',
 *       ],
 *   ];
 * }
 * ```
 */
class SortableGridAction extends Action
{
    public $modelName;
    public $behaviorName;

    public function run()
    {
        if (!$items = Yii::$app->request->post('items')) {
            throw new BadRequestHttpException('Don\'t received POST param `items`.');
        }
        /** @var \yii\db\ActiveRecord $model */
        $model = new $this->modelName;
        if (!$model->hasMethod('gridSort')) {
            throw new InvalidConfigException(
                "Not found right `SortableGridBehavior` behavior in `{$this->modelName}`."
            );
        }

        if ($this->behaviorName) {
            $model->getBehavior($this->behaviorName)->gridSort(Json::decode($items));
        } else {
            $model->gridSort(Json::decode($items));
        }
    }
}