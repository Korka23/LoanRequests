<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%loan_requests}}`.
 */
class m260219_130634_create_requests_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%requests}}', [
            'id' => $this->primaryKey(),
            'status' => $this->string()->notNull(),
            'amount' => $this->integer()->notNull(),
            'term' => $this->integer()->notNull(),
            'user_id' => $this->bigInteger()->notNull(),
        ]);

        $this->createIndex('idx-requests-user_id', '{{%requests}}', 'user_id');

        $this->addForeignKey('fk-requests-user_id', '{{%requests}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

        $this->addCheck(
            'chk-requests-amount-positive',
            '{{%requests}}',
            'amount > 0'
        );

        $this->addCheck(
            'chk-requests-term-positive',
            '{{%requests}}',
            'term > 0'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropForeignKey('fk-requests-user_id', '{{%requests}}');
        $this->dropIndex('idx-requests-user_id', '{{%requests}}');

        $this->dropTable('{{%requests}}');
    }
}
