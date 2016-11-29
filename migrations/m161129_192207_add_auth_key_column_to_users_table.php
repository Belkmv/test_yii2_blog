<?php

use yii\db\Migration;

/**
 * Handles adding auth_key to table `users`.
 */
class m161129_192207_add_auth_key_column_to_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('blg_user', 'auth_key','varchar(255)');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('blg_user', 'auth_key');
    }
}
