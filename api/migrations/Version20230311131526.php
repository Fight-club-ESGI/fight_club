<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230311131526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE wallet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE wallet (id INT NOT NULL, users_id UUID NOT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7C68921F67B3B43D ON wallet (users_id)');
        $this->addSql('COMMENT ON COLUMN wallet.users_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F67B3B43D FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE refresh_tokens ALTER valid TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN refresh_tokens.valid IS NULL');
        $this->addSql('ALTER TABLE "user" ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE "user" ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE wallet_id_seq CASCADE');
        $this->addSql('ALTER TABLE wallet DROP CONSTRAINT FK_7C68921F67B3B43D');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('ALTER TABLE "refresh_tokens" ALTER valid TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN "refresh_tokens".valid IS \'(DC2Type:carbon)\'');
        $this->addSql('ALTER TABLE "user" ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE "user" ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:carbon_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS \'(DC2Type:carbon)\'');
    }
}
