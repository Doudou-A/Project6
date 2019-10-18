<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191018065056 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE figure (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, summary VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, date_created DATETIME NOT NULL, date_last_update DATETIME DEFAULT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_2F57B37AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE figure_forum ADD figure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE figure_forum ADD CONSTRAINT FK_970638F45C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('CREATE INDEX IDX_970638F45C011B5 ON figure_forum (figure_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE figure_forum DROP FOREIGN KEY FK_970638F45C011B5');
        $this->addSql('DROP TABLE figure');
        $this->addSql('DROP INDEX IDX_970638F45C011B5 ON figure_forum');
        $this->addSql('ALTER TABLE figure_forum DROP figure_id');
    }
}
