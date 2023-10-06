<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231006063905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate DROP min_salary, DROP max_salary');
        $this->addSql('ALTER TABLE job_offer ADD position_type_id INT NOT NULL, ADD min_salary DOUBLE PRECISION NOT NULL, ADD max_salary DOUBLE PRECISION NOT NULL, DROP place');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E56BD9D60 FOREIGN KEY (position_type_id) REFERENCES position_type (id)');
        $this->addSql('CREATE INDEX IDX_288A3A4E56BD9D60 ON job_offer (position_type_id)');
        $this->addSql('ALTER TABLE recruiter CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recruiter CHANGE roles roles JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE candidate ADD min_salary DOUBLE PRECISION NOT NULL, ADD max_salary DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E56BD9D60');
        $this->addSql('DROP INDEX IDX_288A3A4E56BD9D60 ON job_offer');
        $this->addSql('ALTER TABLE job_offer ADD place VARCHAR(100) NOT NULL, DROP position_type_id, DROP min_salary, DROP max_salary');
    }
}
