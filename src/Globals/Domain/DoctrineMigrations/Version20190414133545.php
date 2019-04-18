<?php

declare(strict_types=1);

namespace App\Globals\Domain\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190414133545 extends AbstractMigration
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

        $this->addSql('ALTER TABLE amo_group ADD passwordToJoin VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $databaseName = getenv('DATABASE_URL_COMMON');
        if ($this->connection->getDatabase() !== (explode('/', $databaseName))[3]) {
            return;
        }

        $this->addSql('ALTER TABLE amo_group DROP passwordToJoin');
    }
}
