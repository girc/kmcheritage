<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/29/2015
 * Time: 12:43 PM
 */

namespace api\controllers;


use api\models\SignupForm;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\rest\Controller;
use Yii;

class UserAccountController extends Controller
{
    public function actionIndex(){
        return [];
    }
    //------------------------------------------------------------------------------------------------//
    // SIGN UP / ACCOUNT ACTIVATION
    //------------------------------------------------------------------------------------------------//

    /**
     * Signs up the user.
     * If user need to activate his account via email, we will display him
     * message with instructions and send him account activation email
     * ( with link containing account activation token ). If activation is not
     * necessary, we will log him in right after sign up process is complete.
     * NOTE: You can decide whether or not activation is necessary,
     * @see config/params.php
     *
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {
        // get setting value for 'Registration Needs Activation'
        $rna = Yii::$app->params['rna'];

        // if 'rna' value is 'true', we instantiate SignupForm in 'rna' scenario
        $model = $rna ? new SignupForm(['scenario' => 'rna']) : new SignupForm();

        // collect and validate user data
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            // try to save user data in database
            if ($user = $model->signup())
            {
                // if user is active he will be logged in automatically ( this will be first user )
                if ($user->status === User::STATUS_ACTIVE)
                {
                    if (Yii::$app->getUser()->login($user))
                    {
                        return  ['status'=>'success','msg'=>'Welcome!',"user"=>["id"=>$user->id,"username"=>$user->username,"email"=>$user->email,"status"=>$user->status]];
                    }
                }
                // activation is needed, use signupWithActivation()
                else
                {
                    $this->signupWithActivation($model, $user);
                    return  ['status'=>'success','msg'=>'Please check your email to verify this account!',"user"=>["id"=>$user->id,"username"=>$user->username,"email"=>$user->email,"status"=>$user->status]];
                }
            }
            // user could not be saved in database
            else
            {
                // display error message to user
                //Yii::$app->session->setFlash('error',"We couldn't sign you up, please contact us.");
                return ['ERROR: User Could Not be Saved!'];

                // log this error, so we can debug possible problem easier.
                Yii::error('Signup failed!
                    User '.Html::encode($user->username).' could not sign up.
                    Possible causes: something strange happened while saving user in database.');

                return  ['status'=>'error','msg'=>'Sorry! Could not create account'];
            }
        }else{
            return  ['status'=>'error','msg'=>$model->getErrors()];
        }
    }

    /**
     * Sign up user with activation.
     * User will have to activate his account using activation link that we will
     * send him via email.
     *
     * @param $model
     * @param $user
     */
    private function signupWithActivation($model, $user)
    {
        // try to send account activation email
        if ($model->sendAccountActivationEmail($user))
        {
            /*Yii::$app->session->setFlash('success',
                'Hello '.Html::encode($user->username).'.
                To be able to log in, you need to confirm your registration.
                Please check your email, we have sent you a message.');*/
            return  ['status'=>'success','msg'=>'Check your email'];
        }
        // email could not be sent
        else
        {
            // log this error, so we can debug possible problem easier.
            Yii::error('Signup failed!
                User '.Html::encode($user->username).' could not sign up.
                Possible causes: verification email could not be sent.');

            // display error message to user
            /*Yii::$app->session->setFlash('error',
                "We couldn't send you account activation email, please contact us.");*/
            return  ['status'=>'error','msg'=>'Email could not be sent'];
        }
    }
}