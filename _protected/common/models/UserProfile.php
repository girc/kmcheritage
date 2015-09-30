<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_profile".
 *
 * @property string $id
 * @property string $user_id
 * @property string $full_name
 * @property string $gender
 * @property string $date_of_birth
 * @property string $contact_no
 * @property string $address
 * @property string $work
 * @property string $avatar
 * @property string $created_time
 * @property string $updated_time
 *
 * @property User $user
 */
class UserProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'full_name', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['date_of_birth'], 'safe'],
            [['full_name', 'contact_no', 'address', 'work', 'avatar'], 'string', 'max' => 255],
            [['gender'], 'string', 'max' => 10],
            [['avatar'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'full_name' => Yii::t('app', 'Full Name'),
            'gender' => Yii::t('app', 'Gender'),
            'date_of_birth' => Yii::t('app', 'Date Of Birth'),
            'contact_no' => Yii::t('app', 'Contact No'),
            'address' => Yii::t('app', 'Address'),
            'work' => Yii::t('app', 'Work'),
            'avatar' => Yii::t('app', 'Avatar'),
            'created_at' => Yii::t('app', 'Created Time'),
            'updated_at' => Yii::t('app', 'Updated Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
