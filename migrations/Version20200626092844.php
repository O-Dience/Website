<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200626092844 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE announcement_report (id INT AUTO_INCREMENT NOT NULL, reporter_id INT NOT NULL, announcement_id INT NOT NULL, INDEX IDX_DCFAC1DFE1CFE6F5 (reporter_id), INDEX IDX_DCFAC1DF913AEA17 (announcement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE announcement_report ADD CONSTRAINT FK_DCFAC1DFE1CFE6F5 FOREIGN KEY (reporter_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE announcement_report ADD CONSTRAINT FK_DCFAC1DF913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcement (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE announcement_report');
    }
}