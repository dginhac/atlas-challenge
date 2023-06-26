<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230623090950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE submission CHANGE report_name report_name VARCHAR(255) DEFAULT NULL, CHANGE report_size report_size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD report INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP report');
        $this->addSql('ALTER TABLE submission CHANGE report_name report_name VARCHAR(255) NOT NULL, CHANGE report_size report_size INT NOT NULL');
    }
}
