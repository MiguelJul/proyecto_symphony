<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221210071213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE membre (id INT AUTO_INCREMENT NOT NULL, equip_id INT NOT NULL, nom VARCHAR(50) NOT NULL, cognoms VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL, imatge_perfil VARCHAR(255) NOT NULL, data_naiximent DATE NOT NULL, nota DOUBLE PRECISION DEFAULT NULL, INDEX IDX_F6B4FB298AC49FD9 (equip_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB298AC49FD9 FOREIGN KEY (equip_id) REFERENCES equip (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY FK_F6B4FB298AC49FD9');
        $this->addSql('DROP TABLE membre');
    }
}
