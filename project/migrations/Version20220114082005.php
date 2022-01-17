<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220114082005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE french_echoppe ADD provider VARCHAR(255) NOT NULL, ADD offer VARCHAR(255) NOT NULL, ADD cost DOUBLE PRECISION DEFAULT NULL, ADD invoiced_price DOUBLE PRECISION DEFAULT NULL, ADD renewal_type VARCHAR(255) NOT NULL, ADD renewal_date DATE NOT NULL, ADD start_date DATE NOT NULL, ADD commentary VARCHAR(10000) NOT NULL, ADD site_client_to_services_binder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE social_network ADD provider VARCHAR(255) NOT NULL, ADD offer VARCHAR(255) NOT NULL, ADD cost DOUBLE PRECISION DEFAULT NULL, ADD invoiced_price DOUBLE PRECISION DEFAULT NULL, ADD renewal_type VARCHAR(255) NOT NULL, ADD renewal_date DATE NOT NULL, ADD start_date DATE NOT NULL, ADD commentary VARCHAR(10000) NOT NULL, ADD site_client_to_services_binder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ad ADD enable TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE mail ADD enable TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE social_network ADD enable TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE french_echoppe DROP provider, DROP offer, DROP cost, DROP invoiced_price, DROP renewal_type, DROP renewal_date, DROP start_date, DROP commentary, DROP site_client_to_services_binder_id');
        $this->addSql('ALTER TABLE social_network DROP provider, DROP offer, DROP cost, DROP invoiced_price, DROP renewal_type, DROP renewal_date, DROP start_date, DROP commentary, DROP site_client_to_services_binder_id');
        $this->addSql('ALTER TABLE ad DROP enable');
        $this->addSql('ALTER TABLE mail DROP enable');
        $this->addSql('ALTER TABLE social_network DROP enable');
    }
}
