<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510111620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id UUID NOT NULL, _user_id UUID NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B7D32632E8 ON cart (_user_id)');
        $this->addSql('COMMENT ON COLUMN cart.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cart._user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cart.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE cart_item (id UUID NOT NULL, cart_id UUID NOT NULL, ticket_event_id UUID NOT NULL, quantity INT NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F0FE25271AD5CDBF ON cart_item (cart_id)');
        $this->addSql('CREATE INDEX IDX_F0FE2527F49E140A ON cart_item (ticket_event_id)');
        $this->addSql('COMMENT ON COLUMN cart_item.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cart_item.cart_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cart_item.ticket_event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cart_item.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7D32632E8 FOREIGN KEY (_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527F49E140A FOREIGN KEY (ticket_event_id) REFERENCES ticket_event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bet DROP CONSTRAINT FK_FBF0EC9BB0F321C7');
        $this->addSql('ALTER TABLE bet ADD wallet_transaction_id UUID NOT NULL');
        $this->addSql('ALTER TABLE bet ALTER amount TYPE INT');
        $this->addSql('COMMENT ON COLUMN bet.wallet_transaction_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9B924C1837 FOREIGN KEY (wallet_transaction_id) REFERENCES "wallet_transaction" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9BB0F321C7 FOREIGN KEY (bet_on_id) REFERENCES fighter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FBF0EC9B924C1837 ON bet (wallet_transaction_id)');
        $this->addSql('ALTER TABLE event ALTER time_start SET NOT NULL');
        $this->addSql('ALTER TABLE event ALTER time_end SET NOT NULL');
        $this->addSql('ALTER TABLE wallet_transaction ADD bet_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN wallet_transaction.bet_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE wallet_transaction ADD CONSTRAINT FK_7DAF972D871DC26 FOREIGN KEY (bet_id) REFERENCES bet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7DAF972D871DC26 ON wallet_transaction (bet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT FK_BA388B7D32632E8');
        $this->addSql('ALTER TABLE cart_item DROP CONSTRAINT FK_F0FE25271AD5CDBF');
        $this->addSql('ALTER TABLE cart_item DROP CONSTRAINT FK_F0FE2527F49E140A');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('ALTER TABLE event ALTER time_start DROP NOT NULL');
        $this->addSql('ALTER TABLE event ALTER time_end DROP NOT NULL');
        $this->addSql('ALTER TABLE bet DROP CONSTRAINT FK_FBF0EC9B924C1837');
        $this->addSql('ALTER TABLE bet DROP CONSTRAINT fk_fbf0ec9bb0f321c7');
        $this->addSql('DROP INDEX UNIQ_FBF0EC9B924C1837');
        $this->addSql('ALTER TABLE bet DROP wallet_transaction_id');
        $this->addSql('ALTER TABLE bet ALTER amount TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT fk_fbf0ec9bb0f321c7 FOREIGN KEY (bet_on_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "wallet_transaction" DROP CONSTRAINT FK_7DAF972D871DC26');
        $this->addSql('DROP INDEX UNIQ_7DAF972D871DC26');
        $this->addSql('ALTER TABLE "wallet_transaction" DROP bet_id');
    }
}
