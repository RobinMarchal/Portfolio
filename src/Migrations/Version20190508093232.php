<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190508093232 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article ADD writer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E661BC7E6B6 FOREIGN KEY (writer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_23A0E661BC7E6B6 ON article (writer_id)');
        $this->addSql('ALTER TABLE user CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E661BC7E6B6');
        $this->addSql('DROP INDEX IDX_23A0E661BC7E6B6 ON article');
        $this->addSql('ALTER TABLE article DROP writer_id');
        $this->addSql('ALTER TABLE user CHANGE is_active is_active TINYINT(1) NOT NULL');
    }
}
