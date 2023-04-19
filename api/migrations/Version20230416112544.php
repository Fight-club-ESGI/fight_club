<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230416112544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "refresh_tokens_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE bet (id UUID NOT NULL, fight_id UUID NOT NULL, bet_on_id UUID NOT NULL, bettor_id UUID NOT NULL, amount DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FBF0EC9BAC6657E4 ON bet (fight_id)');
        $this->addSql('CREATE INDEX IDX_FBF0EC9BB0F321C7 ON bet (bet_on_id)');
        $this->addSql('CREATE INDEX IDX_FBF0EC9BC7573C15 ON bet (bettor_id)');
        $this->addSql('COMMENT ON COLUMN bet.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN bet.fight_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN bet.bet_on_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN bet.bettor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN bet.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE event (id UUID NOT NULL, fight_category_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, location TEXT NOT NULL, location_link TEXT DEFAULT NULL, time_start TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL, time_end TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL, description TEXT DEFAULT NULL, capacity INT DEFAULT NULL, vip BOOLEAN NOT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7D2DCB665 ON event (fight_category_id)');
        $this->addSql('COMMENT ON COLUMN event.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN event.fight_category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN event.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE fight (id UUID NOT NULL, event_id UUID NOT NULL, fighter_a_id UUID NOT NULL, fighter_b_id UUID NOT NULL, winner_id UUID DEFAULT NULL, loser_id UUID DEFAULT NULL, admin_validator_a_id UUID DEFAULT NULL, admin_validator_b_id UUID DEFAULT NULL, winner_validation BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_21AA445671F7E88B ON fight (event_id)');
        $this->addSql('CREATE INDEX IDX_21AA445654041F33 ON fight (fighter_a_id)');
        $this->addSql('CREATE INDEX IDX_21AA445646B1B0DD ON fight (fighter_b_id)');
        $this->addSql('CREATE INDEX IDX_21AA44565DFCD4B8 ON fight (winner_id)');
        $this->addSql('CREATE INDEX IDX_21AA44561BCAA5F6 ON fight (loser_id)');
        $this->addSql('CREATE INDEX IDX_21AA4456E579D7E0 ON fight (admin_validator_a_id)');
        $this->addSql('CREATE INDEX IDX_21AA4456F7CC780E ON fight (admin_validator_b_id)');
        $this->addSql('COMMENT ON COLUMN fight.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fight.event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fight.fighter_a_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fight.fighter_b_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fight.winner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fight.loser_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fight.admin_validator_a_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fight.admin_validator_b_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fight.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE fight_category (id UUID NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN fight_category.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fight_category.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE fighter (id UUID NOT NULL, fight_category_id UUID DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthdate TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, height INT NOT NULL, weight INT NOT NULL, nationality VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7A08C3FCD2DCB665 ON fighter (fight_category_id)');
        $this->addSql('COMMENT ON COLUMN fighter.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fighter.fight_category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fighter.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "order" (id UUID NOT NULL, customer_id UUID NOT NULL, status VARCHAR(255) NOT NULL, payment_type VARCHAR(255) DEFAULT NULL, quantity INT NOT NULL, stripe TEXT DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F52993989395C3F3 ON "order" (customer_id)');
        $this->addSql('COMMENT ON COLUMN "order".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "order".customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "order".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE order_ticket (order_id UUID NOT NULL, ticket_id UUID NOT NULL, PRIMARY KEY(order_id, ticket_id))');
        $this->addSql('CREATE INDEX IDX_542FBD768D9F6D38 ON order_ticket (order_id)');
        $this->addSql('CREATE INDEX IDX_542FBD76700047D2 ON order_ticket (ticket_id)');
        $this->addSql('COMMENT ON COLUMN order_ticket.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN order_ticket.ticket_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "refresh_tokens" (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(6) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON "refresh_tokens" (refresh_token)');
        $this->addSql('CREATE TABLE sponsorship (id UUID NOT NULL, sponsor_id UUID NOT NULL, sponsored_id UUID NOT NULL, email_validation BOOLEAN NOT NULL, sponsor_validation BOOLEAN NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C0F10CD412F7FB51 ON sponsorship (sponsor_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C0F10CD473340AD ON sponsorship (sponsored_id)');
        $this->addSql('COMMENT ON COLUMN sponsorship.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN sponsorship.sponsor_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN sponsorship.sponsored_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN sponsorship.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE ticket (id UUID NOT NULL, ticket_category_id UUID DEFAULT NULL, event_id UUID NOT NULL, price DOUBLE PRECISION NOT NULL, availability BOOLEAN NOT NULL, time_start TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL, time_end TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_97A0ADA37ED69B9D ON ticket (ticket_category_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA371F7E88B ON ticket (event_id)');
        $this->addSql('COMMENT ON COLUMN ticket.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket.ticket_category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket.event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "ticket_category" (id UUID NOT NULL, event_id UUID NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8325E54071F7E88B ON "ticket_category" (event_id)');
        $this->addSql('COMMENT ON COLUMN "ticket_category".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "ticket_category".event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "ticket_category".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, token VARCHAR(255) DEFAULT NULL, token_date TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "wallet" (id UUID NOT NULL, users_id UUID NOT NULL, amount INT NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7C68921F67B3B43D ON "wallet" (users_id)');
        $this->addSql('COMMENT ON COLUMN "wallet".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "wallet".users_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "wallet".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "wallet_transaction" (id UUID NOT NULL, wallet_id UUID DEFAULT NULL, amount INT NOT NULL, status VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, transaction VARCHAR(255) DEFAULT NULL, stripe_ref VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7DAF972712520F3 ON "wallet_transaction" (wallet_id)');
        $this->addSql('COMMENT ON COLUMN "wallet_transaction".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "wallet_transaction".wallet_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "wallet_transaction".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "weight_category" (id UUID NOT NULL, fight_category_id UUID DEFAULT NULL, min_weight INT NOT NULL, max_weight INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4C6C78B4D2DCB665 ON "weight_category" (fight_category_id)');
        $this->addSql('COMMENT ON COLUMN "weight_category".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "weight_category".fight_category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "weight_category".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9BAC6657E4 FOREIGN KEY (fight_id) REFERENCES fight (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9BB0F321C7 FOREIGN KEY (bet_on_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9BC7573C15 FOREIGN KEY (bettor_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7D2DCB665 FOREIGN KEY (fight_category_id) REFERENCES fight_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA445671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA445654041F33 FOREIGN KEY (fighter_a_id) REFERENCES fighter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA445646B1B0DD FOREIGN KEY (fighter_b_id) REFERENCES fighter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA44565DFCD4B8 FOREIGN KEY (winner_id) REFERENCES fighter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA44561BCAA5F6 FOREIGN KEY (loser_id) REFERENCES fighter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA4456E579D7E0 FOREIGN KEY (admin_validator_a_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA4456F7CC780E FOREIGN KEY (admin_validator_b_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fighter ADD CONSTRAINT FK_7A08C3FCD2DCB665 FOREIGN KEY (fight_category_id) REFERENCES fight_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_ticket ADD CONSTRAINT FK_542FBD768D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_ticket ADD CONSTRAINT FK_542FBD76700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sponsorship ADD CONSTRAINT FK_C0F10CD412F7FB51 FOREIGN KEY (sponsor_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sponsorship ADD CONSTRAINT FK_C0F10CD473340AD FOREIGN KEY (sponsored_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA37ED69B9D FOREIGN KEY (ticket_category_id) REFERENCES "ticket_category" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA371F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "ticket_category" ADD CONSTRAINT FK_8325E54071F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "wallet" ADD CONSTRAINT FK_7C68921F67B3B43D FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "wallet_transaction" ADD CONSTRAINT FK_7DAF972712520F3 FOREIGN KEY (wallet_id) REFERENCES "wallet" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "weight_category" ADD CONSTRAINT FK_4C6C78B4D2DCB665 FOREIGN KEY (fight_category_id) REFERENCES fight_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "refresh_tokens_id_seq" CASCADE');
        $this->addSql('ALTER TABLE bet DROP CONSTRAINT FK_FBF0EC9BAC6657E4');
        $this->addSql('ALTER TABLE bet DROP CONSTRAINT FK_FBF0EC9BB0F321C7');
        $this->addSql('ALTER TABLE bet DROP CONSTRAINT FK_FBF0EC9BC7573C15');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7D2DCB665');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT FK_21AA445671F7E88B');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT FK_21AA445654041F33');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT FK_21AA445646B1B0DD');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT FK_21AA44565DFCD4B8');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT FK_21AA44561BCAA5F6');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT FK_21AA4456E579D7E0');
        $this->addSql('ALTER TABLE fight DROP CONSTRAINT FK_21AA4456F7CC780E');
        $this->addSql('ALTER TABLE fighter DROP CONSTRAINT FK_7A08C3FCD2DCB665');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993989395C3F3');
        $this->addSql('ALTER TABLE order_ticket DROP CONSTRAINT FK_542FBD768D9F6D38');
        $this->addSql('ALTER TABLE order_ticket DROP CONSTRAINT FK_542FBD76700047D2');
        $this->addSql('ALTER TABLE sponsorship DROP CONSTRAINT FK_C0F10CD412F7FB51');
        $this->addSql('ALTER TABLE sponsorship DROP CONSTRAINT FK_C0F10CD473340AD');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA37ED69B9D');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA371F7E88B');
        $this->addSql('ALTER TABLE "ticket_category" DROP CONSTRAINT FK_8325E54071F7E88B');
        $this->addSql('ALTER TABLE "wallet" DROP CONSTRAINT FK_7C68921F67B3B43D');
        $this->addSql('ALTER TABLE "wallet_transaction" DROP CONSTRAINT FK_7DAF972712520F3');
        $this->addSql('ALTER TABLE "weight_category" DROP CONSTRAINT FK_4C6C78B4D2DCB665');
        $this->addSql('DROP TABLE bet');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE fight');
        $this->addSql('DROP TABLE fight_category');
        $this->addSql('DROP TABLE fighter');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_ticket');
        $this->addSql('DROP TABLE "refresh_tokens"');
        $this->addSql('DROP TABLE sponsorship');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE "ticket_category"');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE "wallet"');
        $this->addSql('DROP TABLE "wallet_transaction"');
        $this->addSql('DROP TABLE "weight_category"');
    }
}
