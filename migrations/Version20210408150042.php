<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210408150042 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE images_gallery (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, image LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images_gallery_product (images_gallery_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_AAF5AB0EB5418200 (images_gallery_id), INDEX IDX_AAF5AB0E4584665A (product_id), PRIMARY KEY(images_gallery_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images_gallery_product ADD CONSTRAINT FK_AAF5AB0EB5418200 FOREIGN KEY (images_gallery_id) REFERENCES images_gallery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE images_gallery_product ADD CONSTRAINT FK_AAF5AB0E4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images_gallery_product DROP FOREIGN KEY FK_AAF5AB0EB5418200');
        $this->addSql('DROP TABLE images_gallery');
        $this->addSql('DROP TABLE images_gallery_product');
    }
}
