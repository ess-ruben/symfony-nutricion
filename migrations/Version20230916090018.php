<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230916090018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_measure (id INT AUTO_INCREMENT NOT NULL, meeting_id INT NOT NULL, user_id INT DEFAULT NULL, business_id INT NOT NULL, bicipital DOUBLE PRECISION DEFAULT \'0\', tricipital DOUBLE PRECISION DEFAULT \'0\', subscapularis DOUBLE PRECISION DEFAULT \'0\', suprailiac DOUBLE PRECISION DEFAULT \'0\', abdominal DOUBLE PRECISION DEFAULT \'0\', front_thigh DOUBLE PRECISION DEFAULT \'0\', medial_calf DOUBLE PRECISION DEFAULT \'0\', waist DOUBLE PRECISION DEFAULT \'0\', hip DOUBLE PRECISION DEFAULT \'0\', thigh DOUBLE PRECISION DEFAULT \'0\', calf DOUBLE PRECISION DEFAULT \'0\', contracted_arm DOUBLE PRECISION DEFAULT \'0\', create_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_D80A072F67433D9C (meeting_id), INDEX IDX_D80A072FA76ED395 (user_id), INDEX IDX_D80A072FA89DB457 (business_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_measure ADD CONSTRAINT FK_D80A072F67433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE user_measure ADD CONSTRAINT FK_D80A072FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_measure ADD CONSTRAINT FK_D80A072FA89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
        $this->addSql('ALTER TABLE meeting ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E1393DA5256D FOREIGN KEY (image_id) REFERENCES media_object (id)');
        $this->addSql('CREATE INDEX IDX_F515E1393DA5256D ON meeting (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_measure DROP FOREIGN KEY FK_D80A072F67433D9C');
        $this->addSql('ALTER TABLE user_measure DROP FOREIGN KEY FK_D80A072FA76ED395');
        $this->addSql('ALTER TABLE user_measure DROP FOREIGN KEY FK_D80A072FA89DB457');
        $this->addSql('DROP TABLE user_measure');
        $this->addSql('ALTER TABLE meeting DROP FOREIGN KEY FK_F515E1393DA5256D');
        $this->addSql('DROP INDEX IDX_F515E1393DA5256D ON meeting');
        $this->addSql('ALTER TABLE meeting DROP image_id');
    }
}
