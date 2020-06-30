<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200625191028 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE announcement_fav');
        $this->addSql('CREATE TABLE announcement_fav (id INT AUTO_INCREMENT NOT NULL, announcement_id INT NOT NULL, user_id INT NOT NULL, fav_at DATETIME NOT NULL, INDEX IDX_9E0D37D1913AEA17 (announcement_id), INDEX IDX_9E0D37D1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE announcement_fav ADD CONSTRAINT FK_9E0D37D1913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id)');
        $this->addSql('ALTER TABLE announcement_fav ADD CONSTRAINT FK_9E0D37D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        
    }
}