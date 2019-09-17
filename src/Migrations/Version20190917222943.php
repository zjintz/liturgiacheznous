<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190917222943 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE email_subscription CHANGE periodicity periodicity VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE liturgy CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE is_solemnity is_solemnity TINYINT(1) DEFAULT NULL, CHANGE is_solemnity_vfc is_solemnity_vfc TINYINT(1) DEFAULT NULL, CHANGE is_celebration is_celebration TINYINT(1) DEFAULT NULL, CHANGE is_celebration_vfc is_celebration_vfc TINYINT(1) DEFAULT NULL, CHANGE is_memorial is_memorial TINYINT(1) DEFAULT NULL, CHANGE is_memorial_vfc is_memorial_vfc TINYINT(1) DEFAULT NULL, CHANGE is_memorial_free is_memorial_free TINYINT(1) DEFAULT NULL, CHANGE alleluia_verse alleluia_verse VARCHAR(255) DEFAULT NULL, CHANGE alleluia_reference alleluia_reference VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user_user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL, CHANGE date_of_birth date_of_birth DATETIME DEFAULT NULL, CHANGE firstname firstname VARCHAR(64) DEFAULT NULL, CHANGE lastname lastname VARCHAR(64) DEFAULT NULL, CHANGE website website VARCHAR(64) DEFAULT NULL, CHANGE biography biography VARCHAR(1000) DEFAULT NULL, CHANGE gender gender VARCHAR(1) DEFAULT NULL, CHANGE locale locale VARCHAR(8) DEFAULT NULL, CHANGE timezone timezone VARCHAR(64) DEFAULT NULL, CHANGE phone phone VARCHAR(64) DEFAULT NULL, CHANGE facebook_uid facebook_uid VARCHAR(255) DEFAULT NULL, CHANGE facebook_name facebook_name VARCHAR(255) DEFAULT NULL, CHANGE facebook_data facebook_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE twitter_uid twitter_uid VARCHAR(255) DEFAULT NULL, CHANGE twitter_name twitter_name VARCHAR(255) DEFAULT NULL, CHANGE twitter_data twitter_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE gplus_uid gplus_uid VARCHAR(255) DEFAULT NULL, CHANGE gplus_name gplus_name VARCHAR(255) DEFAULT NULL, CHANGE gplus_data gplus_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE token token VARCHAR(255) DEFAULT NULL, CHANGE two_step_code two_step_code VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE email_subscription CHANGE periodicity periodicity VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE fos_user_user CHANGE salt salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\', CHANGE date_of_birth date_of_birth DATETIME DEFAULT \'NULL\', CHANGE firstname firstname VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE lastname lastname VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE website website VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE biography biography VARCHAR(1000) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE gender gender VARCHAR(1) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE locale locale VARCHAR(8) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE timezone timezone VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE phone phone VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE facebook_uid facebook_uid VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE facebook_name facebook_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE facebook_data facebook_data LONGTEXT DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:json)\', CHANGE twitter_uid twitter_uid VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE twitter_name twitter_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE twitter_data twitter_data LONGTEXT DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:json)\', CHANGE gplus_uid gplus_uid VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE gplus_name gplus_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE gplus_data gplus_data LONGTEXT DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:json)\', CHANGE token token VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE two_step_code two_step_code VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE liturgy CHANGE description description VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE is_solemnity is_solemnity TINYINT(1) DEFAULT \'NULL\', CHANGE is_solemnity_vfc is_solemnity_vfc TINYINT(1) DEFAULT \'NULL\', CHANGE is_celebration is_celebration TINYINT(1) DEFAULT \'NULL\', CHANGE is_celebration_vfc is_celebration_vfc TINYINT(1) DEFAULT \'NULL\', CHANGE is_memorial is_memorial TINYINT(1) DEFAULT \'NULL\', CHANGE is_memorial_vfc is_memorial_vfc TINYINT(1) DEFAULT \'NULL\', CHANGE is_memorial_free is_memorial_free TINYINT(1) DEFAULT \'NULL\', CHANGE alleluia_verse alleluia_verse VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE alleluia_reference alleluia_reference VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
