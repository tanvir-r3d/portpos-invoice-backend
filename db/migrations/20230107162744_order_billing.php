<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class OrderBilling extends AbstractMigration
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
        $table = $this->table('order_billings');
        $table->addColumn('order_id', 'integer', [
            'null' => true,
            'signed' => FALSE,
        ])
            ->addColumn('name', 'string')
            ->addColumn('email', 'string')
            ->addColumn('phone', 'string')
            ->addColumn('address_street', 'string')
            ->addColumn('address_city', 'string')
            ->addColumn('address_state', 'string')
            ->addColumn('address_zipcode', 'string')
            ->addColumn('address_country', 'string')
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('order_id', 'orders', 'id',
                [
                    'delete' => 'CASCADE'
                ])
            ->create();
    }
}
