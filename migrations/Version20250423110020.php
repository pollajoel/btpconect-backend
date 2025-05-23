<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423110020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_entity ADD city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_entity ADD region VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_entity ADD postal_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_entity ADD adresse VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_entity DROP city');
        $this->addSql('ALTER TABLE user_entity DROP region');
        $this->addSql('ALTER TABLE user_entity DROP postal_code');
        $this->addSql('ALTER TABLE user_entity DROP adresse');
    }
}
