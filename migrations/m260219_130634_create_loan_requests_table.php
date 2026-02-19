<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%loan_requests}}`.
 */
class m260219_130634_create_loan_requests_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%loan_requests}}', [
            'id' => $this->primaryKey(),
            'status' => $this->string()->notNull(),
            'amount' => $this->integer()->notNull(),
            'term' => $this->integer()->notNull(),
            'user_id' => $this->bigInteger()->notNull(),
        ]);

        $this->createIndex('idx-loan_requests-user_id', '{{%loan_requests}}', 'user_id');

        $this->addForeignKey('fk-loan_requests-user_id', '{{%loan_requests}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

        $this->addCheck(
            'chk-loan_requests-amount-positive',
            '{{%loan_requests}}',
            'amount > 0'
        );

        $this->addCheck(
            'chk-loan_requests-term-positive',
            '{{%loan_requests}}',
            'term > 0'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropForeignKey('fk-loan_requests-user_id', '{{%loan_requests}}');
        $this->dropIndex('idx-loan_requests-user_id', '{{%loan_requests}}');

        $this->dropTable('{{%loan_requests}}');
    }
}
