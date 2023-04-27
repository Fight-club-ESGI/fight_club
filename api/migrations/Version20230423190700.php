<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230423190700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_ticket DROP CONSTRAINT fk_542fbd768d9f6d38');
        $this->addSql('ALTER TABLE order_ticket DROP CONSTRAINT fk_542fbd76700047d2');
        $this->addSql('DROP TABLE order_ticket');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE order_ticket (order_id UUID NOT NULL, ticket_id UUID NOT NULL, PRIMARY KEY(order_id, ticket_id))');
        $this->addSql('CREATE INDEX idx_542fbd76700047d2 ON order_ticket (ticket_id)');
        $this->addSql('CREATE INDEX idx_542fbd768d9f6d38 ON order_ticket (order_id)');
        $this->addSql('COMMENT ON COLUMN order_ticket.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN order_ticket.ticket_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE order_ticket ADD CONSTRAINT fk_542fbd768d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_ticket ADD CONSTRAINT fk_542fbd76700047d2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
