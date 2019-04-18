<?php

declare(strict_types=1);

namespace App\Globals\Domain\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190414133632 extends AbstractMigration
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

        $this->addSql('ALTER TABLE amo_group DROP INDEX UNIQ_CD3B01D5C57B52FD, ADD INDEX IDX_CD3B01D5C57B52FD (amo_owner_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $databaseName = getenv('DATABASE_URL_COMMON');
        if ($this->connection->getDatabase() !== (explode('/', $databaseName))[3]) {
            return;
        }

        $this->addSql('ALTER TABLE amo_group DROP INDEX IDX_CD3B01D5C57B52FD, ADD UNIQUE INDEX UNIQ_CD3B01D5C57B52FD (amo_owner_id)');
    }
}
