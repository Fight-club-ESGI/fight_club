<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230514173019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wallet_transaction ADD order_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE wallet_transaction DROP "order"');
        $this->addSql('COMMENT ON COLUMN wallet_transaction.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE wallet_transaction ADD CONSTRAINT FK_7DAF9728D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7DAF9728D9F6D38 ON wallet_transaction (order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "wallet_transaction" DROP CONSTRAINT FK_7DAF9728D9F6D38');
        $this->addSql('DROP INDEX UNIQ_7DAF9728D9F6D38');
        $this->addSql('ALTER TABLE "wallet_transaction" ADD "order" VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "wallet_transaction" DROP order_id');
    }
}
