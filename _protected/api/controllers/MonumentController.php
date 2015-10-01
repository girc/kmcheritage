<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10/2/2015
 * Time: 1:17 AM
 */

namespace api\controllers;

use Yii;
use yii\helpers\Json;
use yii\log\Logger;
use yii\rest\Controller;

class MonumentController extends Controller
{
    public $modelClass = 'common\models\User';

    public function actionIndex(){

        Yii::trace('Recieved data ' . Json::encode(Yii::$app->request->post()));
        return ['status'=>'success','msg'=>'ok','object'=>[]];
    }

    public function actionCreate(){
        return ['status'=>'success','msg'=>'ok','object'=>[]];
    }
}