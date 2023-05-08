<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508210604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bet ADD wallet_transaction_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN bet.wallet_transaction_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9B924C1837 FOREIGN KEY (wallet_transaction_id) REFERENCES "wallet_transaction" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FBF0EC9B924C1837 ON bet (wallet_transaction_id)');
        $this->addSql('ALTER TABLE wallet_transaction ADD bet_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN wallet_transaction.bet_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE wallet_transaction ADD CONSTRAINT FK_7DAF972D871DC26 FOREIGN KEY (bet_id) REFERENCES bet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7DAF972D871DC26 ON wallet_transaction (bet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "wallet_transaction" DROP CONSTRAINT FK_7DAF972D871DC26');
        $this->addSql('DROP INDEX UNIQ_7DAF972D871DC26');
        $this->addSql('ALTER TABLE "wallet_transaction" DROP bet_id');
        $this->addSql('ALTER TABLE bet DROP CONSTRAINT FK_FBF0EC9B924C1837');
        $this->addSql('DROP INDEX UNIQ_FBF0EC9B924C1837');
        $this->addSql('ALTER TABLE bet DROP wallet_transaction_id');
    }
}
