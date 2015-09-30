<?php
namespace api\controllers;

use api\models\LoginForm;
use api\models\SignupForm;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\rest\Controller;
use Yii;

/**
 * Site controller.
 * It is responsible for displaying static pages, and logging users in and out.
 */
class SiteController extends Controller
{
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
        return $apiList;
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

    //------------------------------------------------------------------------------------------------//
    // LOG IN / LOG OUT
    //------------------------------------------------------------------------------------------------//

    /**
     * Logs in the user if his account is activated,
     * if not, displays appropriate message.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        // get setting value for 'Login With Email'
        $lwe = Yii::$app->params['lwe'];

        // if 'lwe' value is 'true' we instantiate LoginForm in 'lwe' scenario
        $model = $lwe ? new LoginForm(['scenario' => 'lwe']) : new LoginForm();
        if ($lwe)$model->email=$model->username;

        if(!$model->load(Yii::$app->request->post())){
            return ['status'=>'error','msg'=>'Credential data not received'];
        }

        // now we can try to log in the user
        if ($model->load(Yii::$app->request->post()) && $model->login())
        {
            $user = $model->getUser();
            return ['status'=>'success','msg'=>'Login Successful', 'user'=>['id'=>$user->id,'username'=>$user->username,'email'=>$user->email]];//$this->goBack();
        }
        // user couldn't be logged in, because he has not activated his account
        elseif($model->notActivated())
        {
            // if his account is not activated, he will have to activate it first
            return ['status'=>'error','msg'=>'You have to activate your account first. Please check your email.'];
        }
        // account is activated, but some other errors have happened
        else
        {
            return ['status'=>'error','msg'=>'Oops! Something went wrong.','error'=>[]];
        }
    }

    /**
     * Logs out the user.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
