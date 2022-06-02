<?php

namespace app\models\mains\generals;
use yii\behaviors\TimestampBehavior;
use app\models\identities\Users;
use Yii;

/**
 * This is the model class for table "request_detail_perfilm".
 *
 * @property int $id
 * @property int|null $request_detail_id
 * @property int|null $film_number
 * @property string|null $location_range
 * @property int|null $welder_id
 * @property int|null $welder_process_id
 * @property int $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 * @property string|null $result
 *
 * @property Users $createdBy
 * @property Users $deletedBy
 * @property RequestDetails $requestDetail
 * @property Users $updatedBy
 * @property Welders $welder
 * @property Settings $welderProcess
 */
class RequestDetailPerfilm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request_detail_perfilm';
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
            [['request_detail_id', 'film_number', 'welder_id', 'welder_process_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['result'], 'string'],
            [['location_range'], 'string', 'max' => 10],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['request_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => RequestDetails::className(), 'targetAttribute' => ['request_detail_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['welder_id'], 'exist', 'skipOnError' => true, 'targetClass' => Welders::className(), 'targetAttribute' => ['welder_id' => 'id']],
            [['welder_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['welder_process_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'request_detail_id' => 'Request Detail ID',
            'film_number' => 'Film Number',
            'location_range' => 'Location Range',
            'welder_id' => 'Welder ID',
            'welder_process_id' => 'Welder Process ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'result' => 'Result',
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
     * Gets query for [[RequestDetail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestDetail()
    {
        return $this->hasOne(RequestDetails::className(), ['id' => 'request_detail_id']);
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
     * Gets query for [[Welder]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWelder()
    {
        return $this->hasOne(Welders::className(), ['id' => 'welder_id']);
    }

    /**
     * Gets query for [[WelderProcess]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWelderProcess()
    {
        return $this->hasOne(Settings::className(), ['id' => 'welder_process_id']);
    }
}