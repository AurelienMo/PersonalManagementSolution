<?php

declare(strict_types=1);

namespace App\Globals\Domain\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190413074643 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        /** @var string $databaseName */
        $databaseName = getenv('DATABASE_URL_COMMON');
        if ($this->connection->getDatabase() !== (explode('/', $databaseName))[3]) {
            return;
        }

        $this->addSql('ALTER TABLE amo_user DROP tokenActivation');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        /** @var string $databaseName */
        $databaseName = getenv('DATABASE_URL_COMMON');
        if ($this->connection->getDatabase() !== (explode('/', $databaseName))[3]) {
            return;
        }

        $this->addSql('ALTER TABLE amo_user ADD tokenActivation VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
