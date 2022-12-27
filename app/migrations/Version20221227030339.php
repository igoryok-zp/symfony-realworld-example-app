<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221227030339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    /** @SuppressWarnings(PHPMD.ShortMethodName) */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE profiles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE profiles ('
            . 'id INT NOT NULL, '
            . 'username VARCHAR(32) NOT NULL, '
            . 'bio VARCHAR(255) DEFAULT \'\' NOT NULL, '
            . 'image VARCHAR(255) DEFAULT NULL, '
            . 'created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, '
            . 'updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, '
            . 'PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8B308530F85E0677 ON profiles (username)');
        $this->addSql('COMMENT ON COLUMN profiles.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN profiles.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE users ADD profile_id INT NOT NULL');
        $this->addSql('ALTER TABLE users '
            . 'ADD CONSTRAINT FK_1483A5E9CCFA12B8 FOREIGN KEY (profile_id) '
            . 'REFERENCES profiles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9CCFA12B8 ON users (profile_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9CCFA12B8');
        $this->addSql('DROP SEQUENCE profiles_id_seq CASCADE');
        $this->addSql('DROP TABLE profiles');
        $this->addSql('DROP INDEX UNIQ_1483A5E9CCFA12B8');
        $this->addSql('ALTER TABLE users DROP profile_id');
    }
}
