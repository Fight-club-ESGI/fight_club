<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230416112911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD fight_category_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN event.fight_category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7D2DCB665 FOREIGN KEY (fight_category_id) REFERENCES fight_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7D2DCB665 ON event (fight_category_id)');
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
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7D2DCB665');
        $this->addSql('DROP INDEX IDX_3BAE0AA7D2DCB665');
        $this->addSql('ALTER TABLE event DROP fight_category_id');
    }
}
