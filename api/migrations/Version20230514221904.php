<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230514221904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f52993989395c3f3');
        $this->addSql('DROP INDEX uniq_f52993989395c3f3');
        $this->addSql('ALTER TABLE "order" DROP customer_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "order" ADD customer_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN "order".customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f52993989395c3f3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_f52993989395c3f3 ON "order" (customer_id)');
    }
}
