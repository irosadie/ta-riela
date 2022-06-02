<?php

namespace app\models\alumnuses\generals;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\models\identities\Users;

/**
 * This is the model class for table "announcement_reads".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $announcement_id
 * @property int|null $read_at
 *
 * @property Announcements $announcement
 * @property Users $user
 */
class AnnouncementReads extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'announcement_reads';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'read_at'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'announcement_id', 'read_at'], 'integer'],
            ['user_id', 'default', 'value' => Yii::$app->user->id],
            [['announcement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Announcements::className(), 'targetAttribute' => ['announcement_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'announcement_id' => Yii::t('app', 'Announcement ID'),
            'read_at' => Yii::t('app', 'Read At'),
        ];
    }

    /**
     * Gets query for [[Announcement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnnouncement()
    {
        return $this->hasOne(Announcements::className(), ['id' => 'announcement_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}