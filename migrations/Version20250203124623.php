<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203124623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE critere_evaluation');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE periode_evaluation');
        $this->addSql('ALTER TABLE absence DROP employe_id, DROP status');
        $this->addSql('ALTER TABLE conge_accorder ADD CONSTRAINT FK_31594E4C1B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE conge_accorder ADD CONSTRAINT FK_31594E4CF50CF8EF FOREIGN KEY (conges_id) REFERENCES conges (id)');
        $this->addSql('ALTER TABLE conge_accorder ADD CONSTRAINT FK_31594E4CC54C8C93 FOREIGN KEY (type_id) REFERENCES type_conge (id)');
        $this->addSql('CREATE INDEX IDX_31594E4C1B65292 ON conge_accorder (employe_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_31594E4CF50CF8EF ON conge_accorder (conges_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_31594E4CC54C8C93 ON conge_accorder (type_id)');
        $this->addSql('ALTER TABLE conges ADD CONSTRAINT FK_6327DE3A1B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE conges ADD CONSTRAINT FK_6327DE3AC54C8C93 FOREIGN KEY (type_id) REFERENCES type_conge (id)');
        $this->addSql('ALTER TABLE conges ADD CONSTRAINT FK_6327DE3A486A1052 FOREIGN KEY (congeaccorder_id) REFERENCES conge_accorder (id)');
        $this->addSql('CREATE INDEX IDX_6327DE3A1B65292 ON conges (employe_id)');
        $this->addSql('CREATE INDEX IDX_6327DE3AC54C8C93 ON conges (type_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6327DE3A486A1052 ON conges (congeaccorder_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE critere_evaluation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, poids DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, employe_id INT DEFAULT NULL, date_evaluation DATETIME NOT NULL, commentaire LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, score DOUBLE PRECISION NOT NULL, periode_evaluation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_1323A5751B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE periode_evaluation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_debut DATE NOT NULL, date_fin DATE NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5751B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE absence ADD employe_id INT DEFAULT NULL, ADD status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE conge_accorder DROP FOREIGN KEY FK_31594E4C1B65292');
        $this->addSql('ALTER TABLE conge_accorder DROP FOREIGN KEY FK_31594E4CF50CF8EF');
        $this->addSql('ALTER TABLE conge_accorder DROP FOREIGN KEY FK_31594E4CC54C8C93');
        $this->addSql('DROP INDEX IDX_31594E4C1B65292 ON conge_accorder');
        $this->addSql('DROP INDEX UNIQ_31594E4CF50CF8EF ON conge_accorder');
        $this->addSql('DROP INDEX UNIQ_31594E4CC54C8C93 ON conge_accorder');
        $this->addSql('ALTER TABLE conges DROP FOREIGN KEY FK_6327DE3A1B65292');
        $this->addSql('ALTER TABLE conges DROP FOREIGN KEY FK_6327DE3AC54C8C93');
        $this->addSql('ALTER TABLE conges DROP FOREIGN KEY FK_6327DE3A486A1052');
        $this->addSql('DROP INDEX IDX_6327DE3A1B65292 ON conges');
        $this->addSql('DROP INDEX IDX_6327DE3AC54C8C93 ON conges');
        $this->addSql('DROP INDEX UNIQ_6327DE3A486A1052 ON conges');
    }
}
