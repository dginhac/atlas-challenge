<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627120702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE metrics (id INT AUTO_INCREMENT NOT NULL, docker_id INT NOT NULL, liver_asd DOUBLE PRECISION NOT NULL, liver_dice DOUBLE PRECISION NOT NULL, liver_hausdorff_distance DOUBLE PRECISION NOT NULL, liver_surface_dice DOUBLE PRECISION NOT NULL, tumor_asd DOUBLE PRECISION NOT NULL, tumor_dice DOUBLE PRECISION NOT NULL, tumor_hausdorff_distance DOUBLE PRECISION NOT NULL, tumor_surface_dice DOUBLE PRECISION NOT NULL, rmse DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_228AAAE73FC453F8 (docker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE metrics ADD CONSTRAINT FK_228AAAE73FC453F8 FOREIGN KEY (docker_id) REFERENCES docker (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE metrics DROP FOREIGN KEY FK_228AAAE73FC453F8');
        $this->addSql('DROP TABLE metrics');
    }
}
