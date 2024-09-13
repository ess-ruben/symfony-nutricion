<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531212336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE business_media (id INT AUTO_INCREMENT NOT NULL, business_id INT NOT NULL, sort_order INT DEFAULT 0, section INT DEFAULT 0, url_sponsor VARCHAR(255) DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, file_size INT DEFAULT NULL, updated_file_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_14CD46B3A89DB457 (business_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE business_media ADD CONSTRAINT FK_14CD46B3A89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
        $this->addSql('ALTER TABLE meeting ADD creator_id INT NOT NULL, ADD business_id INT NOT NULL, ADD date_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD status INT DEFAULT NULL, ADD cancel_reason VARCHAR(255) DEFAULT NULL, ADD create_at VARCHAR(255) NOT NULL, ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP date, DROP active, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E13961220EA6 FOREIGN KEY (creator_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E139A89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
        $this->addSql('CREATE INDEX IDX_F515E13961220EA6 ON meeting (creator_id)');
        $this->addSql('CREATE INDEX IDX_F515E139A89DB457 ON meeting (business_id)');
        $this->addSql('ALTER TABLE payment CHANGE status status INT DEFAULT 0, CHANGE type type INT DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business_media DROP FOREIGN KEY FK_14CD46B3A89DB457');
        $this->addSql('DROP TABLE business_media');
        $this->addSql('ALTER TABLE meeting DROP FOREIGN KEY FK_F515E13961220EA6');
        $this->addSql('ALTER TABLE meeting DROP FOREIGN KEY FK_F515E139A89DB457');
        $this->addSql('DROP INDEX IDX_F515E13961220EA6 ON meeting');
        $this->addSql('DROP INDEX IDX_F515E139A89DB457 ON meeting');
        $this->addSql('ALTER TABLE meeting ADD date DATETIME NOT NULL, ADD active TINYINT(1) NOT NULL, DROP creator_id, DROP business_id, DROP date_at, DROP status, DROP cancel_reason, DROP create_at, DROP updated_at, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment CHANGE status status INT DEFAULT NULL, CHANGE type type INT DEFAULT NULL');
    }
}
