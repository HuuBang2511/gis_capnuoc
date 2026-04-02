<?php

namespace app\modules\quanly\models\tanglong;

use Yii;

/**
 * This is the model class for table "kdt_tram_bien_ap".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $OBJECTID
 * @property string|null $ma_so
 * @property string|null $loai_mba
 * @property int|null $nam
 * @property string|null $tinh_trang
 */
class KdtTramBienAp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kdt_tram_bien_ap';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom'], 'string'],
            [['OBJECTID', 'nam'], 'default', 'value' => null],
            [['OBJECTID', 'nam'], 'integer'],
            [['ma_so', 'loai_mba', 'tinh_trang'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'geom' => Yii::t('app', 'Geom'),
            'OBJECTID' => Yii::t('app', 'Objectid'),
            'ma_so' => Yii::t('app', 'Ma So'),
            'loai_mba' => Yii::t('app', 'Loai Mba'),
            'nam' => Yii::t('app', 'Nam'),
            'tinh_trang' => Yii::t('app', 'Tinh Trang'),
        ];
    }
}
