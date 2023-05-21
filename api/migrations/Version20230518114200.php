<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518114200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fighter_fight (fighter_id UUID NOT NULL, fight_id UUID NOT NULL, PRIMARY KEY(fighter_id, fight_id))');
        $this->addSql('CREATE INDEX IDX_FBA526CA34934341 ON fighter_fight (fighter_id)');
        $this->addSql('CREATE INDEX IDX_FBA526CAAC6657E4 ON fighter_fight (fight_id)');
        $this->addSql('COMMENT ON COLUMN fighter_fight.fighter_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fighter_fight.fight_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE fighter_fight ADD CONSTRAINT FK_FBA526CA34934341 FOREIGN KEY (fighter_id) REFERENCES fighter (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fighter_fight ADD CONSTRAINT FK_FBA526CAAC6657E4 FOREIGN KEY (fight_id) REFERENCES fight (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight DROP fight_date');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fighter_fight DROP CONSTRAINT FK_FBA526CA34934341');
        $this->addSql('ALTER TABLE fighter_fight DROP CONSTRAINT FK_FBA526CAAC6657E4');
        $this->addSql('DROP TABLE fighter_fight');
        $this->addSql('ALTER TABLE fight ADD fight_date TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL');
    }
}
