<?php

namespace app\modules\quanly\models\tanglong;

use Yii;

/**
 * This is the model class for table "kdt_cayxanh".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $OBJECTID
 * @property string|null $loai_cay
 * @property string|null $tinh_trang
 * @property string|null $ma_so
 * @property int|null $nam_trong
 */
class KdtCayxanh extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kdt_cayxanh';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom'], 'string'],
            [['OBJECTID', 'nam_trong'], 'default', 'value' => null],
            [['OBJECTID', 'nam_trong'], 'integer'],
            [['loai_cay', 'tinh_trang', 'ma_so'], 'string', 'max' => 200],
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
            'loai_cay' => Yii::t('app', 'Loai Cay'),
            'tinh_trang' => Yii::t('app', 'Tinh Trang'),
            'ma_so' => Yii::t('app', 'Ma So'),
            'nam_trong' => Yii::t('app', 'Nam Trong'),
        ];
    }
}
