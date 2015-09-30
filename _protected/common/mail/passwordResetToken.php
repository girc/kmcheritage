<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $passwordResetLink string */
?>

Hello <?= Html::encode($user->username) ?>,

Follow this link below to reset your password:

<?= Html::a('Please, click here to reset your password.', $passwordResetLink) ?>
