<?php

namespace app\modules\quanly\models\tanglong;

use Yii;

/**
 * This is the model class for table "kdt_giaothong".
 *
 * @property int $id
 * @property string|null $geom
 * @property string|null $osm_id
 * @property int|null $code
 * @property string|null $fclass
 * @property string|null $name
 * @property string|null $ref
 * @property string|null $oneway
 * @property int|null $maxspeed
 * @property int|null $layer
 * @property string|null $bridge
 * @property string|null $tunnel
 * @property int|null $OBJECTID
 * @property int|null $FID_Polygo
 * @property string|null $Entity
 * @property string|null $Handle
 * @property string|null $Layer_2
 * @property int|null $LyrFrzn
 * @property int|null $LyrLock
 * @property int|null $LyrOn
 * @property int|null $LyrVPFrzn
 * @property string|null $LyrHandle
 * @property int|null $Color
 * @property int|null $EntColor
 * @property int|null $LyrColor
 * @property int|null $BlkColor
 * @property string|null $Linetype
 * @property string|null $EntLinetyp
 * @property string|null $LyrLnType
 * @property string|null $BlkLinetyp
 * @property float|null $Elevation
 * @property float|null $Thickness
 * @property int|null $LineWt
 * @property int|null $EntLineWt
 * @property int|null $LyrLineWt
 * @property int|null $BlkLineWt
 * @property string|null $RefName
 * @property float|null $LTScale
 * @property float|null $ExtX
 * @property float|null $ExtY
 * @property float|null $ExtZ
 * @property string|null $DocName
 * @property string|null $DocPath
 * @property string|null $DocType
 * @property string|null $DocVer
 * @property float|null $Shape_Leng
 */
class KdtGiaothong extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kdt_giaothong';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom'], 'string'],
            [['code', 'maxspeed', 'layer', 'OBJECTID', 'FID_Polygo', 'LyrFrzn', 'LyrLock', 'LyrOn', 'LyrVPFrzn', 'Color', 'EntColor', 'LyrColor', 'BlkColor', 'LineWt', 'EntLineWt', 'LyrLineWt', 'BlkLineWt'], 'default', 'value' => null],
            [['code', 'maxspeed', 'layer', 'OBJECTID', 'FID_Polygo', 'LyrFrzn', 'LyrLock', 'LyrOn', 'LyrVPFrzn', 'Color', 'EntColor', 'LyrColor', 'BlkColor', 'LineWt', 'EntLineWt', 'LyrLineWt', 'BlkLineWt'], 'integer'],
            [['Elevation', 'Thickness', 'LTScale', 'ExtX', 'ExtY', 'ExtZ', 'Shape_Leng'], 'number'],
            [['osm_id'], 'string', 'max' => 12],
            [['fclass'], 'string', 'max' => 28],
            [['name'], 'string', 'max' => 100],
            [['ref'], 'string', 'max' => 20],
            [['oneway', 'bridge', 'tunnel'], 'string', 'max' => 1],
            [['Entity', 'Handle', 'LyrHandle', 'DocVer'], 'string', 'max' => 16],
            [['Layer_2', 'Linetype', 'EntLinetyp', 'LyrLnType', 'BlkLinetyp', 'RefName', 'DocName', 'DocPath'], 'string', 'max' => 254],
            [['DocType'], 'string', 'max' => 32],
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
            'osm_id' => Yii::t('app', 'Osm ID'),
            'code' => Yii::t('app', 'Code'),
            'fclass' => Yii::t('app', 'Fclass'),
            'name' => Yii::t('app', 'Name'),
            'ref' => Yii::t('app', 'Ref'),
            'oneway' => Yii::t('app', 'Oneway'),
            'maxspeed' => Yii::t('app', 'Maxspeed'),
            'layer' => Yii::t('app', 'Layer'),
            'bridge' => Yii::t('app', 'Bridge'),
            'tunnel' => Yii::t('app', 'Tunnel'),
            'OBJECTID' => Yii::t('app', 'Objectid'),
            'FID_Polygo' => Yii::t('app', 'Fid Polygo'),
            'Entity' => Yii::t('app', 'Entity'),
            'Handle' => Yii::t('app', 'Handle'),
            'Layer_2' => Yii::t('app', 'Layer 2'),
            'LyrFrzn' => Yii::t('app', 'Lyr Frzn'),
            'LyrLock' => Yii::t('app', 'Lyr Lock'),
            'LyrOn' => Yii::t('app', 'Lyr On'),
            'LyrVPFrzn' => Yii::t('app', 'Lyr Vp Frzn'),
            'LyrHandle' => Yii::t('app', 'Lyr Handle'),
            'Color' => Yii::t('app', 'Color'),
            'EntColor' => Yii::t('app', 'Ent Color'),
            'LyrColor' => Yii::t('app', 'Lyr Color'),
            'BlkColor' => Yii::t('app', 'Blk Color'),
            'Linetype' => Yii::t('app', 'Linetype'),
            'EntLinetyp' => Yii::t('app', 'Ent Linetyp'),
            'LyrLnType' => Yii::t('app', 'Lyr Ln Type'),
            'BlkLinetyp' => Yii::t('app', 'Blk Linetyp'),
            'Elevation' => Yii::t('app', 'Elevation'),
            'Thickness' => Yii::t('app', 'Thickness'),
            'LineWt' => Yii::t('app', 'Line Wt'),
            'EntLineWt' => Yii::t('app', 'Ent Line Wt'),
            'LyrLineWt' => Yii::t('app', 'Lyr Line Wt'),
            'BlkLineWt' => Yii::t('app', 'Blk Line Wt'),
            'RefName' => Yii::t('app', 'Ref Name'),
            'LTScale' => Yii::t('app', 'Lt Scale'),
            'ExtX' => Yii::t('app', 'Ext X'),
            'ExtY' => Yii::t('app', 'Ext Y'),
            'ExtZ' => Yii::t('app', 'Ext Z'),
            'DocName' => Yii::t('app', 'Doc Name'),
            'DocPath' => Yii::t('app', 'Doc Path'),
            'DocType' => Yii::t('app', 'Doc Type'),
            'DocVer' => Yii::t('app', 'Doc Ver'),
            'Shape_Leng' => Yii::t('app', 'Shape Leng'),
        ];
    }
}
