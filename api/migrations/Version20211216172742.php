<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211216172742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE departement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mutation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE departement (id INT NOT NULL, dep_code SMALLINT NOT NULL, dep_name VARCHAR(255) NOT NULL, region_code SMALLINT NOT NULL, region_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mutation (id INT NOT NULL, region_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, nature SMALLINT NOT NULL, price DOUBLE PRECISION DEFAULT NULL, dep_code INT DEFAULT NULL, local_type_code SMALLINT NOT NULL, surface INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4F4978F698260155 ON mutation (region_id)');
        $this->addSql('ALTER TABLE mutation ADD CONSTRAINT FK_4F4978F698260155 FOREIGN KEY (region_id) REFERENCES departement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mutation DROP CONSTRAINT FK_4F4978F698260155');
        $this->addSql('DROP SEQUENCE departement_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mutation_id_seq CASCADE');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE mutation');
    }
}
