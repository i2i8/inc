<?php

use yii\db\Migration;

/**
 * Class m180325_140938_rprice
 */
class m180325_140938_rprice extends Migration
{
    const TBL_RPRICE= '{{%rprice}}';
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable(self::TBL_RPRICE, [
                'id'	       => $this->primaryKey(),
                //如下两字段设为Null
                'index_mid'   => $this->integer()->notNull()->COMMENT('品牌型号'),
                'index_tid'     => $this->integer()->notNull()->COMMENT('维修类型'),
                'pid'          => $this->integer()->Null()->COMMENT('价格标识'),
                //nowprice
                'nowprice' 	   => $this->integer(5)->Null()->COMMENT('当前价格'),
                //willprice
                'willprice'    => $this->integer(5)->Null()->COMMENT('出库价格'),
                //备注描述
                'remark' 	   => $this->string()->Null()->COMMENT('备注描述'),
                //录入时间
                'created' 	=> $this->dateTime()->notNull()->defaultValue(0)->COMMENT('录入时间'),
                //调整时间
                'updated' 	=> $this->timestamp()->COMMENT('调整时间'),
        ], $tableOptions);
        /**http://www.yiichina.com/topic/6664
         * 外表(%iflow)和本表(%iflow_node)，外表即约束表,本表是被外表约束的。
         * 本表的flow_no与外表的flow_no设置互动关系
         * 总共有这么几种关系：RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL
         * RESTRICT:和 NO ACTION 是一样的， 如果本表中有匹配的记录,则不允许进行update/delete操作
         * CASCADE:外表删除的时候，本表删除，或者外表更新的时候本表更新
         * SET NULL:在外表上update/delete记录时，将本表上匹配记录的列设为null，要注意本表的外键列不能为not null
         * SET DEFAULT: 外表有变更时,本表将外键列设置成一个默认的值 但Innodb不能识别
         * $this->addForeignKey('xxx_user_add_addid' , self::TABLE_NAME , 'addid' , '{{%user}}' , 'addid' , 'CASCADE' , 'RESTRICT');
         * 设置本表的外键名为:xxx_user_add_addid
         * 本表的字段 addid 与 外表addid 为互动关系
         * 当删除外表'{{%user}}'，某个addid的时候，本表'{{%user_add}}',对应的addid列也会删除
         * 当更新外表'{{%user}}'，某个addid的时候，本表'{{%user_add}}',对应的addid列不做改动
         * */
        //依次为：本表的外键名称，本表名称，本表中作为外键的字段，主表名称，映射到主表的字段，RESTRICT(限制)，CASCADE（级联）
        //$this->addForeignKey('noded' , self::TBL_IFLOW_NODE , 'flow_no' , '{{%iflow}}' , 'flow_no' , 'CASCADE' , 'RESTRICT');
        $this->addForeignKey('mid_PidForeignKey' , self::TBL_RPRICE , 'index_mid' , '{{%rmodel}}' , 'mid' , 'CASCADE', 'CASCADE');
        $this->addForeignKey('tid_PidForeignKey' , self::TBL_RPRICE , 'index_tid' , '{{%rtype}}' , 'tid' , 'CASCADE', 'CASCADE');
        // 依次为：索引名称,表名，用哪些字段来创建索引值，若是多字段的话，可以都写里头，第四个true表示是否是唯一性的。
        // 最后一个参数慎用，尤其在有外键约束的情况下，只能false，否则，从表关联字段不能被重复写入，造成数据入库失败。
        $this->createIndex('pidIndex', self::TBL_RPRICE, 'pid', false);
        /**
         * 注：记得给外表的字段addid加索引，否则无法添加为外键。
         * */
    }
    public function safeDown()
    {
        $this->dropTable(self::TBL_RPRICE);
    }
}
