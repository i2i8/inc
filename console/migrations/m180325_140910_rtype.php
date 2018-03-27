<?php

use yii\db\Migration;

/**
 * Class m180325_140910_rtype
 */
class m180325_140910_rtype extends Migration
{
    const TBL_RTYPE= '{{%rtype}}';
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable(self::TBL_RTYPE, [
                'id'	    => $this->primaryKey(),
                //父序列号
                'tid'       => $this->integer()->notNull()->COMMENT('关联索引'),
                //维修类型
                'type'   	=> $this->string(28)->notNull()->defaultValue('')->COMMENT('维修类型'),
                //录入时间
                'created' 	=> $this->dateTime()->notNull()->defaultValue(0)->COMMENT('录入时间'),
                //调整时间
                'updated' 	=> $this->timestamp()->COMMENT('调整时间'),
        ], $tableOptions);
        $this->createIndex('tidIndex', self::TBL_RTYPE, 'tid', false);
        //依次为：本表的外键名称，本表名称，本表中作为外键的字段，主表名称，映射到主表的字段，RESTRICT(限制)，CASCADE（级联）
        $this->addForeignKey('tid_RtypeForeignKey' , self::TBL_RTYPE , 'tid' , '{{%rmodel}}' , 'mid' , 'CASCADE', 'CASCADE');
    }
    
    public function safeDown()
    {
        $this->dropTable(self::TBL_RTYPE);
    }
}
