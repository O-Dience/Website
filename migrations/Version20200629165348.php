<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200629165348 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_report (id INT AUTO_INCREMENT NOT NULL, reporter_id INT NOT NULL, reportee_id INT NOT NULL, INDEX IDX_A17D6CB9E1CFE6F5 (reporter_id), INDEX IDX_A17D6CB92C0189D3 (reportee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_report ADD CONSTRAINT FK_A17D6CB9E1CFE6F5 FOREIGN KEY (reporter_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_report ADD CONSTRAINT FK_A17D6CB92C0189D3 FOREIGN KEY (reportee_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE announcement_report ADD is_confirmed TINYINT(1) NOT NULL, DROP reason');
        $this->addSql('ALTER TABLE user_report ADD is_confirmed TINYINT(1) NOT NULL, DROP reason');
        $this->addSql('ALTER TABLE announcement CHANGE status status TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE status status TINYINT(1) NOT NULL');
        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_report');
        $this->addSql('ALTER TABLE announcement CHANGE status status INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE status status INT NOT NULL');
        $this->addSql('ALTER TABLE announcement_report ADD reason VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE utf8mb4_unicode_ci, DROP is_confirmed');
        $this->addSql('ALTER TABLE user_report ADD reason VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE utf8mb4_unicode_ci, DROP is_confirmed');
    }
}
