<?php

namespace app\models\alumnuses\generals;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\models\identities\Users;

/**
 * This is the model class for table "announcements".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $desc
 * @property string|null $content
 * @property string|null $read_more_uri
 * @property string|null $thumbnail
 * @property string|null $file_json
 * @property string|null $year_of_graduates
 * @property string|null $schools
 * @property string|null $privacy
 * @property int $status
 * @property int $published_at
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property AnnouncementReads[] $announcementReads
 * @property Users $createdBy
 * @property Users $deletedBy
 * @property Users $updatedBy
 */
class Announcements extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'announcements';
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
            [['desc', 'content', 'file_json'], 'string'],
            [['title', 'content'], 'required'],
            [['privacy', 'schools', 'year_of_graduates', 'thumbnail', 'slug'], 'safe'],
            ['slug', 'required', 'when'=>function($model){ return $model->privacy=='public';}],
            ['slug', 'unique', 'when'=>function($model){ return $model->privacy=='public';}],
            ['slug', function(){ 
                if($this->privacy=='auth'){
                    return $this->slug = NULL;
                }
            }],
            [['status', 'published_at', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            ['created_by', 'default', 'value'=>Yii::$app->user->id],
            [['title', 'slug', 'read_more_uri', 'thumbnail'], 'string', 'max' => 255],
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
            'slug' => Yii::t('app', 'Slug'),
            'desc' => Yii::t('app', 'Desc'),
            'content' => Yii::t('app', 'Content'),
            'read_more_uri' => Yii::t('app', 'Read More Uri'),
            'thumbnail' => Yii::t('app', 'Thumbnail'),
            'file_json' => Yii::t('app', 'File Json'),
            'year_of_graduates' => Yii::t('app', 'Year Of Graduates'),
            'schools' => Yii::t('app', 'Schools'),
            'privacy' => Yii::t('app', 'Privacy'),
            'status' => Yii::t('app', 'Status'),
            'published_at' => Yii::t('app', 'Published At'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }

    /**
     * Gets query for [[AnnouncementReads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnnouncementReads()
    {
        return $this->hasMany(AnnouncementReads::className(), ['announcement_id' => 'id']);
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