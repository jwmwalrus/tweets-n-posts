<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191223221557 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Tweet table; le User.twitterid unique';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Tweet (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, itemid VARCHAR(255) NOT NULL, raw LONGTEXT NOT NULL, plain LONGTEXT NOT NULL, timestamp BIGINT NOT NULL, hidden TINYINT(1) DEFAULT \'0\', INDEX IDX_FCA7253F7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Tweet ADD CONSTRAINT FK_FCA7253F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES User (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Tweet DROP FOREIGN KEY FK_FCA7253F7E3C61F9');
        $this->addSql('DROP TABLE Tweet');
    }
}
