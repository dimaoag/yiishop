<?php

use yii\db\Migration;

/**
 * Class m181006_113355_rename_user_table
 */
class m181006_113355_rename_user_table extends Migration
{
    public function up()
    {
        $this->renameTable('{{%user}}', '{{%users}}');
    }

    public function down()
    {
        $this->renameTable('{{%users}}', '{{%user}}');
    }
}
