<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307090215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE todo_list ADD user_id INT DEFAULT NULL, ADD comment_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE todo_list ADD CONSTRAINT FK_1B199E07A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE todo_list ADD CONSTRAINT FK_1B199E07541DB185 FOREIGN KEY (comment_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1B199E07A76ED395 ON todo_list (user_id)');
        $this->addSql('CREATE INDEX IDX_1B199E07541DB185 ON todo_list (comment_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE todo_list DROP FOREIGN KEY FK_1B199E07A76ED395');
        $this->addSql('ALTER TABLE todo_list DROP FOREIGN KEY FK_1B199E07541DB185');
        $this->addSql('DROP INDEX IDX_1B199E07A76ED395 ON todo_list');
        $this->addSql('DROP INDEX IDX_1B199E07541DB185 ON todo_list');
        $this->addSql('ALTER TABLE todo_list DROP user_id, DROP comment_user_id');
    }
}
