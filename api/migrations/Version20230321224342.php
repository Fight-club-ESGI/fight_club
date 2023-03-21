<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321224342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT FK_21AA445654041F33');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT FK_21AA445646B1B0DD');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT FK_21AA44565DFCD4B8');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT FK_21AA44561BCAA5F6');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA445654041F33 FOREIGN KEY (fighter_a_id) REFERENCES fighter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA445646B1B0DD FOREIGN KEY (fighter_b_id) REFERENCES fighter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA44565DFCD4B8 FOREIGN KEY (winner_id) REFERENCES fighter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA44561BCAA5F6 FOREIGN KEY (loser_id) REFERENCES fighter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT fk_21aa445654041f33');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT fk_21aa445646b1b0dd');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT fk_21aa44565dfcd4b8');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT fk_21aa44561bcaa5f6');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT fk_21aa445654041f33 FOREIGN KEY (fighter_a_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT fk_21aa445646b1b0dd FOREIGN KEY (fighter_b_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT fk_21aa44565dfcd4b8 FOREIGN KEY (winner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT fk_21aa44561bcaa5f6 FOREIGN KEY (loser_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
