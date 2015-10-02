<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10/2/2015
 * Time: 7:44 AM
 */

namespace api\controllers;


use yii\helpers\Json;
use yii\rest\Controller;
use Yii;
class MonumentPhotoController extends Controller
{
    public $modelClass = 'common\models\MonumentPhoto';

    public function actionIndex(){
        Yii::trace('Recieved data ' . Json::encode(Yii::$app->request->post()));
        return ['status'=>'success','msg'=>'ok','object'=>[]];
    }

    public function actionCreate(){

        return ['status'=>'success','msg'=>'ok','object'=>Yii::$app->request->post()];
    }
}