<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230316104153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "refresh_tokens_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "refresh_tokens" (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON "refresh_tokens" (refresh_token)');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE wallet (id UUID NOT NULL, users_id UUID NOT NULL, amount DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7C68921F67B3B43D ON wallet (users_id)');
        $this->addSql('COMMENT ON COLUMN wallet.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN wallet.users_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN wallet.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE wallet_transaction (id UUID NOT NULL, wallet_id UUID DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, transaction VARCHAR(255) DEFAULT NULL, stripe_ref VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7DAF972712520F3 ON wallet_transaction (wallet_id)');
        $this->addSql('COMMENT ON COLUMN wallet_transaction.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN wallet_transaction.wallet_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN wallet_transaction.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F67B3B43D FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wallet_transaction ADD CONSTRAINT FK_7DAF972712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "refresh_tokens_id_seq" CASCADE');
        $this->addSql('ALTER TABLE wallet DROP CONSTRAINT FK_7C68921F67B3B43D');
        $this->addSql('ALTER TABLE wallet_transaction DROP CONSTRAINT FK_7DAF972712520F3');
        $this->addSql('DROP TABLE "refresh_tokens"');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP TABLE wallet_transaction');
    }
}
