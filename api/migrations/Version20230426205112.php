<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426205112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT fk_97a0ada3f49e140a');
        $this->addSql('CREATE TABLE order_ticket (order_id UUID NOT NULL, ticket_id UUID NOT NULL, PRIMARY KEY(order_id, ticket_id))');
        $this->addSql('CREATE INDEX IDX_542FBD768D9F6D38 ON order_ticket (order_id)');
        $this->addSql('CREATE INDEX IDX_542FBD76700047D2 ON order_ticket (ticket_id)');
        $this->addSql('COMMENT ON COLUMN order_ticket.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN order_ticket.ticket_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE order_ticket ADD CONSTRAINT FK_542FBD768D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_ticket ADD CONSTRAINT FK_542FBD76700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket_event DROP CONSTRAINT fk_139e692c71f7e88b');
        $this->addSql('ALTER TABLE ticket_event DROP CONSTRAINT fk_139e692c7ed69b9d');
        $this->addSql('DROP TABLE ticket_event');
        $this->addSql('ALTER TABLE "order" ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE "order" DROP price');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT fk_97a0ada3a35f2858');
        $this->addSql('DROP INDEX idx_97a0ada3a35f2858');
        $this->addSql('DROP INDEX idx_97a0ada3f49e140a');
        $this->addSql('ALTER TABLE ticket ADD availability BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD time_start TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD time_end TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket DROP ticket_event_id');
        $this->addSql('ALTER TABLE ticket DROP _order_id');
        $this->addSql('ALTER TABLE ticket DROP reference');
        $this->addSql('ALTER TABLE ticket ALTER ticket_category_id DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP viptoken');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE ticket_event (id UUID NOT NULL, event_id UUID NOT NULL, ticket_category_id UUID NOT NULL, max_quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_139e692c7ed69b9d ON ticket_event (ticket_category_id)');
        $this->addSql('CREATE INDEX idx_139e692c71f7e88b ON ticket_event (event_id)');
        $this->addSql('COMMENT ON COLUMN ticket_event.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket_event.event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket_event.ticket_category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket_event.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE ticket_event ADD CONSTRAINT fk_139e692c71f7e88b FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket_event ADD CONSTRAINT fk_139e692c7ed69b9d FOREIGN KEY (ticket_category_id) REFERENCES ticket_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_ticket DROP CONSTRAINT FK_542FBD768D9F6D38');
        $this->addSql('ALTER TABLE order_ticket DROP CONSTRAINT FK_542FBD76700047D2');
        $this->addSql('DROP TABLE order_ticket');
        $this->addSql('ALTER TABLE ticket ADD ticket_event_id UUID NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD _order_id UUID NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD reference VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ticket DROP availability');
        $this->addSql('ALTER TABLE ticket DROP time_start');
        $this->addSql('ALTER TABLE ticket DROP time_end');
        $this->addSql('ALTER TABLE ticket ALTER ticket_category_id SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN ticket.ticket_event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket._order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT fk_97a0ada3f49e140a FOREIGN KEY (ticket_event_id) REFERENCES ticket_event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT fk_97a0ada3a35f2858 FOREIGN KEY (_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_97a0ada3a35f2858 ON ticket (_order_id)');
        $this->addSql('CREATE INDEX idx_97a0ada3f49e140a ON ticket (ticket_event_id)');
        $this->addSql('ALTER TABLE "user" ADD viptoken VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE "order" DROP quantity');
    }
}
