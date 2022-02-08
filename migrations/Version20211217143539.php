<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211217143539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD enable TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE domain_name ADD enable TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE server ADD enable TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE site ADD enable TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE server ADD name VARCHAR(255) NOT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP enable');
        $this->addSql('ALTER TABLE domain_name DROP enable');
        $this->addSql('ALTER TABLE server DROP enable');
        $this->addSql('ALTER TABLE site DROP enable');
        $this->addSql('ALTER TABLE server DROP name');

    }
}
