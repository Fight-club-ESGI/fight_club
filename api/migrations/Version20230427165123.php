<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230427165123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ticket_event (id UUID NOT NULL, event_id UUID NOT NULL, ticket_category_id UUID NOT NULL, max_quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_139E692C71F7E88B ON ticket_event (event_id)');
        $this->addSql('CREATE INDEX IDX_139E692C7ED69B9D ON ticket_event (ticket_category_id)');
        $this->addSql('COMMENT ON COLUMN ticket_event.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket_event.event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket_event.ticket_category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket_event.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE ticket_event ADD CONSTRAINT FK_139E692C71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket_event ADD CONSTRAINT FK_139E692C7ED69B9D FOREIGN KEY (ticket_category_id) REFERENCES "ticket_category" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE "order" DROP quantity');
        $this->addSql('ALTER TABLE ticket ADD ticket_event_id UUID NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD _order_id UUID NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD reference VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ticket DROP availability');
        $this->addSql('ALTER TABLE ticket DROP time_start');
        $this->addSql('ALTER TABLE ticket DROP time_end');
        $this->addSql('ALTER TABLE ticket ALTER ticket_category_id SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN ticket.ticket_event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket._order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3F49E140A FOREIGN KEY (ticket_event_id) REFERENCES ticket_event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3A35F2858 FOREIGN KEY (_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_97A0ADA3F49E140A ON ticket (ticket_event_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3A35F2858 ON ticket (_order_id)');
        $this->addSql('ALTER TABLE ticket_category DROP CONSTRAINT fk_8325e54071f7e88b');
        $this->addSql('DROP INDEX idx_8325e54071f7e88b');
        $this->addSql('ALTER TABLE ticket_category DROP event_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3F49E140A');
        $this->addSql('ALTER TABLE ticket_event DROP CONSTRAINT FK_139E692C71F7E88B');
        $this->addSql('ALTER TABLE ticket_event DROP CONSTRAINT FK_139E692C7ED69B9D');
        $this->addSql('DROP TABLE ticket_event');
        $this->addSql('ALTER TABLE "ticket_category" ADD event_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN "ticket_category".event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "ticket_category" ADD CONSTRAINT fk_8325e54071f7e88b FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8325e54071f7e88b ON "ticket_category" (event_id)');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3A35F2858');
        $this->addSql('DROP INDEX IDX_97A0ADA3F49E140A');
        $this->addSql('DROP INDEX IDX_97A0ADA3A35F2858');
        $this->addSql('ALTER TABLE ticket ADD availability BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD time_start TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD time_end TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket DROP ticket_event_id');
        $this->addSql('ALTER TABLE ticket DROP _order_id');
        $this->addSql('ALTER TABLE ticket DROP reference');
        $this->addSql('ALTER TABLE ticket ALTER ticket_category_id DROP NOT NULL');
        $this->addSql('ALTER TABLE "order" ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE "order" DROP price');
    }
}
