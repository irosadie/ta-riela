<?php

namespace app\models\alumnuses\generals;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\models\identities\Users;

/**
 * This is the model class for table "job_vacancies".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $company_id
 * @property string|null $salary_range
 * @property int|null $salary_currency_id
 * @property string|null $salary_currency_str
 * @property string|null $skill_needed
 * @property string|null $company_str
 * @property int|null $submition_deadline
 * @property string|null $requirements
 * @property string|null $desc
 * @property int|null $published_at
 * @property string|null $schools
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
 * @property Settings $salaryCurrency
 * @property Users $updatedBy
 */
class JobVacancies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'job_vacancies';
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
            [['company_id', 'salary_currency_id', 'published_at', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['skill_needed', 'requirements'], 'string'],
            [['thumbnail', 'schools'], 'safe'],
            [['title', 'skill_needed', 'requirements', 'company_str', 'company_address_str'], 'required'],
            ['submition_deadline', function(){
                return $this->submition_deadline = strtotime($this->submition_deadline);
            }],
            [['title', 'salary_range', 'company_str', 'desc'], 'string', 'max' => 255],
            [['salary_currency_str'], 'string', 'max' => 8],
            ['created_by', 'default', 'value'=>Yii::$app->user->id],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['salary_currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['salary_currency_id' => 'id']],
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
            'company_id' => Yii::t('app', 'Company ID'),
            'salary_range' => Yii::t('app', 'Salary Range'),
            'salary_currency_id' => Yii::t('app', 'Salary Currency ID'),
            'salary_currency_str' => Yii::t('app', 'Salary Currency Str'),
            'skill_needed' => Yii::t('app', 'Skill Needed'),
            'company_str' => Yii::t('app', 'Company Str'),
            'submition_deadline' => Yii::t('app', 'Submition Deadline'),
            'requirements' => Yii::t('app', 'Requirements'),
            'desc' => Yii::t('app', 'Desc'),
            'published_at' => Yii::t('app', 'Published At'),
            'schools' => Yii::t('app', 'Schools'),
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
     * Gets query for [[SalaryCurrency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSalaryCurrency()
    {
        return $this->hasOne(Settings::className(), ['id' => 'salary_currency_id']);
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