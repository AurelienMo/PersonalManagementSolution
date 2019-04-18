<?php

declare(strict_types=1);

namespace App\Globals\Domain\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190413080225 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        /** @var string $databaseName */
        $databaseName = getenv('DATABASE_URL_COMMON');
        if ($this->connection->getDatabase() !== (explode('/', $databaseName))[3]) {
            return;
        }

        $this->addSql('CREATE TABLE amo_group (id VARCHAR(255) NOT NULL, amo_owner_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_CD3B01D5C57B52FD (amo_owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE amo_group ADD CONSTRAINT FK_CD3B01D5C57B52FD FOREIGN KEY (amo_owner_id) REFERENCES amo_user (id)');
        $this->addSql('ALTER TABLE amo_user ADD amo_group_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE amo_user ADD CONSTRAINT FK_7C34BEA94513EA43 FOREIGN KEY (amo_group_id) REFERENCES amo_group (id)');
        $this->addSql('CREATE INDEX IDX_7C34BEA94513EA43 ON amo_user (amo_group_id)');
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

        $this->addSql('ALTER TABLE amo_user DROP FOREIGN KEY FK_7C34BEA94513EA43');
        $this->addSql('DROP TABLE amo_group');
        $this->addSql('DROP INDEX IDX_7C34BEA94513EA43 ON amo_user');
        $this->addSql('ALTER TABLE amo_user DROP amo_group_id');
    }
}
