<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230507154752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bet DROP CONSTRAINT FK_FBF0EC9BB0F321C7');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9BB0F321C7 FOREIGN KEY (bet_on_id) REFERENCES fighter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT fk_97a0ada37ed69b9d');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT fk_97a0ada371f7e88b');
        $this->addSql('DROP INDEX idx_97a0ada371f7e88b');
        $this->addSql('DROP INDEX idx_97a0ada37ed69b9d');
        $this->addSql('ALTER TABLE ticket DROP ticket_category_id');
        $this->addSql('ALTER TABLE ticket DROP event_id');
        $this->addSql('ALTER TABLE ticket DROP price');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ticket ADD ticket_category_id UUID NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD event_id UUID NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD price DOUBLE PRECISION NOT NULL');
        $this->addSql('COMMENT ON COLUMN ticket.ticket_category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket.event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT fk_97a0ada37ed69b9d FOREIGN KEY (ticket_category_id) REFERENCES ticket_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT fk_97a0ada371f7e88b FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_97a0ada371f7e88b ON ticket (event_id)');
        $this->addSql('CREATE INDEX idx_97a0ada37ed69b9d ON ticket (ticket_category_id)');
        $this->addSql('ALTER TABLE bet DROP CONSTRAINT fk_fbf0ec9bb0f321c7');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT fk_fbf0ec9bb0f321c7 FOREIGN KEY (bet_on_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
