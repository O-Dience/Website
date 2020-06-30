<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200615145031 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE announcement_social_network (announcement_id INT NOT NULL, social_network_id INT NOT NULL, INDEX IDX_B67596F4913AEA17 (announcement_id), INDEX IDX_B67596F4FA413953 (social_network_id), PRIMARY KEY(announcement_id, social_network_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE announcement_social_network ADD CONSTRAINT FK_B67596F4913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE announcement_social_network ADD CONSTRAINT FK_B67596F4FA413953 FOREIGN KEY (social_network_id) REFERENCES social_network (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE announcement_social_network');
    }
}