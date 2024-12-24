<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241223165054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, cover VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE approvisionnement (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, approvisionner_id INT NOT NULL, quantite INT NOT NULL, date DATE NOT NULL, archive INT NOT NULL, lot VARCHAR(255) NOT NULL, peremption DATE NOT NULL, INDEX IDX_516C3FAAF347EFB (produit_id), INDEX IDX_516C3FAA221C9E86 (approvisionner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE approvisionner (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date DATE NOT NULL, INDEX IDX_4240C2B8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avoir (id INT AUTO_INCREMENT NOT NULL, admin_id INT NOT NULL, client_id INT NOT NULL, commande_id INT NOT NULL, reclamation_id INT DEFAULT NULL, reste_id INT DEFAULT NULL, date DATE NOT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_659B1A43642B8210 (admin_id), INDEX IDX_659B1A4319EB6921 (client_id), INDEX IDX_659B1A4382EA2E54 (commande_id), INDEX IDX_659B1A432D6BA2D9 (reclamation_id), INDEX IDX_659B1A4346C508B5 (reste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avoir_reste (id INT AUTO_INCREMENT NOT NULL, avoir_id INT NOT NULL, client_id INT NOT NULL, admin_id INT NOT NULL, produit_id INT NOT NULL, livrer_id INT NOT NULL, commande_id INT NOT NULL, date DATE NOT NULL, quantite INT NOT NULL, quantitelivre INT NOT NULL, INDEX IDX_640DC8BCC36D46DB (avoir_id), INDEX IDX_640DC8BC19EB6921 (client_id), INDEX IDX_640DC8BC642B8210 (admin_id), INDEX IDX_640DC8BCF347EFB (produit_id), INDEX IDX_640DC8BC534C185D (livrer_id), INDEX IDX_640DC8BC82EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, cv_id INT DEFAULT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, datnaiss DATE NOT NULL, lieunaiss VARCHAR(255) NOT NULL, activite VARCHAR(255) NOT NULL, niveau VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E33BD3B8CFE419E2 (cv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, admin_id INT DEFAULT NULL, livreur_id INT DEFAULT NULL, paiement_id INT DEFAULT NULL, date DATE NOT NULL, montant DOUBLE PRECISION NOT NULL, versement DOUBLE PRECISION NOT NULL, reduction DOUBLE PRECISION NOT NULL, suivi TINYINT(1) NOT NULL, livraison TINYINT(1) NOT NULL, datelivrer DATE DEFAULT NULL, tva DOUBLE PRECISION NOT NULL, credit TINYINT(1) NOT NULL, payer TINYINT(1) NOT NULL, livrer TINYINT(1) NOT NULL, dateefectlivraison DATETIME DEFAULT NULL, INDEX IDX_6EEAA67DA76ED395 (user_id), INDEX IDX_6EEAA67D642B8210 (admin_id), INDEX IDX_6EEAA67DF8646701 (livreur_id), INDEX IDX_6EEAA67D2A4C4478 (paiement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_produit (produit_id INT NOT NULL, commande_id INT NOT NULL, promotion_id INT DEFAULT NULL, date DATE NOT NULL, session DOUBLE PRECISION NOT NULL, publique DOUBLE PRECISION NOT NULL, quantite INT NOT NULL, INDEX IDX_DF1E9E87139DF194 (promotion_id), INDEX IDX_DF1E9E87F347EFB (produit_id), INDEX IDX_DF1E9E8782EA2E54 (commande_id), PRIMARY KEY(produit_id, commande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cv (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, album_id INT NOT NULL, url VARCHAR(255) NOT NULL, alt VARCHAR(255) DEFAULT NULL, INDEX IDX_C53D045F1137ABCF (album_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, retour_id INT DEFAULT NULL, livreur_id INT NOT NULL, commande_id INT NOT NULL, date DATE NOT NULL, reste TINYINT(1) NOT NULL, livrer TINYINT(1) NOT NULL, dateefectlivraison DATETIME DEFAULT NULL, signature VARCHAR(255) DEFAULT NULL, INDEX IDX_E576B732A76ED395 (user_id), INDEX IDX_E576B73228D6031F (retour_id), INDEX IDX_E576B732F8646701 (livreur_id), INDEX IDX_E576B73282EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrer_produit (id INT AUTO_INCREMENT NOT NULL, retour_id INT DEFAULT NULL, promotion_id INT DEFAULT NULL, commande_id INT NOT NULL, produit_id INT NOT NULL, livrer_id INT NOT NULL, lot VARCHAR(255) NOT NULL, peremption DATE NOT NULL, reste TINYINT(1) NOT NULL, date DATE NOT NULL, quantitelivrer INT NOT NULL, restealivrer INT NOT NULL, quantitecommander INT NOT NULL, archive INT NOT NULL, INDEX IDX_42988E0728D6031F (retour_id), INDEX IDX_42988E07139DF194 (promotion_id), INDEX IDX_42988E0782EA2E54 (commande_id), INDEX IDX_42988E07F347EFB (produit_id), INDEX IDX_42988E07534C185D (livrer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrer_reste (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, produit_id INT NOT NULL, livrer_id INT NOT NULL, commande_id INT NOT NULL, date DATE NOT NULL, quantite INT NOT NULL, quantitelivre INT NOT NULL, session DOUBLE PRECISION NOT NULL, INDEX IDX_E9E4CF9A19EB6921 (client_id), INDEX IDX_E9E4CF9AF347EFB (produit_id), INDEX IDX_E9E4CF9A534C185D (livrer_id), INDEX IDX_E9E4CF9A82EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, type VARCHAR(255) NOT NULL, banque VARCHAR(255) DEFAULT NULL, numero INT DEFAULT NULL, montant INT NOT NULL, INDEX IDX_B1DC7A1E82EA2E54 (commande_id), INDEX IDX_B1DC7A1EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, promotion_id INT DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, desigantion VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, prix INT NOT NULL, mincommande VARCHAR(255) DEFAULT NULL, fabriquant VARCHAR(255) DEFAULT NULL, prixpublic INT NOT NULL, stock INT NOT NULL, creation DATE NOT NULL, tva TINYINT(1) NOT NULL, INDEX IDX_29A5EC27139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, designation VARCHAR(255) NOT NULL, debut DATE NOT NULL, fin DATE NOT NULL, type VARCHAR(255) NOT NULL, premier INT DEFAULT NULL, ugpremier INT DEFAULT NULL, deuxieme INT DEFAULT NULL, ugdeuxieme INT DEFAULT NULL, troisieme INT DEFAULT NULL, ugtroisieme INT DEFAULT NULL, quatrieme INT DEFAULT NULL, ugquatrieme INT DEFAULT NULL, cinquieme INT DEFAULT NULL, ugcinquieme INT DEFAULT NULL, reduction INT DEFAULT NULL, INDEX IDX_C11D7DD1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_produit (produit_id INT NOT NULL, promotion_id INT NOT NULL, INDEX IDX_71D81A1DF347EFB (produit_id), INDEX IDX_71D81A1D139DF194 (promotion_id), PRIMARY KEY(produit_id, promotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, usercloture_id INT DEFAULT NULL, user_id INT NOT NULL, commande_id INT NOT NULL, creation DATETIME NOT NULL, status TINYINT(1) NOT NULL, cloture DATETIME DEFAULT NULL, commentaire LONGTEXT NOT NULL, motif VARCHAR(255) NOT NULL, INDEX IDX_CE6064047298FBAD (usercloture_id), INDEX IDX_CE606404A76ED395 (user_id), INDEX IDX_CE60640482EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation_produit (reclamation_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_F88F32B62D6BA2D9 (reclamation_id), INDEX IDX_F88F32B6F347EFB (produit_id), PRIMARY KEY(reclamation_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, reclamation_id INT NOT NULL, date DATE NOT NULL, message LONGTEXT NOT NULL, INDEX IDX_5FB6DEC7A76ED395 (user_id), INDEX IDX_5FB6DEC72D6BA2D9 (reclamation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retour (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_ED6FD32182EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retour_produit (id INT AUTO_INCREMENT NOT NULL, retour_id INT NOT NULL, commande_id INT NOT NULL, produit_id INT NOT NULL, date DATETIME NOT NULL, quantite INT NOT NULL, motif VARCHAR(255) NOT NULL, lot INT NOT NULL, peremption DATE NOT NULL, reapprovisionner TINYINT(1) NOT NULL, rembourser TINYINT(1) NOT NULL, avoir TINYINT(1) NOT NULL, valider TINYINT(1) NOT NULL, INDEX IDX_DBFCC8AB28D6031F (retour_id), INDEX IDX_DBFCC8AB82EA2E54 (commande_id), INDEX IDX_DBFCC8ABF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, lot VARCHAR(255) NOT NULL, peremption DATE NOT NULL, quantite INT NOT NULL, INDEX IDX_4B365660F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suggestion (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, message LONGTEXT NOT NULL, INDEX IDX_DD80F31B19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, fonction VARCHAR(255) NOT NULL, roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', reset_token VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, client TINYINT(1) NOT NULL, livreur TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE versement (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, type VARCHAR(255) NOT NULL, banque VARCHAR(255) DEFAULT NULL, numero INT DEFAULT NULL, montant INT NOT NULL, INDEX IDX_716E936782EA2E54 (commande_id), INDEX IDX_716E9367A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE approvisionnement ADD CONSTRAINT FK_516C3FAAF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE approvisionnement ADD CONSTRAINT FK_516C3FAA221C9E86 FOREIGN KEY (approvisionner_id) REFERENCES approvisionner (id)');
        $this->addSql('ALTER TABLE approvisionner ADD CONSTRAINT FK_4240C2B8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avoir ADD CONSTRAINT FK_659B1A43642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avoir ADD CONSTRAINT FK_659B1A4319EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avoir ADD CONSTRAINT FK_659B1A4382EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE avoir ADD CONSTRAINT FK_659B1A432D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('ALTER TABLE avoir ADD CONSTRAINT FK_659B1A4346C508B5 FOREIGN KEY (reste_id) REFERENCES livrer_reste (id)');
        $this->addSql('ALTER TABLE avoir_reste ADD CONSTRAINT FK_640DC8BCC36D46DB FOREIGN KEY (avoir_id) REFERENCES avoir (id)');
        $this->addSql('ALTER TABLE avoir_reste ADD CONSTRAINT FK_640DC8BC19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avoir_reste ADD CONSTRAINT FK_640DC8BC642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avoir_reste ADD CONSTRAINT FK_640DC8BCF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE avoir_reste ADD CONSTRAINT FK_640DC8BC534C185D FOREIGN KEY (livrer_id) REFERENCES livrer (id)');
        $this->addSql('ALTER TABLE avoir_reste ADD CONSTRAINT FK_640DC8BC82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DF8646701 FOREIGN KEY (livreur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D2A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id)');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E87139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E87F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E8782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F1137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
        $this->addSql('ALTER TABLE livrer ADD CONSTRAINT FK_E576B732A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE livrer ADD CONSTRAINT FK_E576B73228D6031F FOREIGN KEY (retour_id) REFERENCES retour (id)');
        $this->addSql('ALTER TABLE livrer ADD CONSTRAINT FK_E576B732F8646701 FOREIGN KEY (livreur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE livrer ADD CONSTRAINT FK_E576B73282EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE livrer_produit ADD CONSTRAINT FK_42988E0728D6031F FOREIGN KEY (retour_id) REFERENCES retour (id)');
        $this->addSql('ALTER TABLE livrer_produit ADD CONSTRAINT FK_42988E07139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE livrer_produit ADD CONSTRAINT FK_42988E0782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE livrer_produit ADD CONSTRAINT FK_42988E07F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE livrer_produit ADD CONSTRAINT FK_42988E07534C185D FOREIGN KEY (livrer_id) REFERENCES livrer (id)');
        $this->addSql('ALTER TABLE livrer_reste ADD CONSTRAINT FK_E9E4CF9A19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE livrer_reste ADD CONSTRAINT FK_E9E4CF9AF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE livrer_reste ADD CONSTRAINT FK_E9E4CF9A534C185D FOREIGN KEY (livrer_id) REFERENCES livrer (id)');
        $this->addSql('ALTER TABLE livrer_reste ADD CONSTRAINT FK_E9E4CF9A82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE promotion_produit ADD CONSTRAINT FK_71D81A1DF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE promotion_produit ADD CONSTRAINT FK_71D81A1D139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064047298FBAD FOREIGN KEY (usercloture_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640482EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE reclamation_produit ADD CONSTRAINT FK_F88F32B62D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation_produit ADD CONSTRAINT FK_F88F32B6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC72D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('ALTER TABLE retour ADD CONSTRAINT FK_ED6FD32182EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE retour_produit ADD CONSTRAINT FK_DBFCC8AB28D6031F FOREIGN KEY (retour_id) REFERENCES retour (id)');
        $this->addSql('ALTER TABLE retour_produit ADD CONSTRAINT FK_DBFCC8AB82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE retour_produit ADD CONSTRAINT FK_DBFCC8ABF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE suggestion ADD CONSTRAINT FK_DD80F31B19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E936782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E9367A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F1137ABCF');
        $this->addSql('ALTER TABLE approvisionnement DROP FOREIGN KEY FK_516C3FAA221C9E86');
        $this->addSql('ALTER TABLE avoir_reste DROP FOREIGN KEY FK_640DC8BCC36D46DB');
        $this->addSql('ALTER TABLE avoir DROP FOREIGN KEY FK_659B1A4382EA2E54');
        $this->addSql('ALTER TABLE avoir_reste DROP FOREIGN KEY FK_640DC8BC82EA2E54');
        $this->addSql('ALTER TABLE commande_produit DROP FOREIGN KEY FK_DF1E9E8782EA2E54');
        $this->addSql('ALTER TABLE livrer DROP FOREIGN KEY FK_E576B73282EA2E54');
        $this->addSql('ALTER TABLE livrer_produit DROP FOREIGN KEY FK_42988E0782EA2E54');
        $this->addSql('ALTER TABLE livrer_reste DROP FOREIGN KEY FK_E9E4CF9A82EA2E54');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E82EA2E54');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640482EA2E54');
        $this->addSql('ALTER TABLE retour DROP FOREIGN KEY FK_ED6FD32182EA2E54');
        $this->addSql('ALTER TABLE retour_produit DROP FOREIGN KEY FK_DBFCC8AB82EA2E54');
        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E936782EA2E54');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8CFE419E2');
        $this->addSql('ALTER TABLE avoir_reste DROP FOREIGN KEY FK_640DC8BC534C185D');
        $this->addSql('ALTER TABLE livrer_produit DROP FOREIGN KEY FK_42988E07534C185D');
        $this->addSql('ALTER TABLE livrer_reste DROP FOREIGN KEY FK_E9E4CF9A534C185D');
        $this->addSql('ALTER TABLE avoir DROP FOREIGN KEY FK_659B1A4346C508B5');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D2A4C4478');
        $this->addSql('ALTER TABLE approvisionnement DROP FOREIGN KEY FK_516C3FAAF347EFB');
        $this->addSql('ALTER TABLE avoir_reste DROP FOREIGN KEY FK_640DC8BCF347EFB');
        $this->addSql('ALTER TABLE commande_produit DROP FOREIGN KEY FK_DF1E9E87F347EFB');
        $this->addSql('ALTER TABLE livrer_produit DROP FOREIGN KEY FK_42988E07F347EFB');
        $this->addSql('ALTER TABLE livrer_reste DROP FOREIGN KEY FK_E9E4CF9AF347EFB');
        $this->addSql('ALTER TABLE promotion_produit DROP FOREIGN KEY FK_71D81A1DF347EFB');
        $this->addSql('ALTER TABLE reclamation_produit DROP FOREIGN KEY FK_F88F32B6F347EFB');
        $this->addSql('ALTER TABLE retour_produit DROP FOREIGN KEY FK_DBFCC8ABF347EFB');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660F347EFB');
        $this->addSql('ALTER TABLE commande_produit DROP FOREIGN KEY FK_DF1E9E87139DF194');
        $this->addSql('ALTER TABLE livrer_produit DROP FOREIGN KEY FK_42988E07139DF194');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27139DF194');
        $this->addSql('ALTER TABLE promotion_produit DROP FOREIGN KEY FK_71D81A1D139DF194');
        $this->addSql('ALTER TABLE avoir DROP FOREIGN KEY FK_659B1A432D6BA2D9');
        $this->addSql('ALTER TABLE reclamation_produit DROP FOREIGN KEY FK_F88F32B62D6BA2D9');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC72D6BA2D9');
        $this->addSql('ALTER TABLE livrer DROP FOREIGN KEY FK_E576B73228D6031F');
        $this->addSql('ALTER TABLE livrer_produit DROP FOREIGN KEY FK_42988E0728D6031F');
        $this->addSql('ALTER TABLE retour_produit DROP FOREIGN KEY FK_DBFCC8AB28D6031F');
        $this->addSql('ALTER TABLE approvisionner DROP FOREIGN KEY FK_4240C2B8A76ED395');
        $this->addSql('ALTER TABLE avoir DROP FOREIGN KEY FK_659B1A43642B8210');
        $this->addSql('ALTER TABLE avoir DROP FOREIGN KEY FK_659B1A4319EB6921');
        $this->addSql('ALTER TABLE avoir_reste DROP FOREIGN KEY FK_640DC8BC19EB6921');
        $this->addSql('ALTER TABLE avoir_reste DROP FOREIGN KEY FK_640DC8BC642B8210');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D642B8210');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DF8646701');
        $this->addSql('ALTER TABLE livrer DROP FOREIGN KEY FK_E576B732A76ED395');
        $this->addSql('ALTER TABLE livrer DROP FOREIGN KEY FK_E576B732F8646701');
        $this->addSql('ALTER TABLE livrer_reste DROP FOREIGN KEY FK_E9E4CF9A19EB6921');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1EA76ED395');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD1A76ED395');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064047298FBAD');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A76ED395');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7A76ED395');
        $this->addSql('ALTER TABLE suggestion DROP FOREIGN KEY FK_DD80F31B19EB6921');
        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E9367A76ED395');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE approvisionnement');
        $this->addSql('DROP TABLE approvisionner');
        $this->addSql('DROP TABLE avoir');
        $this->addSql('DROP TABLE avoir_reste');
        $this->addSql('DROP TABLE candidature');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_produit');
        $this->addSql('DROP TABLE cv');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE livrer');
        $this->addSql('DROP TABLE livrer_produit');
        $this->addSql('DROP TABLE livrer_reste');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE promotion_produit');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reclamation_produit');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE retour');
        $this->addSql('DROP TABLE retour_produit');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE suggestion');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE versement');
    }
}
