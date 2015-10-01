<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10/2/2015
 * Time: 1:17 AM
 */

namespace api\controllers;


use yii\rest\Controller;

class MonumentController extends Controller
{
    public $modelClass = 'common\models\User';

    public function actionIndex(){
        return ['status'=>'success','msg'=>'ok','object'=>[]];
    }

    public function actionCreate(){
        return ['status'=>'success','msg'=>'ok','object'=>[]];
    }
}