<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231005054833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_offer ADD city_id INT NOT NULL, ADD job_branch_id INT NOT NULL');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E1B662358 FOREIGN KEY (job_branch_id) REFERENCES job_branch (id)');
        $this->addSql('CREATE INDEX IDX_288A3A4E8BAC62AF ON job_offer (city_id)');
        $this->addSql('CREATE INDEX IDX_288A3A4E1B662358 ON job_offer (job_branch_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E8BAC62AF');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E1B662358');
        $this->addSql('DROP INDEX IDX_288A3A4E8BAC62AF ON job_offer');
        $this->addSql('DROP INDEX IDX_288A3A4E1B662358 ON job_offer');
        $this->addSql('ALTER TABLE job_offer DROP city_id, DROP job_branch_id');
    }
}
