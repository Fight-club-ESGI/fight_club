<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230514172338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" ADD wallet_transaction_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN "order".wallet_transaction_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398924C1837 FOREIGN KEY (wallet_transaction_id) REFERENCES "wallet_transaction" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5299398924C1837 ON "order" (wallet_transaction_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398924C1837');
        $this->addSql('DROP INDEX UNIQ_F5299398924C1837');
        $this->addSql('ALTER TABLE "order" DROP wallet_transaction_id');
    }
}
