<?php


namespace app\modules\api\controllers;


use app\modules\api\models\Article;
use app\modules\api\models\ArticleHasTranslations;
use Faker\Factory;
use Yii;
use yii\rest\ActiveController;
use yii\rest\Controller;

class ArticleController extends ApiController
{
    public function actionOne(){
        return Article::find()->where(['slug' => Yii::$app->request->get('slug')])->with('articleHasAnchors')->one();
    }

    public function actionAll(){
        return ArticleHasTranslations::find()->all();
    }
}