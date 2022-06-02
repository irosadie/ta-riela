<?php

namespace app\models\alumnuses\generals;
use yii\behaviors\TimestampBehavior;
use app\models\identities\Users;
use Yii;

/**
 * This is the model class for table "ppdb_informations".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $school_id
 * @property string|null $school_str
 * @property string|null $school_website_str
 * @property string|null $school_address_str
 * @property string|null $desc
 * @property string|null $content
 * @property string|null $time_range
 * @property string|null $original_link_info
 * @property string|null $thumbnail
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
 * @property Users $updatedBy
 */
class PpdbInformations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ppdb_informations';
    }

    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['school_address_str', 'desc', 'content'], 'string'],
            [['title', 'school_str', 'school_website_str', 'original_link_info', 'thumbnail'], 'string', 'max' => 255],
            [['time_range'], 'string', 'max' => 100],
            [['school_level_str'], 'safe'],
            [['title', 'content', 'school_str', 'school_address_str'],'required'],
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
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'school_id' => Yii::t('app', 'School ID'),
            'school_str' => Yii::t('app', 'School Str'),
            'school_website_str' => Yii::t('app', 'School Website Str'),
            'school_address_str' => Yii::t('app', 'School Address Str'),
            'desc' => Yii::t('app', 'Desc'),
            'content' => Yii::t('app', 'Content'),
            'time_range' => Yii::t('app', 'Time Range'),
            'original_link_info' => Yii::t('app', 'Original Link Info'),
            'thumbnail' => Yii::t('app', 'Thumbnail'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
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
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'updated_by']);
    }
}