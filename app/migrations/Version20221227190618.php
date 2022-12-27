<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221227190618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    /** @SuppressWarnings(PHPMD.ShortMethodName) */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE articles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE articles ('
            . 'id INT NOT NULL, '
            . 'author_id INT NOT NULL, '
            . 'slug VARCHAR(128) NOT NULL, '
            . 'title VARCHAR(128) NOT NULL, '
            . 'description VARCHAR(255) NOT NULL, '
            . 'body TEXT NOT NULL, '
            . 'created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, '
            . 'updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, '
            . 'PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BFDD3168989D9B62 ON articles (slug)');
        $this->addSql('CREATE INDEX IDX_BFDD3168F675F31B ON articles (author_id)');
        $this->addSql('COMMENT ON COLUMN articles.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN articles.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE articles '
            . 'ADD CONSTRAINT FK_BFDD3168F675F31B FOREIGN KEY (author_id) '
            . 'REFERENCES profiles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE articles_id_seq CASCADE');
        $this->addSql('ALTER TABLE articles DROP CONSTRAINT FK_BFDD3168F675F31B');
        $this->addSql('DROP TABLE articles');
    }
}
