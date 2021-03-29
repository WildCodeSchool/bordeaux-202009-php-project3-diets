<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210329150026 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dietetician_specialization (dietetician_id INT NOT NULL, specialization_id INT NOT NULL, INDEX IDX_31E9463A158DBF52 (dietetician_id), INDEX IDX_31E9463AFA846217 (specialization_id), PRIMARY KEY(dietetician_id, specialization_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dietetician_specialization ADD CONSTRAINT FK_31E9463A158DBF52 FOREIGN KEY (dietetician_id) REFERENCES dietetician (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dietetician_specialization ADD CONSTRAINT FK_31E9463AFA846217 FOREIGN KEY (specialization_id) REFERENCES specialization (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE specialization_dietetician');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE specialization_dietetician (specialization_id INT NOT NULL, dietetician_id INT NOT NULL, INDEX IDX_11D4722A158DBF52 (dietetician_id), INDEX IDX_11D4722AFA846217 (specialization_id), PRIMARY KEY(specialization_id, dietetician_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE specialization_dietetician ADD CONSTRAINT FK_11D4722A158DBF52 FOREIGN KEY (dietetician_id) REFERENCES dietetician (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specialization_dietetician ADD CONSTRAINT FK_11D4722AFA846217 FOREIGN KEY (specialization_id) REFERENCES specialization (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE dietetician_specialization');
    }
}
