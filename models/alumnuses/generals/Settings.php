<?php

namespace app\models\alumnuses\generals;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\models\identities\Users;

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
 * @property Articles[] $articles
 * @property JobVacancies[] $jobVacancies
 * @property QuestionnaireDetails[] $questionnaireDetails
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
            [['name', 'value', 'value_'], 'string'],
            [['value'], 'required', 'on'=>['article-categories', 'mem'], 'message'=>'Code cannot be blank.'],
            [['value_'], 'required', 'on'=>['article-categories', 'num'], 'message'=>'Name cannot be blank.'],
            [['status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['name', 'value'], 'string', 'max' => 255],
            ['created_by', 'default', 'value'=>Yii::$app->user->id],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
            'value_' => Yii::t('app', 'Value'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Update By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }

    /**
     * Gets query for [[Articles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Articles::className(), ['category_id' => 'id']);
    }

    /**
     * Gets query for [[JobVacancies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJobVacancies()
    {
        return $this->hasMany(JobVacancies::className(), ['salary_currency_id' => 'id']);
    }

    /**
     * Gets query for [[QuestionnaireDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaireDetails()
    {
        return $this->hasMany(QuestionnaireDetails::className(), ['group_id' => 'id']);
    }
}