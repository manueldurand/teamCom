<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230309110434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE view_post (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_A430A0D14B89032C (post_id), INDEX IDX_A430A0D1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE view_post ADD CONSTRAINT FK_A430A0D14B89032C FOREIGN KEY (post_id) REFERENCES todo_list (id)');
        $this->addSql('ALTER TABLE view_post ADD CONSTRAINT FK_A430A0D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE view_post DROP FOREIGN KEY FK_A430A0D14B89032C');
        $this->addSql('ALTER TABLE view_post DROP FOREIGN KEY FK_A430A0D1A76ED395');
        $this->addSql('DROP TABLE view_post');
    }
}
