<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230905165807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produto (id INT AUTO_INCREMENT NOT NULL, categoria_id INT NOT NULL, usuario_id INT NOT NULL, nome VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL, valor DOUBLE PRECISION NOT NULL, is_adquirido TINYINT(1) NOT NULL, INDEX IDX_5CAC49D73397707A (categoria_id), UNIQUE INDEX UNIQ_5CAC49D7DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produto ADD CONSTRAINT FK_5CAC49D73397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE produto ADD CONSTRAINT FK_5CAC49D7DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produto DROP FOREIGN KEY FK_5CAC49D73397707A');
        $this->addSql('ALTER TABLE produto DROP FOREIGN KEY FK_5CAC49D7DB38439E');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE produto');
        $this->addSql('DROP TABLE user');
    }
}
