<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211217083742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE server_site (server_id INT NOT NULL, site_id INT NOT NULL, INDEX IDX_E2C378041844E6B7 (server_id), INDEX IDX_E2C37804F6BD1646 (site_id), PRIMARY KEY(server_id, site_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE server_site ADD CONSTRAINT FK_E2C378041844E6B7 FOREIGN KEY (server_id) REFERENCES server (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE server_site ADD CONSTRAINT FK_E2C37804F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE email');
        $this->addSql('ALTER TABLE domain_name DROP FOREIGN KEY FK_F3FF53611844E6B7');
        $this->addSql('DROP INDEX IDX_F3FF53611844E6B7 ON domain_name');
        $this->addSql('ALTER TABLE domain_name ADD site_id INT DEFAULT NULL, DROP server_id');
        $this->addSql('ALTER TABLE domain_name ADD CONSTRAINT FK_F3FF5361F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('CREATE INDEX IDX_F3FF5361F6BD1646 ON domain_name (site_id)');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E41844E6B7');
        $this->addSql('DROP INDEX IDX_694309E41844E6B7 ON site');
        $this->addSql('ALTER TABLE site DROP server_id');
        $this->addSql('ALTER TABLE site ADD name VARCHAR(255) NOT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE server_site');
        $this->addSql('ALTER TABLE domain_name DROP FOREIGN KEY FK_F3FF5361F6BD1646');
        $this->addSql('DROP INDEX IDX_F3FF5361F6BD1646 ON domain_name');
        $this->addSql('ALTER TABLE domain_name ADD server_id INT NOT NULL, DROP site_id');
        $this->addSql('ALTER TABLE domain_name ADD CONSTRAINT FK_F3FF53611844E6B7 FOREIGN KEY (server_id) REFERENCES server (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F3FF53611844E6B7 ON domain_name (server_id)');
        $this->addSql('ALTER TABLE site ADD server_id INT NOT NULL');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E41844E6B7 FOREIGN KEY (server_id) REFERENCES server (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_694309E41844E6B7 ON site (server_id)');
        $this->addSql('ALTER TABLE site DROP name');

    }
}
