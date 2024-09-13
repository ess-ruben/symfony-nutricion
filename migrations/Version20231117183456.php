<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117183456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_measure ADD file_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_measure ADD file_size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_measure ADD updated_file_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN user_measure.updated_file_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_measure DROP file_name');
        $this->addSql('ALTER TABLE user_measure DROP file_size');
        $this->addSql('ALTER TABLE user_measure DROP updated_file_at');
    }
}
