<?php

namespace app\models\smart\generals;

use Yii;

/**
 * This is the model class for table "m_tahun_ajaran".
 *
 * @property float $tahun_ajaran_id
 * @property string $nama
 * @property float $periode_aktif
 * @property float|null $ajaran_baru
 * @property int|null $aktif_ppdb
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $soft_delete
 * @property int|null $created_by
 *
 * @property HakAkses $createdBy
 * @property MSemester[] $mSemesters
 * @property MasterKelulusan[] $masterKelulusans
 * @property PublicPesertaDidikBaru[] $publicPesertaDidikBarus
 * @property PublicPtkTerdaftar[] $publicPtkTerdaftars
 */
class MTahunAjaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'm_tahun_ajaran';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_smart');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahun_ajaran_id', 'nama', 'periode_aktif', 'tanggal_mulai', 'tanggal_selesai'], 'required'],
            [['tahun_ajaran_id', 'periode_aktif', 'ajaran_baru'], 'number'],
            [['aktif_ppdb', 'soft_delete', 'created_by'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai', 'created_at', 'updated_at'], 'safe'],
            [['nama'], 'string', 'max' => 10],
            [['tahun_ajaran_id'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => HakAkses::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tahun_ajaran_id' => Yii::t('app', 'Tahun Ajaran ID'),
            'nama' => Yii::t('app', 'Nama'),
            'periode_aktif' => Yii::t('app', 'Periode Aktif'),
            'ajaran_baru' => Yii::t('app', 'Ajaran Baru'),
            'aktif_ppdb' => Yii::t('app', 'Aktif Ppdb'),
            'tanggal_mulai' => Yii::t('app', 'Tanggal Mulai'),
            'tanggal_selesai' => Yii::t('app', 'Tanggal Selesai'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'soft_delete' => Yii::t('app', 'Soft Delete'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(HakAkses::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[MSemesters]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMSemesters()
    {
        return $this->hasMany(MSemester::className(), ['tahun_ajaran_id' => 'tahun_ajaran_id']);
    }

    /**
     * Gets query for [[MasterKelulusans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMasterKelulusans()
    {
        return $this->hasMany(MasterKelulusan::className(), ['tahun_ajaran_id' => 'tahun_ajaran_id']);
    }

    /**
     * Gets query for [[PublicPesertaDidikBarus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPesertaDidikBarus()
    {
        return $this->hasMany(PublicPesertaDidikBaru::className(), ['tahun_ajaran_id' => 'tahun_ajaran_id']);
    }

    /**
     * Gets query for [[PublicPtkTerdaftars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicPtkTerdaftars()
    {
        return $this->hasMany(PublicPtkTerdaftar::className(), ['tahun_ajaran_id' => 'tahun_ajaran_id']);
    }
}