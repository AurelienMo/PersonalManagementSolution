<?php

declare(strict_types=1);

namespace App\Domain\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190716201603 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE amo_task DROP FOREIGN KEY FK_A3D9B3C522E5F31C');
        $this->addSql('CREATE TABLE user_group (user_id VARCHAR(255) NOT NULL, group_id VARCHAR(255) NOT NULL, INDEX IDX_8F02BF9DA76ED395 (user_id), INDEX IDX_8F02BF9DFE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DA76ED395 FOREIGN KEY (user_id) REFERENCES amo_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DFE54D947 FOREIGN KEY (group_id) REFERENCES amo_group (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE amo_category_task');
        $this->addSql('DROP TABLE amo_task');
        $this->addSql('ALTER TABLE amo_user DROP FOREIGN KEY FK_7C34BEA94513EA43');
        $this->addSql('DROP INDEX IDX_7C34BEA94513EA43 ON amo_user');
        $this->addSql('ALTER TABLE amo_user DROP amo_group_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE amo_category_task (id VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE amo_task (id VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, amo_category_task_id VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, amo_owner_id VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, amo_person_affected_id VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, display_in_group TINYINT(1) NOT NULL, start_at DATETIME DEFAULT NULL, due_at DATETIME DEFAULT NULL, status VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_A3D9B3C5B5BB947D (amo_person_affected_id), INDEX IDX_A3D9B3C522E5F31C (amo_category_task_id), INDEX IDX_A3D9B3C5C57B52FD (amo_owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE amo_task ADD CONSTRAINT FK_A3D9B3C522E5F31C FOREIGN KEY (amo_category_task_id) REFERENCES amo_category_task (id)');
        $this->addSql('ALTER TABLE amo_task ADD CONSTRAINT FK_A3D9B3C5B5BB947D FOREIGN KEY (amo_person_affected_id) REFERENCES amo_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE amo_task ADD CONSTRAINT FK_A3D9B3C5C57B52FD FOREIGN KEY (amo_owner_id) REFERENCES amo_user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('ALTER TABLE amo_user ADD amo_group_id VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE amo_user ADD CONSTRAINT FK_7C34BEA94513EA43 FOREIGN KEY (amo_group_id) REFERENCES amo_group (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_7C34BEA94513EA43 ON amo_user (amo_group_id)');
    }
}
