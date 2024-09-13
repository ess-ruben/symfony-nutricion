<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230813201630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD discr VARCHAR(255) NOT NULL, ADD file_name VARCHAR(255) DEFAULT NULL, ADD file_size INT DEFAULT NULL, ADD updated_file_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD kcal DOUBLE PRECISION DEFAULT NULL, ADD minutes DOUBLE PRECISION DEFAULT NULL, CHANGE business_id business_id INT NOT NULL, CHANGE image create_at VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE post_category ADD icon VARCHAR(255) DEFAULT NULL, ADD is_active TINYINT(1) DEFAULT 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD image VARCHAR(255) NOT NULL, DROP create_at, DROP updated_at, DROP deleted_at, DROP discr, DROP file_name, DROP file_size, DROP updated_file_at, DROP kcal, DROP minutes, CHANGE business_id business_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post_category DROP icon, DROP is_active');
    }
}
