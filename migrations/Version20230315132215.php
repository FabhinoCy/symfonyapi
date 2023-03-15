<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230315132215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character` ADD origin_id INT DEFAULT NULL, ADD location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB03456A273CC FOREIGN KEY (origin_id) REFERENCES origin (id)');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB03464D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_937AB03456A273CC ON `character` (origin_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_937AB03464D218E ON `character` (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB03456A273CC');
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB03464D218E');
        $this->addSql('DROP INDEX UNIQ_937AB03456A273CC ON `character`');
        $this->addSql('DROP INDEX UNIQ_937AB03464D218E ON `character`');
        $this->addSql('ALTER TABLE `character` DROP origin_id, DROP location_id');
    }
}
