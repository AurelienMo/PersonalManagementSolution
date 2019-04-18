<?php

declare(strict_types=1);

namespace App\Globals\Domain\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190417185910 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $databaseName = getenv('DATABASE_URL_COMMON');
        if ($this->connection->getDatabase() !== (explode('/', $databaseName))[3]) {
            return;
        }

        $this->addSql('ALTER TABLE amo_user DROP FOREIGN KEY FK_7C34BEA94513EA43');
        $this->addSql('ALTER TABLE amo_user ADD CONSTRAINT FK_7C34BEA94513EA43 FOREIGN KEY (amo_group_id) REFERENCES amo_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE amo_group DROP FOREIGN KEY FK_CD3B01D5C57B52FD');
        $this->addSql('ALTER TABLE amo_group ADD CONSTRAINT FK_CD3B01D5C57B52FD FOREIGN KEY (amo_owner_id) REFERENCES amo_user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $databaseName = getenv('DATABASE_URL_COMMON');
        if ($this->connection->getDatabase() !== (explode('/', $databaseName))[3]) {
            return;
        }

        $this->addSql('ALTER TABLE amo_group DROP FOREIGN KEY FK_CD3B01D5C57B52FD');
        $this->addSql('ALTER TABLE amo_group ADD CONSTRAINT FK_CD3B01D5C57B52FD FOREIGN KEY (amo_owner_id) REFERENCES amo_user (id)');
        $this->addSql('ALTER TABLE amo_user DROP FOREIGN KEY FK_7C34BEA94513EA43');
        $this->addSql('ALTER TABLE amo_user ADD CONSTRAINT FK_7C34BEA94513EA43 FOREIGN KEY (amo_group_id) REFERENCES amo_group (id)');
    }
}
