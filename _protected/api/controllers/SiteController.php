<?php
namespace api\controllers;


use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Site controller.
 * It is responsible for displaying static pages, and logging users in and out.
 */
class SiteController extends Controller
{
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

    //------------------------------------------------------------------------------------------------//
    // STATIC PAGES
    //------------------------------------------------------------------------------------------------//

    /**
     * Displays the index (home) page.
     * Use it in case your home page contains static content.
     *
     * @return string
     */
    public function actionIndex()
    {
        $apiList = [
            [
                'end_point' => 'api/users',
                'title' => 'List Users',
                'link' => Url::to(['/users']),
                'method' => 'GET',
                'parameters' => Json::encode([]),
                'description' => 'Lists the users registered to the application',
            ],
            [
                'end_point' => 'api/user-account/signin',
                'title' => 'Signs up user',
                'link' => Url::to(['/user-account/signin']),
                'method' => 'POST',
                'parameters' => Json::encode(["SignupForm" => ["username" => "testusername", "password" => "mysecretpassword", "email" => "my@email.com"]]),
                'description' => 'Registers new user to the system',
            ],
            [
                'end_point' => 'api/user-account/login',
                'title' => 'logs up user',
                'link' => Url::to(['/user-account/login']),
                'method' => 'POST',
                'parameters' => Json::encode(["LoginForm" => ["email" => "my@email.com", "password" => "mysecretpassword"]]),
                'description' => 'Logs in registered user to the system',
            ]
        ];
        return $this->render('index', ['apiList' => $apiList]);
    }
} //API Site Controller
