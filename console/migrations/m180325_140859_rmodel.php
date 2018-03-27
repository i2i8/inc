<?php

use yii\db\Migration;

/**
 * Class m180325_140859_rmodel
 */
class m180325_140859_rmodel extends Migration
{
    const TBL_RMODEL= '{{%rmodel}}';
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable(self::TBL_RMODEL, [
                'id'	    => $this->primaryKey(),
                //父序列号，必须为Null，因为第一次save的时候，自增id还没生成，amid是第二次save前，由前一次save主入库后获取到的
                'mid'      => $this->integer()->Null()->COMMENT('关联索引'),
                //品牌型号
                'name'   	=> $this->string(28)->notNull()->defaultValue('')->COMMENT('品牌型号'),
                //录入时间
                'created' 	=> $this->dateTime()->notNull()->defaultValue(0)->COMMENT('录入时间'),
                //调整时间
                'updated' 	=> $this->timestamp()->COMMENT('调整时间'),
        ], $tableOptions);
        // 依次为：索引名称,表名，用哪些字段来创建索引值，若是多字段的话，可以都写里头，第四个true表示是否是唯一性的。
        // 最后一个参数慎用，尤其在有外键约束的情况下，只能false，否则，从表关联字段不能被重复写入，造成数据入库失败。
        $this->createIndex('midIndex', self::TBL_RMODEL, 'mid', false);
    }
    
    public function safeDown()
    {
        $this->dropTable(self::TBL_RMODEL);
    }
}
