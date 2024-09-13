<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119110540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE issue_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE issue_response_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE issue (id INT NOT NULL, user_id INT DEFAULT NULL, title TEXT NOT NULL, body TEXT DEFAULT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_12AD233EA76ED395 ON issue (user_id)');
        $this->addSql('CREATE TABLE issue_response (id INT NOT NULL, issue_id INT NOT NULL, user_id INT DEFAULT NULL, response TEXT NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E9D321505E7AA58C ON issue_response (issue_id)');
        $this->addSql('CREATE INDEX IDX_E9D32150A76ED395 ON issue_response (user_id)');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue_response ADD CONSTRAINT FK_E9D321505E7AA58C FOREIGN KEY (issue_id) REFERENCES issue (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE issue_response ADD CONSTRAINT FK_E9D32150A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE issue_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE issue_response_id_seq CASCADE');
        $this->addSql('ALTER TABLE issue DROP CONSTRAINT FK_12AD233EA76ED395');
        $this->addSql('ALTER TABLE issue_response DROP CONSTRAINT FK_E9D321505E7AA58C');
        $this->addSql('ALTER TABLE issue_response DROP CONSTRAINT FK_E9D32150A76ED395');
        $this->addSql('DROP TABLE issue');
        $this->addSql('DROP TABLE issue_response');
    }
}
