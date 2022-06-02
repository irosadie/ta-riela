<?php

namespace app\models\mains\generals;

use yii\behaviors\TimestampBehavior;
use app\models\identities\Users;
use Yii;

/**
 * This is the model class for table "request_details".
 *
 * @property int $id
 * @property int|null $request_id
 * @property string|null $request_number
 * @property string|null $joint_number
 * @property string|null $join_number
 * @property string|null $drawing_number
 * @property string|null $line_number
 * @property int|null $diameter_id
 * @property string|null $diameter_str
 * @property float|null $thickness
 * @property int|null $material_id
 * @property string|null $material_str
 * @property int|null $method_id
 * @property string|null $method_str
 * @property string|null $multiple_welder_id
 * @property string|null $multiple_process_id
 * @property string|null $request_status
 * @property int|null $line_class_id
 * @property string|null $line_class_str
 * @property string|null $remark
 * @property int $status
 * @property int|null $shop_id
 * @property string|null $shop_str
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Users $createdBy
 * @property Users $deletedBy
 * @property Settings $diameter
 * @property Settings $lineClass
 * @property Settings $material
 * @property Settings $method
 * @property Requests $request
 * @property Settings $shop
 * @property Users $updatedBy
 */
class RequestDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request_details';
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
            [['thickness', 'request_number', 'diameter_str', 'method_id', 'line_class_id', 'shop_id', 'material_str', 'joint_number', 'drawing_number', 'line_number'], 'required'],
            [['request_id', 'diameter_id', 'material_id', 'method_id', 'line_class_id', 'status', 'shop_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['thickness'], 'number'],
            [['multiple_welder_id', 'multiple_process_id', 'remark'], 'safe'],
            [['request_number', 'joint_number', 'drawing_number', 'line_number', 'diameter_str', 'method_str', 'line_class_str', 'shop_str'], 'string', 'max' => 100],
            [['request_status'], 'string', 'max' => 5],
            ['created_by', 'default', 'value'=>Yii::$app->user->id],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['diameter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['diameter_id' => 'id']],
            [['line_class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['line_class_id' => 'id']],
            [['material_id'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['material_id' => 'id']],
            [['method_id'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['method_id' => 'id']],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requests::className(), 'targetAttribute' => ['request_id' => 'id']],
            [['shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['shop_id' => 'id']],
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
            'request_id' => 'Request',
            'request_number' => 'Request Number',
            'joint_number' => 'Joint Number',
            'join_number' => 'Join Number',
            'drawing_number' => 'Drawing Number',
            'line_number' => 'Line Number',
            'diameter_id' => 'Diameter',
            'diameter_str' => 'Diameter',
            'thickness' => 'Thickness',
            'material_id' => 'Material',
            'material_str' => 'Material Name',
            'method_id' => 'Method',
            'method_str' => 'Method Name',
            'multiple_welder_id' => 'Multiple Welder',
            'multiple_process_id' => 'Multiple Process',
            'request_status' => 'Request Status',
            'line_class_id' => 'Line Class',
            'line_class_str' => 'Line Class Name',
            'remark' => 'Remark',
            'status' => 'Status',
            'shop_id' => 'Shop',
            'shop_str' => 'Shop Name',
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
     * Gets query for [[Diameter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiameter()
    {
        return $this->hasOne(Settings::className(), ['id' => 'diameter_id']);
    }

    /**
     * Gets query for [[LineClass]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLineClass()
    {
        return $this->hasOne(Settings::className(), ['id' => 'line_class_id']);
    }

    /**
     * Gets query for [[Material]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaterial()
    {
        return $this->hasOne(Settings::className(), ['id' => 'material_id']);
    }

    /**
     * Gets query for [[Method]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMethod()
    {
        return $this->hasOne(Settings::className(), ['id' => 'method_id']);
    }

    /**
     * Gets query for [[Request]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(Requests::className(), ['id' => 'request_id']);
    }

    /**
     * Gets query for [[Shop]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(Settings::className(), ['id' => 'shop_id']);
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
}