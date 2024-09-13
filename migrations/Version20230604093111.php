<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230604093111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calendar_day (id INT AUTO_INCREMENT NOT NULL, calendar_id INT DEFAULT NULL, day INT NOT NULL, INDEX IDX_1BC14B23A40A2C8 (calendar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar_item (id INT AUTO_INCREMENT NOT NULL, calendar_day_id INT NOT NULL, calendar_section_id INT NOT NULL, text LONGTEXT DEFAULT NULL, INDEX IDX_4D8DA64AA3A4597A (calendar_day_id), INDEX IDX_4D8DA64A4E69277F (calendar_section_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar_section (id INT AUTO_INCREMENT NOT NULL, calendar_id INT NOT NULL, title VARCHAR(255) NOT NULL, sort_section INT NOT NULL, INDEX IDX_4F040128A40A2C8 (calendar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, file_size INT DEFAULT NULL, updated_file_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DF05551DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE calendar_day ADD CONSTRAINT FK_1BC14B23A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar_user (id)');
        $this->addSql('ALTER TABLE calendar_item ADD CONSTRAINT FK_4D8DA64AA3A4597A FOREIGN KEY (calendar_day_id) REFERENCES calendar_day (id)');
        $this->addSql('ALTER TABLE calendar_item ADD CONSTRAINT FK_4D8DA64A4E69277F FOREIGN KEY (calendar_section_id) REFERENCES calendar_section (id)');
        $this->addSql('ALTER TABLE calendar_section ADD CONSTRAINT FK_4F040128A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar_user (id)');
        $this->addSql('ALTER TABLE calendar_user ADD CONSTRAINT FK_DF05551DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calendar_day DROP FOREIGN KEY FK_1BC14B23A40A2C8');
        $this->addSql('ALTER TABLE calendar_item DROP FOREIGN KEY FK_4D8DA64AA3A4597A');
        $this->addSql('ALTER TABLE calendar_item DROP FOREIGN KEY FK_4D8DA64A4E69277F');
        $this->addSql('ALTER TABLE calendar_section DROP FOREIGN KEY FK_4F040128A40A2C8');
        $this->addSql('ALTER TABLE calendar_user DROP FOREIGN KEY FK_DF05551DA76ED395');
        $this->addSql('DROP TABLE calendar_day');
        $this->addSql('DROP TABLE calendar_item');
        $this->addSql('DROP TABLE calendar_section');
        $this->addSql('DROP TABLE calendar_user');
    }
}
