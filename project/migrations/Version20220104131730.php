<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220104131730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain_name ADD start_date DATE, CHANGE price cost DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE server ADD start_date DATE, CHANGE price cost DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain_name DROP start_date, CHANGE cost price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE server DROP start_date, CHANGE cost price DOUBLE PRECISION DEFAULT NULL');
    }
}
