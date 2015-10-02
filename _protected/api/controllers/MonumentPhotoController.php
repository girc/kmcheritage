<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10/2/2015
 * Time: 7:44 AM
 */

namespace api\controllers;


use common\models\MonumentPhoto;
use yii\helpers\Json;
use yii\rest\Controller;
use Yii;
use yii\web\UploadedFile;

class MonumentPhotoController extends Controller
{
    public $modelClass = 'common\models\MonumentPhoto';

    public function actionIndex(){
        Yii::trace('Recieved data ' . Json::encode(Yii::$app->request->post()));
        return ['status'=>'success','msg'=>'ok','object'=>[]];
    }

    public function actionCreate(){

        $model = new MonumentPhoto();

        /*if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->save()) {
                if (UploadedFile::getInstance($model, 'image')) {
                    // process uploaded image file instance
                    $image = UploadedFile::getInstance($model, 'image');
                    // upload only if valid uploaded file instance found
                    if ($image !== false) {
                        try {
                            $model->saveUploadedImage($image);
                        } catch (Exception $e) {
                            $transaction->rollBack();
                        }
                    }
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                // error in saving model
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
*/
        return ['status'=>'success','msg'=>'ok','object'=>UploadedFile::getInstance($model,'image_file')];
    }
}