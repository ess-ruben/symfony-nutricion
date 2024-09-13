<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231213210258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business_media ADD alternative_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE calendar_user ADD alternative_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE media_object ADD alternative_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD alternative_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_measure ADD alternative_url VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_measure DROP alternative_url');
        $this->addSql('ALTER TABLE media_object DROP alternative_url');
        $this->addSql('ALTER TABLE calendar_user DROP alternative_url');
        $this->addSql('ALTER TABLE post DROP alternative_url');
        $this->addSql('ALTER TABLE business_media DROP alternative_url');
    }
}
