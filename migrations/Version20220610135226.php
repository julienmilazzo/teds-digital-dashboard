<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610135226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad CHANGE offer offer VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE click_and_collect CHANGE offer offer VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE domain_name CHANGE offer offer VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE french_echoppe CHANGE offer offer VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mail CHANGE offer offer VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE server CHANGE offer offer VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE social_network CHANGE offer offer VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad CHANGE offer offer VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE click_and_collect CHANGE offer offer VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE domain_name CHANGE offer offer VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE french_echoppe CHANGE offer offer VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE mail CHANGE offer offer VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE server CHANGE offer offer VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE social_network CHANGE offer offer VARCHAR(255) NOT NULL');
    }
}
