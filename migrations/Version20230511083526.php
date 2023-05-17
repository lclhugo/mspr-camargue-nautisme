<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230511083526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD location_id INT NOT NULL, DROP location');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495564D218E FOREIGN KEY (location_id) REFERENCES rental_location (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42C8495564D218E ON reservation (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495564D218E');
        $this->addSql('DROP INDEX UNIQ_42C8495564D218E ON reservation');
        $this->addSql('ALTER TABLE reservation ADD location VARCHAR(255) NOT NULL, DROP location_id');
    }
}
