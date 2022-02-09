<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220107131834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE server DROP site_client_to_services_binder_id');
        $this->addSql('ALTER TABLE site_client_to_services_binder CHANGE site_id site_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE server ADD site_client_to_services_binder_id INT NOT NULL');
        $this->addSql('ALTER TABLE site_client_to_services_binder CHANGE site_id site_id INT NOT NULL');
    }
}
