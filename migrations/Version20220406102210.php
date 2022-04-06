<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220406102210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX up_idx');
        $this->addSql('DROP INDEX IDX_A38D043CF7384557');
        $this->addSql('DROP INDEX IDX_A38D043C50EAE44');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_utilisateur_produit AS SELECT id, id_utilisateur, id_produit, quantite FROM im22_utilisateur_produit');
        $this->addSql('DROP TABLE im22_utilisateur_produit');
        $this->addSql('CREATE TABLE im22_utilisateur_produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_utilisateur INTEGER NOT NULL, id_produit INTEGER NOT NULL, quantite INTEGER NOT NULL, CONSTRAINT FK_A38D043C50EAE44 FOREIGN KEY (id_utilisateur) REFERENCES im22_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A38D043CF7384557 FOREIGN KEY (id_produit) REFERENCES im22_produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO im22_utilisateur_produit (id, id_utilisateur, id_produit, quantite) SELECT id, id_utilisateur, id_produit, quantite FROM __temp__im22_utilisateur_produit');
        $this->addSql('DROP TABLE __temp__im22_utilisateur_produit');
        $this->addSql('CREATE UNIQUE INDEX up_idx ON im22_utilisateur_produit (id_utilisateur, id_produit, quantite)');
        $this->addSql('CREATE INDEX IDX_A38D043CF7384557 ON im22_utilisateur_produit (id_produit)');
        $this->addSql('CREATE INDEX IDX_A38D043C50EAE44 ON im22_utilisateur_produit (id_utilisateur)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_A38D043C50EAE44');
        $this->addSql('DROP INDEX IDX_A38D043CF7384557');
        $this->addSql('DROP INDEX up_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_utilisateur_produit AS SELECT id, id_utilisateur, id_produit, quantite FROM im22_utilisateur_produit');
        $this->addSql('DROP TABLE im22_utilisateur_produit');
        $this->addSql('CREATE TABLE im22_utilisateur_produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_utilisateur INTEGER NOT NULL, id_produit INTEGER NOT NULL, quantite INTEGER NOT NULL)');
        $this->addSql('INSERT INTO im22_utilisateur_produit (id, id_utilisateur, id_produit, quantite) SELECT id, id_utilisateur, id_produit, quantite FROM __temp__im22_utilisateur_produit');
        $this->addSql('DROP TABLE __temp__im22_utilisateur_produit');
        $this->addSql('CREATE INDEX IDX_A38D043C50EAE44 ON im22_utilisateur_produit (id_utilisateur)');
        $this->addSql('CREATE INDEX IDX_A38D043CF7384557 ON im22_utilisateur_produit (id_produit)');
        $this->addSql('CREATE UNIQUE INDEX up_idx ON im22_utilisateur_produit (id_utilisateur, id_produit, quantite)');
    }
}
