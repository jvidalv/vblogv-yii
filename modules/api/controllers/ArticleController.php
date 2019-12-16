<?php


namespace app\modules\api\controllers;


use app\modules\api\models\Article;
use app\modules\api\models\ArticleHasTranslations;
use Yii;

class ArticleController extends ApiController
{
    /**
     * @return Article|array|\yii\db\ActiveRecord|null
     */
    public function actionOne()
    {
        return Article::find()->alias('a')
            ->where(['slug' => Yii::$app->request->get('slug')])
            ->with('translations')->with('articleHasAnchors')->with('articleHasTags')->with('category')
            ->one();
    }

    /**
     * @return Article[]|ArticleHasTranslations[]|array|\yii\db\ActiveRecord[]
     */
    public function actionRelated()
    {
        $aArticle = Article::findOne(['slug' => Yii::$app->request->get('slug')]);
        $continuation = $aArticle->continuation ? $aArticle->continuation->id : null;
        $articles = Article::find()->alias('a')
            ->where(['language_id' => $aArticle->language_id])
            ->andWhere(['or', ['!=', 'id', $aArticle->id], ['id' => $continuation]])
            ->andWhere(['category_id' => $aArticle->category_id])
            ->with('translations')->with('articleHasAnchors')->with('articleHasTags')->with('category')
            ->orderBy(['date' => 'DESC'])
            ->limit(Yii::$app->request->get('limit'))
            ->all();
        return ArticleHasTranslations::find()->where(['in', 'article_' . $aArticle->language_id, array_map(function ($obj) {
            return $obj->id;}, $articles)])->all();
    }

    /**
     * @return ArticleHasTranslations[]|array|\yii\db\ActiveRecord[]
     */
    public function actionAll()
    {
        return ArticleHasTranslations::find()->all();
    }
}