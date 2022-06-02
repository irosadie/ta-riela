<?php

namespace app\models\mains\generals;

use Yii;
use app\models\identities\Users;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $value
 * @property string|null $value_
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Users $createdBy
 * @property Users $deletedBy
 * @property RequestDetails[] $requestDetails
 * @property RequestDetails[] $requestDetails0
 * @property RequestDetails[] $requestDetails1
 * @property RequestDetails[] $requestDetails2
 * @property RequestDetails[] $requestDetails3
 * @property Requests[] $requests
 * @property Requests[] $requests0
 * @property Users $updatedBy
 * @property Welders[] $welders
 * @property Welders[] $welders0
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'required', 'on'=>['line-classes', 'welder-processes', 'shoops', 'materials', 'inspection-methods'], 'message'=>'Code cannot be blank.'],
            [['value_'], 'required', 'on'=>['line-classes', 'welder-processes', 'shoops', 'materials', 'inspection-methods'], 'message'=>'Name/ Value cannot be blank.'],

            [['value_'], 'string'],
            [['status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['value'], 'string', 'max' => 255],
            ['created_by', 'default', 'value'=>Yii::$app->user->id],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'value' => 'Value',
            'value_' => 'Value',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[DeletedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'deleted_by']);
    }

    /**
     * Gets query for [[RequestDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestDetails()
    {
        return $this->hasMany(RequestDetails::className(), ['diameter_id' => 'id']);
    }

    /**
     * Gets query for [[RequestDetails0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestDetails0()
    {
        return $this->hasMany(RequestDetails::className(), ['line_class_id' => 'id']);
    }

    /**
     * Gets query for [[RequestDetails1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestDetails1()
    {
        return $this->hasMany(RequestDetails::className(), ['material_id' => 'id']);
    }

    /**
     * Gets query for [[RequestDetails2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestDetails2()
    {
        return $this->hasMany(RequestDetails::className(), ['method_id' => 'id']);
    }

    /**
     * Gets query for [[RequestDetails3]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestDetails3()
    {
        return $this->hasMany(RequestDetails::className(), ['shop_id' => 'id']);
    }

    /**
     * Gets query for [[Requests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Requests::className(), ['aproved_designed_id' => 'id']);
    }

    /**
     * Gets query for [[Requests0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests0()
    {
        return $this->hasMany(Requests::className(), ['prepared_designed_id' => 'id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['role_id' => 'id']);
    }

    /**
     * Gets query for [[Welders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWelders()
    {
        return $this->hasMany(Welders::className(), ['identity_type_id' => 'id']);
    }

    /**
     * Gets query for [[Welders0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWelders0()
    {
        return $this->hasMany(Welders::className(), ['position_id' => 'id']);
    }
}