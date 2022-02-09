<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220107150609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE click_and_collect ADD server_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE click_and_collect ADD CONSTRAINT FK_FE410AF51844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('CREATE INDEX IDX_FE410AF51844E6B7 ON click_and_collect (server_id)');
        $this->addSql('ALTER TABLE domain_name ADD server_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE domain_name ADD CONSTRAINT FK_F3FF53611844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('CREATE INDEX IDX_F3FF53611844E6B7 ON domain_name (server_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE click_and_collect DROP FOREIGN KEY FK_FE410AF51844E6B7');
        $this->addSql('DROP INDEX IDX_FE410AF51844E6B7 ON click_and_collect');
        $this->addSql('ALTER TABLE click_and_collect DROP server_id');
        $this->addSql('ALTER TABLE domain_name DROP FOREIGN KEY FK_F3FF53611844E6B7');
        $this->addSql('DROP INDEX IDX_F3FF53611844E6B7 ON domain_name');
        $this->addSql('ALTER TABLE domain_name DROP server_id');
    }
}