<?php

namespace app\modules\quanly\models\tanglong;

use Yii;

/**
 * This is the model class for table "kdt_thuadat".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $OBJECTID
 * @property float|null $Shape_Leng
 * @property string|null $loai_dat
 * @property string|null $so_thua
 * @property string|null $tinhhinh_xd
 * @property string|null $chu_ho
 * @property float|null $Shape_Area
 */
class KdtThuadat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kdt_thuadat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom'], 'string'],
            [['OBJECTID'], 'default', 'value' => null],
            [['OBJECTID'], 'integer'],
            [['Shape_Leng', 'Shape_Area'], 'number'],
            [['loai_dat', 'so_thua', 'tinhhinh_xd', 'chu_ho'], 'string', 'max' => 200],
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
            'Shape_Leng' => Yii::t('app', 'Shape Leng'),
            'loai_dat' => Yii::t('app', 'Loai Dat'),
            'so_thua' => Yii::t('app', 'So Thua'),
            'tinhhinh_xd' => Yii::t('app', 'Tinhhinh Xd'),
            'chu_ho' => Yii::t('app', 'Chu Ho'),
            'Shape_Area' => Yii::t('app', 'Shape Area'),
        ];
    }
}
