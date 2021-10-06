<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210405150523 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE videos_gallery (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, video LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE videos_gallery_product (videos_gallery_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_80DAEED55815782F (videos_gallery_id), INDEX IDX_80DAEED54584665A (product_id), PRIMARY KEY(videos_gallery_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE videos_gallery_product ADD CONSTRAINT FK_80DAEED55815782F FOREIGN KEY (videos_gallery_id) REFERENCES videos_gallery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE videos_gallery_product ADD CONSTRAINT FK_80DAEED54584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE videos_gallery_product DROP FOREIGN KEY FK_80DAEED55815782F');
        $this->addSql('DROP TABLE videos_gallery');
        $this->addSql('DROP TABLE videos_gallery_product');
    }
}
