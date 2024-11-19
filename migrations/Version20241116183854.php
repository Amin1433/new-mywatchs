<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241116183854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE galerie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER NOT NULL, description VARCHAR(255) NOT NULL, publiee BOOLEAN NOT NULL, CONSTRAINT FK_9E7D159061220EA6 FOREIGN KEY (creator_id) REFERENCES member (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_9E7D159061220EA6 ON galerie (creator_id)');
        $this->addSql('CREATE TABLE galerie_watch (galerie_id INTEGER NOT NULL, watch_id INTEGER NOT NULL, PRIMARY KEY(galerie_id, watch_id), CONSTRAINT FK_18D45B0825396CB FOREIGN KEY (galerie_id) REFERENCES galerie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_18D45B0C7C58135 FOREIGN KEY (watch_id) REFERENCES watch (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_18D45B0825396CB ON galerie_watch (galerie_id)');
        $this->addSql('CREATE INDEX IDX_18D45B0C7C58135 ON galerie_watch (watch_id)');
        $this->addSql('CREATE TABLE member (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, rack_id INTEGER NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_70E4FA788E86A33E FOREIGN KEY (rack_id) REFERENCES rack (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70E4FA788E86A33E ON member (rack_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON member (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE galerie');
        $this->addSql('DROP TABLE galerie_watch');
        $this->addSql('DROP TABLE member');
    }
}
