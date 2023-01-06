<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class JwtToken extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('jwt_tokens');
        $table->addColumn('uuid', 'text')
            ->addColumn('user_id', 'integer')
            ->addColumn('token_title', 'string')
            ->addColumn('token', 'text')
            ->addColumn('restrictions', 'json')
            ->addColumn('permissions', 'json')
            ->addColumn('last_used_at', 'timestamp')
            ->addColumn('expires_at', 'timestamp')
            ->addColumn('refreshed_at', 'timestamp')
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
