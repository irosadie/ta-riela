<?php

namespace app\models\alumnuses\generals;

use Yii;
use app\models\identities\Users;

/**
 * This is the model class for table "m_regionals".
 *
 * @property int $id
 * @property string|null $region_id
 * @property string|null $name
 * @property string|null $parent_region_id
 * @property int|null $level
 *
 * @property MRegionals[] $mRegionals
 * @property MRegionals $parentRegion
 */
class MRegionals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'm_regionals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level'], 'integer'],
            [['region_id', 'parent_region_id'], 'string', 'max' => 6],
            [['name'], 'string', 'max' => 255],
            [['region_id'], 'unique'],
            [['parent_region_id'], 'exist', 'skipOnError' => true, 'targetClass' => MRegionals::className(), 'targetAttribute' => ['parent_region_id' => 'region_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'region_id' => Yii::t('app', 'Region ID'),
            'name' => Yii::t('app', 'Name'),
            'parent_region_id' => Yii::t('app', 'Parent Region ID'),
            'level' => Yii::t('app', 'Level'),
        ];
    }

    /**
     * Gets query for [[MRegionals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMRegionals()
    {
        return $this->hasMany(MRegionals::className(), ['parent_region_id' => 'region_id']);
    }

    /**
     * Gets query for [[ParentRegion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParentRegion()
    {
        return $this->hasOne(MRegionals::className(), ['region_id' => 'parent_region_id']);
    }
}