<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190509140958 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE realization_tag (realization_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_8892105D1A26530A (realization_id), INDEX IDX_8892105DBAD26311 (tag_id), PRIMARY KEY(realization_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE realization_tag ADD CONSTRAINT FK_8892105D1A26530A FOREIGN KEY (realization_id) REFERENCES realization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE realization_tag ADD CONSTRAINT FK_8892105DBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_tag DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE article_tag ADD PRIMARY KEY (article_id, tag_id)');
        $this->addSql('ALTER TABLE article_tag RENAME INDEX idx_300b23cc7294869c TO IDX_919694F97294869C');
        $this->addSql('ALTER TABLE article_tag RENAME INDEX idx_300b23ccbad26311 TO IDX_919694F9BAD26311');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE realization_tag');
        $this->addSql('ALTER TABLE article_tag DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE article_tag ADD PRIMARY KEY (tag_id, article_id)');
        $this->addSql('ALTER TABLE article_tag RENAME INDEX idx_919694f9bad26311 TO IDX_300B23CCBAD26311');
        $this->addSql('ALTER TABLE article_tag RENAME INDEX idx_919694f97294869c TO IDX_300B23CC7294869C');
    }
}
