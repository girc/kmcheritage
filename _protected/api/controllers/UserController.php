<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/2/2015
 * Time: 2:31 PM
 */

namespace api\controllers;

use yii\rest\ActiveController;
class UserController extends ActiveController{
    public $modelClass = 'common\models\User';
}