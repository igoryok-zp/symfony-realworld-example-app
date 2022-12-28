<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221228150644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    /** @SuppressWarnings(PHPMD.ShortMethodName) */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorites ('
            . 'article_id INT NOT NULL, '
            . 'profile_id INT NOT NULL, '
            . 'created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, '
            . 'PRIMARY KEY(article_id, profile_id))');
        $this->addSql('CREATE INDEX IDX_E46960F57294869C ON favorites (article_id)');
        $this->addSql('CREATE INDEX IDX_E46960F5CCFA12B8 ON favorites (profile_id)');
        $this->addSql('COMMENT ON COLUMN favorites.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE favorites '
            . 'ADD CONSTRAINT FK_E46960F57294869C FOREIGN KEY (article_id) '
            . 'REFERENCES articles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE favorites '
            . 'ADD CONSTRAINT FK_E46960F5CCFA12B8 FOREIGN KEY (profile_id) '
            . 'REFERENCES profiles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE favorites DROP CONSTRAINT FK_E46960F57294869C');
        $this->addSql('ALTER TABLE favorites DROP CONSTRAINT FK_E46960F5CCFA12B8');
        $this->addSql('DROP TABLE favorites');
    }
}
