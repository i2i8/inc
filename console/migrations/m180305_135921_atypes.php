<?php

use yii\db\Migration;

/**
 * Class m180305_135921_atypes
 */
class m180305_135921_atypes extends Migration
{
    const TBL_ATYPES= '{{%atypes}}';
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable(self::TBL_ATYPES, [
            'id'	    => $this->primaryKey(),
            //父序列号
            'atid'      => $this->integer()->notNull()->COMMENT('关联索引'),
            //维修类型
            'types'   	=> $this->string(28)->notNull()->defaultValue('')->COMMENT('维修类型'),
            //录入时间
            'created' 	=> $this->dateTime()->notNull()->defaultValue(0)->COMMENT('录入时间'),
            //调整时间
            'updated' 	=> $this->timestamp()->COMMENT('调整时间'),
        ], $tableOptions);
        $this->createIndex('atidIndex', self::TBL_ATYPES, 'atid', false);
        //依次为：本表的外键名称，本表名称，本表中作为外键的字段，主表名称，映射到主表的字段，RESTRICT(限制)，CASCADE（级联）
        $this->addForeignKey('atid_atypesForeignKey' , self::TBL_ATYPES , 'atid' , '{{%amodel}}' , 'amid' , 'CASCADE', 'CASCADE');
    }
    
    public function safeDown()
    {
        $this->dropTable(self::TBL_ATYPES);
    }
}
