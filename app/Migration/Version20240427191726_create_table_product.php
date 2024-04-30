<?php

declare(strict_types=1);

namespace Online\Store\App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\Migrations\AbstractMigration;

final class Version20240427191726_create_table_product extends AbstractMigration
{
    private const TABLE_NAME = 'product';

    public function getDescription(): string
    {
        return 'Migration for table product';
    }

    /**
     * @throws SchemaException
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable(self::TABLE_NAME);
        $table->addColumn('id', 'string', ['notnull' => true]);
        $table->addColumn('name', 'string', ['notnull' => true]);
        $table->addColumn('price', 'float', ['notnull' => true]);
        $table->addColumn('type', 'integer', ['notnull' => true]);
        $table->addColumn('amount', 'float', ['notnull' => true, 'default' => 0]);
        $table->addColumn('created_at', 'datetimetz_immutable', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('updated_at', 'datetimetz_immutable', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
    }

    /**
     * @throws SchemaException
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable(self::TABLE_NAME);
    }
}
