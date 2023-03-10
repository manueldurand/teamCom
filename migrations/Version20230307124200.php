<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307124200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE todo_list DROP FOREIGN KEY FK_1B199E07541DB185');
        $this->addSql('DROP INDEX IDX_1B199E07541DB185 ON todo_list');
        $this->addSql('ALTER TABLE todo_list DROP comment_user_id, DROP auteur');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE todo_list ADD comment_user_id INT DEFAULT NULL, ADD auteur VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE todo_list ADD CONSTRAINT FK_1B199E07541DB185 FOREIGN KEY (comment_user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1B199E07541DB185 ON todo_list (comment_user_id)');
    }
}
