<?php

declare(strict_types=1);

namespace Online\Store\App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\Migrations\AbstractMigration;

final class Version20240428225008_create_table_shopping_cart extends AbstractMigration
{
    private const TABLE_NAME = 'shopping_cart';

    public function getDescription(): string
    {
        return 'Migration for table shopping_cart';
    }

    /**
     * @throws SchemaException
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable(self::TABLE_NAME);
        $table->addColumn('id', 'string', ['notnull' => true]);
        $table->addColumn('user_id', 'string', ['notnull' => true]);
        $table->addColumn('created_at', 'datetimetz_immutable', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('updated_at', 'datetimetz_immutable', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        // TODO: Faltaría crear la tabla de usuarios para relacionarla
        // $table->addForeignKeyConstraint('user', ['user_id'], ['id']);
    }

    /**
     * @throws SchemaException
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable(self::TABLE_NAME);
    }
}
