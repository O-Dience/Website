<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200717135656 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE announcement CHANGE status status TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE status status TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user_social CHANGE social_id social_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE announcement CHANGE status status INT NOT NULL');
        $this->addSql('ALTER TABLE announcement_report DROP is_confirmed');
        $this->addSql('ALTER TABLE user CHANGE status status INT NOT NULL');
        $this->addSql('ALTER TABLE user_report DROP is_confirmed');
        $this->addSql('ALTER TABLE user_social CHANGE social_id social_id INT NOT NULL');
    }
}
