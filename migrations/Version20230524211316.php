<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230524211316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE business (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, boss_user_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_active TINYINT(1) DEFAULT 1, INDEX IDX_8D36E38F5B7AF75 (address_id), UNIQUE INDEX UNIQ_8D36E381EC47A0C (boss_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, tariff_id INT NOT NULL, business_id INT NOT NULL, status INT DEFAULT NULL, type INT DEFAULT NULL, end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', create_at VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6D28840D92348FD2 (tariff_id), INDEX IDX_6D28840DA89DB457 (business_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, tariff_id INT DEFAULT NULL, last_payment_id INT DEFAULT NULL, business_id INT NOT NULL, type INT DEFAULT 0, create_at VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active TINYINT(1) DEFAULT 1, INDEX IDX_A3C664D392348FD2 (tariff_id), UNIQUE INDEX UNIQ_A3C664D3EDB7C951 (last_payment_id), INDEX IDX_A3C664D3A89DB457 (business_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tariff (id INT AUTO_INCREMENT NOT NULL, price DOUBLE PRECISION DEFAULT NULL, price_montly DOUBLE PRECISION DEFAULT NULL, price_annual DOUBLE PRECISION DEFAULT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, workers INT DEFAULT NULL, clients INT DEFAULT NULL, is_active TINYINT(1) DEFAULT 1, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE business ADD CONSTRAINT FK_8D36E38F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE business ADD CONSTRAINT FK_8D36E381EC47A0C FOREIGN KEY (boss_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D92348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DA89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D392348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3EDB7C951 FOREIGN KEY (last_payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
        $this->addSql('ALTER TABLE post ADD business_id INT DEFAULT NULL, ADD is_active TINYINT(1) DEFAULT 1');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DA89DB457 ON post (business_id)');
        $this->addSql('ALTER TABLE user ADD business_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649A89DB457 ON user (business_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA89DB457');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649A89DB457');
        $this->addSql('ALTER TABLE business DROP FOREIGN KEY FK_8D36E38F5B7AF75');
        $this->addSql('ALTER TABLE business DROP FOREIGN KEY FK_8D36E381EC47A0C');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D92348FD2');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DA89DB457');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D392348FD2');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3EDB7C951');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3A89DB457');
        $this->addSql('DROP TABLE business');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE tariff');
        $this->addSql('DROP INDEX IDX_5A8A6C8DA89DB457 ON post');
        $this->addSql('ALTER TABLE post DROP business_id, DROP is_active');
        $this->addSql('DROP INDEX IDX_8D93D649A89DB457 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP business_id');
    }
}
