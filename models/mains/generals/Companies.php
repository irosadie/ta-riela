<?php

namespace app\models\mains\generals;

use yii\behaviors\TimestampBehavior;
use Yii;
use app\models\identities\Users;

/**
 * This is the model class for table "companies".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $desc
 * @property string|null $address
 * @property string|null $npwp
 * @property string|null $telp
 * @property string|null $fax
 * @property string|null $email
 * @property int|null $pic_in_user_id
 * @property string|null $pic_in_user_str
 * @property string|null $pic_in_user_phone
 * @property int|null $pic_ex_user_id
 * @property string|null $pic_ex_user_str
 * @property string|null $pic_ex_user_phone
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Users $createdBy
 * @property Users $deletedBy
 * @property Users $picExUser
 * @property Users $picInUser
 * @property ProjectUsers[] $projectUsers
 * @property Projects[] $projects
 * @property Requests[] $requests
 * @property Users $updatedBy
 * @property Welders[] $welders
 */
class Companies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'companies';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['desc', 'address'], 'string'],
            [['pic_in_user_id', 'pic_ex_user_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['code'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 50],
            [['npwp'], 'string', 'max' => 20],
            [['telp', 'fax', 'pic_in_user_phone', 'pic_ex_user_phone'], 'string', 'max' => 15],
            [['email', 'pic_in_user_str', 'pic_ex_user_str'], 'string', 'max' => 255],
            ['created_by', 'default', 'value'=>Yii::$app->user->id],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['pic_ex_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['pic_ex_user_id' => 'id']],
            [['pic_in_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['pic_in_user_id' => 'id']],
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
            'code' => 'Code',
            'name' => 'Name',
            'desc' => 'Desc',
            'address' => 'Address',
            'npwp' => 'Npwp',
            'telp' => 'Telp',
            'fax' => 'Fax',
            'email' => 'Email',
            'pic_in_user_id' => 'Pic In User ID',
            'pic_in_user_str' => 'Pic In User Str',
            'pic_in_user_phone' => 'Pic In User Phone',
            'pic_ex_user_id' => 'Pic Ex User ID',
            'pic_ex_user_str' => 'Pic Ex User Str',
            'pic_ex_user_phone' => 'Pic Ex User Phone',
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
     * Gets query for [[PicExUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPicExUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'pic_ex_user_id']);
    }

    /**
     * Gets query for [[PicInUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPicInUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'pic_in_user_id']);
    }

    /**
     * Gets query for [[ProjectUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjectUsers()
    {
        return $this->hasMany(ProjectUsers::className(), ['company_id' => 'id']);
    }

    /**
     * Gets query for [[Projects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Projects::className(), ['company_id' => 'id']);
    }

    /**
     * Gets query for [[Requests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Requests::className(), ['aproved_company_id' => 'id']);
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
     * Gets query for [[Welders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWelders()
    {
        return $this->hasMany(Welders::className(), ['company_id' => 'id']);
    }
}