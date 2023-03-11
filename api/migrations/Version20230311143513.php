<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230311143513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wallet (id UUID NOT NULL, users_id UUID NOT NULL, amount DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7C68921F67B3B43D ON wallet (users_id)');
        $this->addSql('COMMENT ON COLUMN wallet.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN wallet.users_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN wallet.created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('COMMENT ON COLUMN wallet.updated_at IS \'(DC2Type:carbon)\'');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F67B3B43D FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE wallet DROP CONSTRAINT FK_7C68921F67B3B43D');
        $this->addSql('DROP TABLE wallet');
    }
}
