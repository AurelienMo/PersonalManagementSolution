<?php

declare(strict_types=1);

namespace App\Domain\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190428071720 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE amo_category_task (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE amo_task (id VARCHAR(255) NOT NULL, amo_category_task_id VARCHAR(255) DEFAULT NULL, amo_owner_id VARCHAR(255) DEFAULT NULL, amo_person_affected_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, display_in_group TINYINT(1) NOT NULL, start_at DATETIME DEFAULT NULL, due_at DATETIME DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_A3D9B3C522E5F31C (amo_category_task_id), INDEX IDX_A3D9B3C5C57B52FD (amo_owner_id), INDEX IDX_A3D9B3C5B5BB947D (amo_person_affected_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE amo_task ADD CONSTRAINT FK_A3D9B3C522E5F31C FOREIGN KEY (amo_category_task_id) REFERENCES amo_category_task (id)');
        $this->addSql('ALTER TABLE amo_task ADD CONSTRAINT FK_A3D9B3C5C57B52FD FOREIGN KEY (amo_owner_id) REFERENCES amo_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE amo_task ADD CONSTRAINT FK_A3D9B3C5B5BB947D FOREIGN KEY (amo_person_affected_id) REFERENCES amo_user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE amo_task DROP FOREIGN KEY FK_A3D9B3C522E5F31C');
        $this->addSql('DROP TABLE amo_category_task');
        $this->addSql('DROP TABLE amo_task');
    }
}
