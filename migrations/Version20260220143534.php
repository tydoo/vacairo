<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260220143534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD date_debut_formation_s1_s2 DATE DEFAULT NULL, ADD date_fin_formation_s1_s2 DATE DEFAULT NULL, ADD date_debut_formation_s3_s4 DATE DEFAULT NULL, ADD date_fin_formation_s3_s4 DATE DEFAULT NULL, ADD date_debut_contrat DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP date_debut_formation_s1_s2, DROP date_fin_formation_s1_s2, DROP date_debut_formation_s3_s4, DROP date_fin_formation_s3_s4, DROP date_debut_contrat');
    }
}
