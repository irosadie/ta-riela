<?php

namespace app\models\mains\generals;

use yii\behaviors\TimestampBehavior;
use app\models\identities\Users;
use Yii;

/**
 * This is the model class for table "welders".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $identity_number
 * @property int|null $identity_type_id
 * @property string|null $born_in
 * @property string|null $born_at
 * @property int|null $company_id
 * @property int|null $position_id
 * @property string|null $position_str
 * @property int|null $status
 * @property string|null $image
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Companies $company
 * @property Users $createdBy
 * @property Users $deletedBy
 * @property Users $identityType
 * @property Settings $position
 * @property Users $updatedBy
 */
class Welders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'welders';
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
            [['code', 'name', 'identity_number'], 'required'],
            [['company_id'], 'required', 'message'=>'Company Base cannot be blank.'],
            [['id', 'identity_type_id', 'company_id', 'position_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['born_at'], 'safe'],
            [['code'], 'string', 'max' => 5],
            [['name', 'born_in', 'position_str'], 'string', 'max' => 100],
            [['identity_number'], 'string', 'max' => 20],
            [['image'], 'string', 'max' => 255],
            ['created_by', 'default', 'value'=>Yii::$app->user->id],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['identity_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['identity_type_id' => 'id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['position_id' => 'id']],
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
            'identity_number' => 'Identity Number',
            'identity_type_id' => 'Identity Type ID',
            'born_in' => 'Born In',
            'born_at' => 'Born At',
            'company_id' => 'Company ID',
            'position_id' => 'Position ID',
            'position_str' => 'Position Str',
            'status' => 'Status',
            'image' => 'Image',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
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
     * Gets query for [[IdentityType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdentityType()
    {
        return $this->hasOne(Settings::className(), ['id' => 'identity_type_id']);
    }

    /**
     * Gets query for [[Position]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(Settings::className(), ['id' => 'position_id']);
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