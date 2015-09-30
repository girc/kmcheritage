<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $activationLink string */

?>

Hello <?= Html::encode($user->username) ?>,

Follow this link to activate your account:

<?= Html::a('Please, click here to activate your account.', $activationLink) ?>
