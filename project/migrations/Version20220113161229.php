<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220113161229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ad (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, site_id INT DEFAULT NULL, social_network_id INT DEFAULT NULL, end_date DATE NOT NULL, provider VARCHAR(255) NOT NULL, offer VARCHAR(255) NOT NULL, cost DOUBLE PRECISION DEFAULT NULL, invoiced_price DOUBLE PRECISION DEFAULT NULL, renewal_type VARCHAR(255) NOT NULL, renewal_date DATE NOT NULL, start_date DATE NOT NULL, commentary VARCHAR(10000) NOT NULL, site_client_to_services_binder_id INT DEFAULT NULL, INDEX IDX_77E0ED5819EB6921 (client_id), UNIQUE INDEX UNIQ_77E0ED58F6BD1646 (site_id), INDEX IDX_77E0ED58FA413953 (social_network_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE french_echoppe (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, online_date DATE NOT NULL, enable TINYINT(1) NOT NULL, online TINYINT(1) DEFAULT NULL, INDEX IDX_47CDEE0A19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mail (id INT AUTO_INCREMENT NOT NULL, domain_name_id INT NOT NULL, client_id INT NOT NULL, provider VARCHAR(255) NOT NULL, offer VARCHAR(255) NOT NULL, cost DOUBLE PRECISION DEFAULT NULL, invoiced_price DOUBLE PRECISION DEFAULT NULL, renewal_type VARCHAR(255) NOT NULL, renewal_date DATE NOT NULL, start_date DATE NOT NULL, commentary VARCHAR(10000) NOT NULL, site_client_to_services_binder_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_5126AC4893C085CE (domain_name_id), INDEX IDX_5126AC4819EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE social_network (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, post_by_week INT NOT NULL, which_social_network LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_EFFF522119EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED5819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED58F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED58FA413953 FOREIGN KEY (social_network_id) REFERENCES social_network (id)');
        $this->addSql('ALTER TABLE french_echoppe ADD CONSTRAINT FK_47CDEE0A19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE mail ADD CONSTRAINT FK_5126AC4893C085CE FOREIGN KEY (domain_name_id) REFERENCES domain_name (id)');
        $this->addSql('ALTER TABLE mail ADD CONSTRAINT FK_5126AC4819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE social_network ADD CONSTRAINT FK_EFFF522119EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE click_and_collect ADD commentary VARCHAR(10000) NOT NULL, CHANGE site_client_to_services_binder_id site_client_to_services_binder_id INT NOT NULL');
        $this->addSql('ALTER TABLE domain_name ADD commentary VARCHAR(10000) NOT NULL, CHANGE site_client_to_services_binder_id site_client_to_services_binder_id INT NOT NULL');
        $this->addSql('ALTER TABLE server ADD commentary VARCHAR(10000) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED58FA413953');
        $this->addSql('DROP TABLE ad');
        $this->addSql('DROP TABLE french_echoppe');
        $this->addSql('DROP TABLE mail');
        $this->addSql('DROP TABLE social_network');
        $this->addSql('ALTER TABLE click_and_collect DROP commentary, CHANGE site_client_to_services_binder_id site_client_to_services_binder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE domain_name DROP commentary, CHANGE site_client_to_services_binder_id site_client_to_services_binder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE server DROP commentary');
    }
}
