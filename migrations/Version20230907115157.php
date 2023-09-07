<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230907115157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categoria ADD usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categoria ADD CONSTRAINT FK_4E10122DDB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4E10122DDB38439E ON categoria (usuario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categoria DROP FOREIGN KEY FK_4E10122DDB38439E');
        $this->addSql('DROP INDEX IDX_4E10122DDB38439E ON categoria');
        $this->addSql('ALTER TABLE categoria DROP usuario_id');
    }
}
