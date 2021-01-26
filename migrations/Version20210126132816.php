<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210126132816 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2EE45BDBF');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2EE45BDBF');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
