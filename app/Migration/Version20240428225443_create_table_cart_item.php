<?php

declare(strict_types=1);

namespace Online\Store\App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\Migrations\AbstractMigration;

final class Version20240428225443_create_table_cart_item extends AbstractMigration
{
    private const TABLE_NAME = 'cart_item';

    public function getDescription(): string
    {
        return 'Migration for table cart_item';
    }

    /**
     * @throws SchemaException
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable(self::TABLE_NAME);
        $table->addColumn('id', 'string', ['notnull' => true]);
        $table->addColumn('shopping_cart_id', 'string', ['notnull' => true]);
        $table->addColumn('product_id', 'string', ['notnull' => true]);
        $table->addColumn('amount', 'float', ['notnull' => true, 'default' => 0]);
        $table->addColumn('created_at', 'datetimetz_immutable', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('updated_at', 'datetimetz_immutable', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('shopping_cart', ['shopping_cart_id'], ['id']);
        $table->addForeignKeyConstraint('product', ['product_id'], ['id']);
    }

    /**
     * @throws SchemaException
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable(self::TABLE_NAME);
    }
}
