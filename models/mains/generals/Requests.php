<?php

namespace app\models\mains\generals;

use yii\behaviors\TimestampBehavior;
use app\models\identities\Users;
use Yii;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * This is the model class for table "requests".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $desc
 * @property string|null $report_number
 * @property string|null $date
 * @property string|null $location
 * @property int|null $project_id
 * @property int|null $company_id
 * @property int|null $prepared_designed_id
 * @property string|null $prepared_designed_str
 * @property string|null $prepared_signature
 * @property string|null $prepared_signature_date
 * @property int|null $aproved_company_id
 * @property int|null $aproved_designed_id
 * @property string|null $aproved_str
 * @property string|null $aproved_signature
 * @property string|null $aproved_signature_date
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Companies $aprovedCompany
 * @property Settings $aprovedDesigned
 * @property Users $company
 * @property Users $createdBy
 * @property Users $deletedBy
 * @property Settings $preparedDesigned
 * @property Projects $project
 * @property RequestDetails[] $requestDetails
 * @property Users $updatedBy
 */
class Requests extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'requests';
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
            [['desc'], 'string'],
            [['code', 'name', 'report_number', 'date', 'location'], 'required'],
            [['date', 'prepared_signature_date', 'aproved_signature_date'], 'safe'],
            [['project_id', 'company_id', 'prepared_designed_id', 'aproved_company_id', 'aproved_designed_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['code'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 10],
            [['report_number', 'location'], 'string', 'max' => 100],
            [['prepared_designed_str', 'prepared_signature', 'aproved_str', 'aproved_signature'], 'string', 'max' => 255],
            ['created_by', 'default', 'value'=>Yii::$app->user->id],
            [['aproved_company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['aproved_company_id' => 'id']],
            [['aproved_designed_id'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['aproved_designed_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['prepared_designed_id'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['prepared_designed_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Projects::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['file'],'file', 'skipOnEmpty' => true,'extensions' => 'file'],
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
            'desc' => 'Description',
            'report_number' => 'Report Number',
            'date' => 'Date',
            'location' => 'Location',
            'project_id' => 'Project ',
            'company_id' => 'Company ',
            'prepared_designed_id' => 'Prepared Designed ',
            'prepared_designed_str' => 'Prepared Designed Name',
            'prepared_signature' => 'Prepared Signature',
            'prepared_signature_date' => 'Prepared Signature Date',
            'aproved_company_id' => 'Approved Company ',
            'aproved_designed_id' => 'Approved Designed ID',
            'aproved_str' => 'Approved Name',
            'aproved_signature' => 'Approved Signature',
            'aproved_signature_date' => 'Approved Signature Date',
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
     * Gets query for [[AprovedCompany]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAprovedCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'aproved_company_id']);
    }

    /**
     * Gets query for [[AprovedDesigned]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAprovedDesigned()
    {
        return $this->hasOne(Settings::className(), ['id' => 'aproved_designed_id']);
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Users::className(), ['id' => 'company_id']);
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
     * Gets query for [[PreparedDesigned]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreparedDesigned()
    {
        return $this->hasOne(Settings::className(), ['id' => 'prepared_designed_id']);
    }

    /**
     * Gets query for [[Project]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Projects::className(), ['id' => 'project_id']);
    }

    /**
     * Gets query for [[RequestDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestDetails()
    {
        return $this->hasMany(RequestDetails::className(), ['request_id' => 'id'])->where(['IS', 'deleted_at', new Expression('NULL')]);
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

    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }
}