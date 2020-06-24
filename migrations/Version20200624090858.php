<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200624090858 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_fav (id INT AUTO_INCREMENT NOT NULL, user_like_id INT NOT NULL, user_liked_id INT NOT NULL, INDEX IDX_9D8D81E4DD96E438 (user_like_id), INDEX IDX_9D8D81E4260FC79 (user_liked_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_fav ADD CONSTRAINT FK_9D8D81E4DD96E438 FOREIGN KEY (user_like_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_fav ADD CONSTRAINT FK_9D8D81E4260FC79 FOREIGN KEY (user_liked_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_fav');
    }
}
