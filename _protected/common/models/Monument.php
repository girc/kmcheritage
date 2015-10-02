<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%monument}}".
 *
 * @property string $id
 * @property string $team
 * @property string $date
 * @property string $monument_id
 * @property string $monument_name
 * @property string $monument_type
 * @property string $monument_type_other
 * @property string $artitecture_style
 * @property string $artitecture_style_other
 * @property string $dimension
 * @property integer $no_of_storey
 * @property integer $no_of_similar_heritages
 * @property string $damage_level
 * @property string $damage_level_other
 * @property string $damage_pinnacle
 * @property string $damage_roof
 * @property string $damage_wall
 * @property string $damage_door
 * @property string $damage_wooden_pillar
 * @property string $damage_plinth
 * @property string $damage_parts_other
 * @property integer $damage_description
 * @property integer $ward_no
 * @property string $tole
 * @property string $current_use
 * @property string $latest_maintenance_date
 * @property string $latest_maintenance_by
 * @property boolean $additional_security
 * @property string $additional_detail
 * @property boolean $lost_artifacts
 * @property string $lost_artifacts_detail
 * @property string $monuments_storage
 * @property string $local_contact
 * @property string $ownership
 * @property string $management_committee
 * @property string $builders_name
 * @property string $built_year
 * @property string $cultural_elements
 * @property string $history
 * @property string $religious
 * @property string $social
 * @property string $festival_date
 * @property string $ethnic_group
 * @property string $degradation
 * @property double $latitude
 * @property double $longitude
 * @property string $user_id
 *
 * @property User $user
 * @property Photo[] $photos
 */
class Monument extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%monument}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['no_of_storey', 'no_of_similar_heritages', 'damage_description', 'ward_no', 'user_id'], 'integer'],
            [['additional_security', 'lost_artifacts'], 'boolean'],
            [['additional_detail', 'lost_artifacts_detail', 'cultural_elements', 'history', 'religious', 'social', 'degradation'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['team', 'monument_id', 'monument_name', 'monument_type_other', 'artitecture_style', 'artitecture_style_other', 'dimension', 'damage_level_other', 'damage_parts_other', 'tole', 'latest_maintenance_date', 'latest_maintenance_by', 'monuments_storage', 'local_contact', 'ownership', 'management_committee', 'builders_name', 'built_year', 'ethnic_group'], 'string', 'max' => 100],
            [['monument_type', 'damage_level', 'damage_pinnacle', 'damage_roof', 'damage_wall', 'damage_door', 'damage_wooden_pillar', 'damage_plinth'], 'string', 'max' => 50],
            [['current_use'], 'string', 'max' => 200],
            [['festival_date'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'team' => 'Team',
            'date' => 'Date',
            'monument_id' => 'Monument ID',
            'monument_name' => 'Monument Name',
            'monument_type' => 'Monument Type',
            'monument_type_other' => 'Monument Type Other',
            'artitecture_style' => 'Artitecture Style',
            'artitecture_style_other' => 'Artitecture Style Other',
            'dimension' => 'Dimension',
            'no_of_storey' => 'No Of Storey',
            'no_of_similar_heritages' => 'No Of Similar Heritages',
            'damage_level' => 'Damage Level',
            'damage_level_other' => 'Damage Level Other',
            'damage_pinnacle' => 'Damage Pinnacle',
            'damage_roof' => 'Damage Roof',
            'damage_wall' => 'Damage Wall',
            'damage_door' => 'Damage Door',
            'damage_wooden_pillar' => 'Damage Wooden Pillar',
            'damage_plinth' => 'Damage Plinth',
            'damage_parts_other' => 'Damage Parts Other',
            'damage_description' => 'Damage Description',
            'ward_no' => 'Ward No',
            'tole' => 'Tole',
            'current_use' => 'Current Use',
            'latest_maintenance_date' => 'Latest Maintenance Date',
            'latest_maintenance_by' => 'Latest Maintenance By',
            'additional_security' => 'Additional Security',
            'additional_detail' => 'Additional Detail',
            'lost_artifacts' => 'Lost Artifacts',
            'lost_artifacts_detail' => 'Lost Artifacts Detail',
            'monuments_storage' => 'Monuments Storage',
            'local_contact' => 'Local Contact',
            'ownership' => 'Ownership',
            'management_committee' => 'Management Committee',
            'builders_name' => 'Builders Name',
            'built_year' => 'Built Year',
            'cultural_elements' => 'Cultural Elements',
            'history' => 'History',
            'religious' => 'Religious',
            'social' => 'Social',
            'festival_date' => 'Festival Date',
            'ethnic_group' => 'Ethnic Group',
            'degradation' => 'Degradation',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['monument_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return MonumentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MonumentQuery(get_called_class());
    }
}
