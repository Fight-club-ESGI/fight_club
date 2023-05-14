<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230514221524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" ADD _user_id UUID NOT NULL');
        $this->addSql('ALTER TABLE "order" DROP payment_type');
        $this->addSql('ALTER TABLE "order" DROP stripe');
        $this->addSql('COMMENT ON COLUMN "order"._user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398D32632E8 FOREIGN KEY (_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F5299398D32632E8 ON "order" (_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398D32632E8');
        $this->addSql('DROP INDEX IDX_F5299398D32632E8');
        $this->addSql('ALTER TABLE "order" ADD payment_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD stripe TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" DROP _user_id');
    }
}
