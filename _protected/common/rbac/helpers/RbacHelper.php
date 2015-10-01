<?php
namespace common\rbac\helpers;

use common\models\User;
use common\rbac\models\Role;
use Yii;
use yii\log\Logger;

/**
 * Rbac helper class.
 */
class RbacHelper
{
    /**
     * @param $id
     * @return string
     */
    public static function assignRole($id)
    {
        // make sure there are no leftovers
        Role::deleteAll(['user_id' => $id]);

        $usersCount = User::find()->count();

        $auth = Yii::$app->authManager;

        // this is the first user in our system, give him theCreator role
        switch($usersCount){
            case 1:
                $role = $auth->getRole('theCreator');
                if(!$role)
                    Yii::error('Role theCreator does not exit;');
                break;
            default:
                $role = $auth->getRole('member');
                if(!$role)
                    Yii::error('Role theCreator does not exit;');
                break;
        }

        if($role){
            $auth->assign($role,$id);
        }
        // return assigned role name in case you want to use this method in tests
        return $role?$role->name:null;
    }
}

