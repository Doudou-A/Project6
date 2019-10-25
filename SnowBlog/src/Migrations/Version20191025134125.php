<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191025134125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37AA76ED395');
        $this->addSql('DROP INDEX IDX_2F57B37AA76ED395 ON figure');
        $this->addSql('ALTER TABLE figure CHANGE user_id users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37A67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2F57B37A67B3B43D ON figure (users_id)');
        $this->addSql('ALTER TABLE figure_forum DROP FOREIGN KEY FK_970638F45C011B5');
        $this->addSql('ALTER TABLE figure_forum DROP FOREIGN KEY FK_970638F4A76ED395');
        $this->addSql('DROP INDEX IDX_970638F45C011B5 ON figure_forum');
        $this->addSql('DROP INDEX IDX_970638F4A76ED395 ON figure_forum');
        $this->addSql('ALTER TABLE figure_forum ADD users_id INT DEFAULT NULL, ADD figures_id INT DEFAULT NULL, DROP user_id, DROP figure_id');
        $this->addSql('ALTER TABLE figure_forum ADD CONSTRAINT FK_970638F467B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE figure_forum ADD CONSTRAINT FK_970638F45C7F3A37 FOREIGN KEY (figures_id) REFERENCES figure (id)');
        $this->addSql('CREATE INDEX IDX_970638F467B3B43D ON figure_forum (users_id)');
        $this->addSql('CREATE INDEX IDX_970638F45C7F3A37 ON figure_forum (figures_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37A67B3B43D');
        $this->addSql('DROP INDEX IDX_2F57B37A67B3B43D ON figure');
        $this->addSql('ALTER TABLE figure CHANGE users_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2F57B37AA76ED395 ON figure (user_id)');
        $this->addSql('ALTER TABLE figure_forum DROP FOREIGN KEY FK_970638F467B3B43D');
        $this->addSql('ALTER TABLE figure_forum DROP FOREIGN KEY FK_970638F45C7F3A37');
        $this->addSql('DROP INDEX IDX_970638F467B3B43D ON figure_forum');
        $this->addSql('DROP INDEX IDX_970638F45C7F3A37 ON figure_forum');
        $this->addSql('ALTER TABLE figure_forum ADD user_id INT DEFAULT NULL, ADD figure_id INT DEFAULT NULL, DROP users_id, DROP figures_id');
        $this->addSql('ALTER TABLE figure_forum ADD CONSTRAINT FK_970638F45C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE figure_forum ADD CONSTRAINT FK_970638F4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_970638F45C011B5 ON figure_forum (figure_id)');
        $this->addSql('CREATE INDEX IDX_970638F4A76ED395 ON figure_forum (user_id)');
    }
}
