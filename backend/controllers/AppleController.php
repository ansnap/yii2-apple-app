<?php

namespace backend\controllers;

use backend\models\Apple;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class AppleController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'eat' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $apples = Apple::find()->all();

        return $this->render('index', ['apples' => $apples]);
    }

    public function actionGenerate()
    {
        $apples_to_gen = rand(1, 4);
        $colors = ['green', 'red', 'yellow'];

        for ($i = 0; $i < $apples_to_gen; $i++) {
            $apple = new Apple();
            $apple->color = $colors[array_rand($colors)];
            $apple->created_at = rand(1618723395, time());
            $apple->save();
        }

        return $this->redirect(['index']);
    }

    public function actionFall($id)
    {
        $apple = Apple::findOne($id);

        if (!$apple) {
            throw new NotFoundHttpException('Яблоко не найдено');
        }

        $apple->fell_at = time();
        $apple->save();

        return $this->redirect(['index']);
    }

    public function actionEat($id)
    {
        $apple = Apple::findOne($id);

        if (!$apple) {
            throw new NotFoundHttpException('Яблоко не найдено');
        }

        $is_spoilt = $apple->fell_at && time() - $apple->fell_at > Apple::HOURS_TO_SPOIL * 60 * 60;

        if (!$apple->fell_at || $is_spoilt) {
            Yii::$app->session->setFlash('danger', 'Когда яблоко висит на дереве или испорчено - съесть не получится');
            return $this->redirect(['index']);
        }

        $percent = Yii::$app->request->post()['percent'];

        if ($apple->eat + $percent >= 100) {
            $apple->delete();
        } else {
            $apple->eat = $apple->eat + $percent;
            $apple->save();
        }

        return $this->redirect(['index']);
    }

    public function actionDelete($id)
    {
        $apple = Apple::findOne($id);

        if (!$apple) {
            throw new NotFoundHttpException('Яблоко не найдено');
        }

        $apple->delete();

        return $this->redirect(['index']);
    }
}
