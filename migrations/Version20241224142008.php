<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241224142008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense ADD depense_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_3405975741D81563 FOREIGN KEY (depense_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_3405975741D81563 ON depense (depense_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_3405975741D81563');
        $this->addSql('DROP INDEX IDX_3405975741D81563 ON depense');
        $this->addSql('ALTER TABLE depense DROP depense_id');
    }
}
