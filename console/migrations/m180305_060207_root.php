<?php

use yii\db\Migration;

/**
 * Class m180305_060207_root
 */
class m180305_060207_root extends Migration
{
    const TBL_ROOT = '{{%root}}';
    
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable(self::TBL_ROOT, [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique()->COMMENT('索引'),
            'auth_key' => $this->string(32)->Null(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique()->COMMENT('索引'),
            'email' => $this->string()->notNull()->unique()->COMMENT('索引'),
            
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }
    
    public function safeDown()
    {
        $this->dropTable(self::TBL_ROOT);
        
    }
}
