<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230416110727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket_category ADD event_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN ticket_category.event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE ticket_category ADD CONSTRAINT FK_8325E54071F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8325E54071F7E88B ON ticket_category (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "ticket_category" DROP CONSTRAINT FK_8325E54071F7E88B');
        $this->addSql('DROP INDEX IDX_8325E54071F7E88B');
        $this->addSql('ALTER TABLE "ticket_category" DROP event_id');
    }
}
