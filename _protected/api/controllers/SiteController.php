<?php
namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Site controller.
 * It is responsible for displaying static pages, and logging users in and out.
 */
class SiteController extends Controller
{
    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','error'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * Declares external actions for the controller.
     *
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays the index (home) page.
     * Use it in case your home page contains static content.
     *
     * @return string
     */
    public function actionIndex()
    {
        $apiList=[
          [
              'end_point'=>'api/users',
              'title'=>'List Users',
              'link'=>Url::to(['/users']),
              'method'=>'GET',
              'description'=>'Lists the users registered to the application',
          ]
        ];
        return $this->render('index',['apiList'=>$apiList]);
    }

}
