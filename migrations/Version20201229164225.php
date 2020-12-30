<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201229164225 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, event_format_id INT DEFAULT NULL, picture_id INT DEFAULT NULL, registered_event_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, link VARCHAR(255) DEFAULT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, price DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, event_is_validated TINYINT(1) DEFAULT NULL, INDEX IDX_3BAE0AA750BCC838 (event_format_id), UNIQUE INDEX UNIQ_3BAE0AA7EE45BDBF (picture_id), INDEX IDX_3BAE0AA71DF0B29B (registered_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_format (id INT AUTO_INCREMENT NOT NULL, format VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, identifier VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expertise (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pathology (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, identifier VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registered_event (id INT AUTO_INCREMENT NOT NULL, is_organizer TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, resource_format_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, link VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_BC91F416A76ED395 (user_id), INDEX IDX_BC91F4167EE0A59A (resource_format_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource_pathology (resource_id INT NOT NULL, pathology_id INT NOT NULL, INDEX IDX_74DE4D0C89329D25 (resource_id), INDEX IDX_74DE4D0CCE86795D (pathology_id), PRIMARY KEY(resource_id, pathology_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource_format (id INT AUTO_INCREMENT NOT NULL, format VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, identifier VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, link VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, service_is_validated TINYINT(1) DEFAULT NULL, INDEX IDX_E19D9AD2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, picture_id INT DEFAULT NULL, registered_event_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, birthday DATETIME DEFAULT NULL, adeli VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, phone INT DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649EE45BDBF (picture_id), INDEX IDX_8D93D6491DF0B29B (registered_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_expertise (user_id INT NOT NULL, expertise_id INT NOT NULL, INDEX IDX_227A526FA76ED395 (user_id), INDEX IDX_227A526F9D5B92F9 (expertise_id), PRIMARY KEY(user_id, expertise_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA750BCC838 FOREIGN KEY (event_format_id) REFERENCES event_format (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA71DF0B29B FOREIGN KEY (registered_event_id) REFERENCES registered_event (id)');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F416A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F4167EE0A59A FOREIGN KEY (resource_format_id) REFERENCES resource_format (id)');
        $this->addSql('ALTER TABLE resource_pathology ADD CONSTRAINT FK_74DE4D0C89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resource_pathology ADD CONSTRAINT FK_74DE4D0CCE86795D FOREIGN KEY (pathology_id) REFERENCES pathology (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491DF0B29B FOREIGN KEY (registered_event_id) REFERENCES registered_event (id)');
        $this->addSql('ALTER TABLE user_expertise ADD CONSTRAINT FK_227A526FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_expertise ADD CONSTRAINT FK_227A526F9D5B92F9 FOREIGN KEY (expertise_id) REFERENCES expertise (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA750BCC838');
        $this->addSql('ALTER TABLE user_expertise DROP FOREIGN KEY FK_227A526F9D5B92F9');
        $this->addSql('ALTER TABLE resource_pathology DROP FOREIGN KEY FK_74DE4D0CCE86795D');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7EE45BDBF');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649EE45BDBF');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA71DF0B29B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491DF0B29B');
        $this->addSql('ALTER TABLE resource_pathology DROP FOREIGN KEY FK_74DE4D0C89329D25');
        $this->addSql('ALTER TABLE resource DROP FOREIGN KEY FK_BC91F4167EE0A59A');
        $this->addSql('ALTER TABLE resource DROP FOREIGN KEY FK_BC91F416A76ED395');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2A76ED395');
        $this->addSql('ALTER TABLE user_expertise DROP FOREIGN KEY FK_227A526FA76ED395');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_format');
        $this->addSql('DROP TABLE expertise');
        $this->addSql('DROP TABLE pathology');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE registered_event');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE resource_pathology');
        $this->addSql('DROP TABLE resource_format');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_expertise');
    }
}
