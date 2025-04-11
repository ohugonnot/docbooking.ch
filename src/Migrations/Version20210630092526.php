<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210630092526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, doctor_id INT DEFAULT NULL, product_name VARCHAR(255) DEFAULT NULL, product_sku VARCHAR(255) DEFAULT NULL, product_price NUMERIC(5, 2) DEFAULT NULL, product_subtotal NUMERIC(5, 2) DEFAULT NULL, product_total NUMERIC(5, 2) DEFAULT NULL, status SMALLINT DEFAULT NULL, is_payed SMALLINT DEFAULT NULL, date_paied DATETIME DEFAULT NULL, order_id VARCHAR(255) DEFAULT NULL, app_date DATE DEFAULT NULL, app_time TIME DEFAULT NULL, create_time DATETIME DEFAULT NULL, INDEX IDX_FE38F8446B899279 (patient_id), INDEX IDX_FE38F84487F4FB17 (doctor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE awards (id INT AUTO_INCREMENT NOT NULL, doctor_id INT DEFAULT NULL, awards VARCHAR(255) DEFAULT NULL, year INT DEFAULT NULL, INDEX IDX_25EAE3FE87F4FB17 (doctor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clinic (id INT AUTO_INCREMENT NOT NULL, doctor_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, images LONGTEXT DEFAULT NULL, INDEX IDX_783F8B487F4FB17 (doctor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doctor (id INT AUTO_INCREMENT NOT NULL, doctor_social_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, gender VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, date_birth DATE DEFAULT NULL, about_me LONGTEXT DEFAULT NULL, address_line_1 VARCHAR(255) DEFAULT NULL, address_line_2 VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, picture_profile VARCHAR(255) DEFAULT NULL, receiving_patient_info VARCHAR(255) DEFAULT NULL, price_type VARCHAR(255) DEFAULT NULL, price_custom_value INT DEFAULT NULL, speciality VARCHAR(255) DEFAULT NULL, services LONGTEXT DEFAULT NULL, specialization LONGTEXT DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', create_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, business_hours LONGTEXT DEFAULT NULL, latitude NUMERIC(20, 16) DEFAULT NULL, longitude NUMERIC(20, 16) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, spoken_languages LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', title VARCHAR(255) DEFAULT NULL, lang_other VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1FC0F36AE7927C74 (email), UNIQUE INDEX UNIQ_1FC0F36A989D9B62 (slug), INDEX IDX_1FC0F36A30F93ACC (doctor_social_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doctor_reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_82473C21A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doctor_social (id INT AUTO_INCREMENT NOT NULL, website_url VARCHAR(255) DEFAULT NULL, facebook_url VARCHAR(255) DEFAULT NULL, twitter_url VARCHAR(255) DEFAULT NULL, instagram_url VARCHAR(255) DEFAULT NULL, pinterest_url VARCHAR(255) DEFAULT NULL, linkedin_url VARCHAR(255) DEFAULT NULL, youtube_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE education (id INT AUTO_INCREMENT NOT NULL, doctor_id INT DEFAULT NULL, degree VARCHAR(255) DEFAULT NULL, college_institute VARCHAR(255) DEFAULT NULL, year_completion VARCHAR(255) DEFAULT NULL, INDEX IDX_DB0A5ED287F4FB17 (doctor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, doctor_id INT DEFAULT NULL, hospital_name VARCHAR(255) DEFAULT NULL, experience_from DATE DEFAULT NULL, experience_to DATE DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, INDEX IDX_590C10387F4FB17 (doctor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE memberships (id INT AUTO_INCREMENT NOT NULL, doctor_id INT DEFAULT NULL, memberships VARCHAR(255) DEFAULT NULL, INDEX IDX_865A477687F4FB17 (doctor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, blood_group VARCHAR(255) DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, date_birth DATE DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, picture_profile VARCHAR(255) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', create_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, insurance VARCHAR(255) DEFAULT NULL, insurance_num VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1ADAD7EBE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient_reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_67C62FAEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registrations (id INT AUTO_INCREMENT NOT NULL, doctor_id INT DEFAULT NULL, registrations VARCHAR(255) DEFAULT NULL, year INT DEFAULT NULL, INDEX IDX_53DE51E787F4FB17 (doctor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timing (id INT AUTO_INCREMENT NOT NULL, doctor_id INT DEFAULT NULL, day INT DEFAULT NULL, month INT DEFAULT NULL, year INT DEFAULT NULL, times LONGTEXT DEFAULT NULL, time_slot VARCHAR(255) DEFAULT NULL, week INT DEFAULT NULL, INDEX IDX_13B196EB87F4FB17 (doctor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8446B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F84487F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE awards ADD CONSTRAINT FK_25EAE3FE87F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE clinic ADD CONSTRAINT FK_783F8B487F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE doctor ADD CONSTRAINT FK_1FC0F36A30F93ACC FOREIGN KEY (doctor_social_id) REFERENCES doctor_social (id)');
        $this->addSql('ALTER TABLE doctor_reset_password_request ADD CONSTRAINT FK_82473C21A76ED395 FOREIGN KEY (user_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE education ADD CONSTRAINT FK_DB0A5ED287F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C10387F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE memberships ADD CONSTRAINT FK_865A477687F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE patient_reset_password_request ADD CONSTRAINT FK_67C62FAEA76ED395 FOREIGN KEY (user_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE registrations ADD CONSTRAINT FK_53DE51E787F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE timing ADD CONSTRAINT FK_13B196EB87F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F84487F4FB17');
        $this->addSql('ALTER TABLE awards DROP FOREIGN KEY FK_25EAE3FE87F4FB17');
        $this->addSql('ALTER TABLE clinic DROP FOREIGN KEY FK_783F8B487F4FB17');
        $this->addSql('ALTER TABLE doctor_reset_password_request DROP FOREIGN KEY FK_82473C21A76ED395');
        $this->addSql('ALTER TABLE education DROP FOREIGN KEY FK_DB0A5ED287F4FB17');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C10387F4FB17');
        $this->addSql('ALTER TABLE memberships DROP FOREIGN KEY FK_865A477687F4FB17');
        $this->addSql('ALTER TABLE registrations DROP FOREIGN KEY FK_53DE51E787F4FB17');
        $this->addSql('ALTER TABLE timing DROP FOREIGN KEY FK_13B196EB87F4FB17');
        $this->addSql('ALTER TABLE doctor DROP FOREIGN KEY FK_1FC0F36A30F93ACC');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8446B899279');
        $this->addSql('ALTER TABLE patient_reset_password_request DROP FOREIGN KEY FK_67C62FAEA76ED395');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE awards');
        $this->addSql('DROP TABLE clinic');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('DROP TABLE doctor_reset_password_request');
        $this->addSql('DROP TABLE doctor_social');
        $this->addSql('DROP TABLE education');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE memberships');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE patient_reset_password_request');
        $this->addSql('DROP TABLE registrations');
        $this->addSql('DROP TABLE timing');
    }
}
