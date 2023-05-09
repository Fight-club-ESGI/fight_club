<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508205804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bet ADD wallet_transaction_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN bet.wallet_transaction_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9B924C1837 FOREIGN KEY (wallet_transaction_id) REFERENCES "wallet_transaction" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FBF0EC9B924C1837 ON bet (wallet_transaction_id)');
        $this->addSql('ALTER TABLE event ALTER time_start SET NOT NULL');
        $this->addSql('ALTER TABLE event ALTER time_end SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE bet DROP CONSTRAINT FK_FBF0EC9B924C1837');
        $this->addSql('DROP INDEX UNIQ_FBF0EC9B924C1837');
        $this->addSql('ALTER TABLE bet DROP wallet_transaction_id');
        $this->addSql('ALTER TABLE event ALTER time_start DROP NOT NULL');
        $this->addSql('ALTER TABLE event ALTER time_end DROP NOT NULL');
    }
}
