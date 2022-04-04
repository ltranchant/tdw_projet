<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220404121501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE im22_utilisateur_produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_utilisateur INTEGER NOT NULL, id_produit INTEGER NOT NULL, quantite INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_A38D043C50EAE44 ON im22_utilisateur_produit (id_utilisateur)');
        $this->addSql('CREATE INDEX IDX_A38D043CF7384557 ON im22_utilisateur_produit (id_produit)');
        $this->addSql('CREATE UNIQUE INDEX up_idx ON im22_utilisateur_produit (id_utilisateur, id_produit, quantite)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE im22_utilisateur_produit');
    }
}
