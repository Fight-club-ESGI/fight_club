<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230527194028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pending_ticket (id UUID NOT NULL, ticket_event_id UUID NOT NULL, _order_id UUID NOT NULL, quantity INT NOT NULL, created_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(6) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_99E4B013F49E140A ON pending_ticket (ticket_event_id)');
        $this->addSql('CREATE INDEX IDX_99E4B013A35F2858 ON pending_ticket (_order_id)');
        $this->addSql('COMMENT ON COLUMN pending_ticket.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN pending_ticket.ticket_event_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN pending_ticket._order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN pending_ticket.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE pending_ticket ADD CONSTRAINT FK_99E4B013F49E140A FOREIGN KEY (ticket_event_id) REFERENCES ticket_event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pending_ticket ADD CONSTRAINT FK_99E4B013A35F2858 FOREIGN KEY (_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ALTER is_active DROP DEFAULT');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f52993989395c3f3');
        $this->addSql('DROP INDEX uniq_f52993989395c3f3');
        $this->addSql('ALTER TABLE "order" ADD wallet_transaction_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD reference VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "order" DROP payment_type');
        $this->addSql('ALTER TABLE "order" DROP stripe');
        $this->addSql('ALTER TABLE "order" RENAME COLUMN customer_id TO _user_id');
        $this->addSql('COMMENT ON COLUMN "order".wallet_transaction_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398D32632E8 FOREIGN KEY (_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398924C1837 FOREIGN KEY (wallet_transaction_id) REFERENCES "wallet_transaction" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F5299398D32632E8 ON "order" (_user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5299398924C1837 ON "order" (wallet_transaction_id)');
        $this->addSql('ALTER TABLE wallet_transaction ADD order_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN wallet_transaction.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE wallet_transaction ADD CONSTRAINT FK_7DAF9728D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7DAF9728D9F6D38 ON wallet_transaction (order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE pending_ticket DROP CONSTRAINT FK_99E4B013F49E140A');
        $this->addSql('ALTER TABLE pending_ticket DROP CONSTRAINT FK_99E4B013A35F2858');
        $this->addSql('DROP TABLE pending_ticket');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398D32632E8');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398924C1837');
        $this->addSql('DROP INDEX IDX_F5299398D32632E8');
        $this->addSql('DROP INDEX UNIQ_F5299398924C1837');
        $this->addSql('ALTER TABLE "order" ADD payment_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD stripe TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" DROP wallet_transaction_id');
        $this->addSql('ALTER TABLE "order" DROP reference');
        $this->addSql('ALTER TABLE "order" RENAME COLUMN _user_id TO customer_id');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f52993989395c3f3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_f52993989395c3f3 ON "order" (customer_id)');
        $this->addSql('ALTER TABLE event ALTER is_active SET DEFAULT true');
        $this->addSql('ALTER TABLE "wallet_transaction" DROP CONSTRAINT FK_7DAF9728D9F6D38');
        $this->addSql('DROP INDEX UNIQ_7DAF9728D9F6D38');
        $this->addSql('ALTER TABLE "wallet_transaction" DROP order_id');
    }
}
