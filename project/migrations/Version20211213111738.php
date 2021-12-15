<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211213111738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domain_name (id INT AUTO_INCREMENT NOT NULL, server_id INT NOT NULL, url VARCHAR(255) NOT NULL, provider VARCHAR(255) NOT NULL, offer VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, invoiced_price DOUBLE PRECISION DEFAULT NULL, renewal_type VARCHAR(255) NOT NULL, renawal_date DATE NOT NULL, INDEX IDX_F3FF53611844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, provider VARCHAR(255) NOT NULL, offer VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, invoiced_price DOUBLE PRECISION DEFAULT NULL, renewal_type VARCHAR(255) NOT NULL, renawal_date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, server_id INT NOT NULL, client_id INT NOT NULL, online_date DATE NOT NULL, online TINYINT(1) NOT NULL, INDEX IDX_694309E41844E6B7 (server_id), INDEX IDX_694309E419EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE domain_name ADD CONSTRAINT FK_F3FF53611844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E41844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E419EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E419EB6921');
        $this->addSql('ALTER TABLE domain_name DROP FOREIGN KEY FK_F3FF53611844E6B7');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E41844E6B7');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE domain_name');
        $this->addSql('DROP TABLE email');
        $this->addSql('DROP TABLE server');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE user');
    }
}
