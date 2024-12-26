<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241226182005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('CREATE INDEX IDX_497DD634F2C56620 ON categorie (compte_id)');
        $this->addSql('ALTER TABLE credit ADD tva DOUBLE PRECISION DEFAULT NULL, ADD date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE depense ADD compte INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD compte INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634F2C56620');
        $this->addSql('DROP INDEX IDX_497DD634F2C56620 ON categorie');
        $this->addSql('ALTER TABLE credit DROP tva, DROP date');
        $this->addSql('ALTER TABLE depense DROP compte');
        $this->addSql('ALTER TABLE user DROP compte');
    }
}
