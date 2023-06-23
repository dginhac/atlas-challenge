<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230623093746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE docker (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', number INT DEFAULT NULL, zip_name VARCHAR(255) NOT NULL, zip_size INT NOT NULL, INDEX IDX_F255FFADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', report_name VARCHAR(255) DEFAULT NULL, report_size INT DEFAULT NULL, UNIQUE INDEX UNIQ_C42F7784A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE docker ADD CONSTRAINT FK_F255FFADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user DROP docker, DROP report');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE docker DROP FOREIGN KEY FK_F255FFADA76ED395');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A76ED395');
        $this->addSql('DROP TABLE docker');
        $this->addSql('DROP TABLE report');
        $this->addSql('ALTER TABLE user ADD docker TINYINT(1) DEFAULT NULL, ADD report INT DEFAULT NULL');
    }
}
