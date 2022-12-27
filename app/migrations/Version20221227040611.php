<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221227040611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    /** @SuppressWarnings(PHPMD.ShortMethodName) */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE followers ('
            . 'profile_id INT NOT NULL, '
            . 'follower_id INT NOT NULL, '
            . 'created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, '
            . 'PRIMARY KEY(profile_id, follower_id))');
        $this->addSql('CREATE INDEX IDX_8408FDA7CCFA12B8 ON followers (profile_id)');
        $this->addSql('CREATE INDEX IDX_8408FDA7AC24F853 ON followers (follower_id)');
        $this->addSql('COMMENT ON COLUMN followers.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE followers '
            . 'ADD CONSTRAINT FK_8408FDA7CCFA12B8 FOREIGN KEY (profile_id) '
            . 'REFERENCES profiles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE followers '
            . 'ADD CONSTRAINT FK_8408FDA7AC24F853 FOREIGN KEY (follower_id) '
            . 'REFERENCES profiles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE followers DROP CONSTRAINT FK_8408FDA7CCFA12B8');
        $this->addSql('ALTER TABLE followers DROP CONSTRAINT FK_8408FDA7AC24F853');
        $this->addSql('DROP TABLE followers');
    }
}
