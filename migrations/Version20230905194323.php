<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230905194323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calendar_day CHANGE create_at create_at DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE calendar_user ADD create_at DATETIME DEFAULT CURRENT_TIMESTAMP, ADD updated_at DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE meeting CHANGE create_at create_at DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE payment CHANGE create_at create_at DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE post CHANGE create_at create_at DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE subscription CHANGE create_at create_at DATETIME DEFAULT CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calendar_day CHANGE create_at create_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE calendar_user DROP create_at, DROP updated_at, DROP deleted_at');
        $this->addSql('ALTER TABLE meeting CHANGE create_at create_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE payment CHANGE create_at create_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE create_at create_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE subscription CHANGE create_at create_at DATETIME NOT NULL');
    }
}
