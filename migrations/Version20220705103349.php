<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220705103349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE director (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE genre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, director_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, poster VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_1D5EF26F899FB366 ON movie (director_id)');
        $this->addSql('CREATE TABLE movie_genre (movie_id INTEGER NOT NULL, genre_id INTEGER NOT NULL, PRIMARY KEY(movie_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_FD1229648F93B6FC ON movie_genre (movie_id)');
        $this->addSql('CREATE INDEX IDX_FD1229644296D31F ON movie_genre (genre_id)');
        $this->addSql('CREATE TABLE movie_actor (movie_id INTEGER NOT NULL, actor_id INTEGER NOT NULL, PRIMARY KEY(movie_id, actor_id))');
        $this->addSql('CREATE INDEX IDX_3A374C658F93B6FC ON movie_actor (movie_id)');
        $this->addSql('CREATE INDEX IDX_3A374C6510DAF24A ON movie_actor (actor_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, google_id VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , name VARCHAR(255) NOT NULL, picture VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64976F5C865 ON user (google_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE director');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_genre');
        $this->addSql('DROP TABLE movie_actor');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
