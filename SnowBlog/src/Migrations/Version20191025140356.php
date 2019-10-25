<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191025140356 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE forum (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, figure_id INT DEFAULT NULL, content LONGTEXT NOT NULL, date_created DATETIME NOT NULL, INDEX IDX_852BBECDA76ED395 (user_id), INDEX IDX_852BBECD5C011B5 (figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECD5C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('DROP TABLE figure_forum');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE figure_forum (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, figure_id INT DEFAULT NULL, content LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, date_created DATETIME NOT NULL, INDEX IDX_970638F45C011B5 (figure_id), INDEX IDX_970638F4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE figure_forum ADD CONSTRAINT FK_970638F45C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE figure_forum ADD CONSTRAINT FK_970638F4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('DROP TABLE forum');
    }
}
