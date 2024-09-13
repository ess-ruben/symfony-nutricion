<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230807200003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE list_product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, list_product_id INT NOT NULL, name VARCHAR(255) NOT NULL, kcal DOUBLE PRECISION NOT NULL, unit_per_gr DOUBLE PRECISION DEFAULT NULL, proteins DOUBLE PRECISION DEFAULT NULL, hydrates DOUBLE PRECISION DEFAULT NULL, fats DOUBLE PRECISION DEFAULT NULL, INDEX IDX_D34A04AD9FA91286 (list_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9FA91286 FOREIGN KEY (list_product_id) REFERENCES list_product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD9FA91286');
        $this->addSql('DROP TABLE list_product');
        $this->addSql('DROP TABLE product');
    }
}
