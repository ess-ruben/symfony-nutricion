<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230713195132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calendar_user_history (id INT AUTO_INCREMENT NOT NULL, calendar_user_id INT NOT NULL, user_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_9EC90AE7C519A852 (calendar_user_id), INDEX IDX_9EC90AE7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE calendar_user_history ADD CONSTRAINT FK_9EC90AE7C519A852 FOREIGN KEY (calendar_user_id) REFERENCES calendar_day (id)');
        $this->addSql('ALTER TABLE calendar_user_history ADD CONSTRAINT FK_9EC90AE7A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calendar_user_history DROP FOREIGN KEY FK_9EC90AE7C519A852');
        $this->addSql('ALTER TABLE calendar_user_history DROP FOREIGN KEY FK_9EC90AE7A76ED395');
        $this->addSql('DROP TABLE calendar_user_history');
    }
}
