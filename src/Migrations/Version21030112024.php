<?php declare(strict_types=1); // Migration: 2.1.0

namespace WebsiteSQL\WebsiteSQL\Migrations;

use Medoo\Medoo;

class Version21030112024
{
    public function up(Medoo $database)
    {
        // Create the 'wsql_activity' table
        $database->create('wsql_activity', [
            'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'user' => ['INT', 'NULL'],
            'action' => ['VARCHAR(150)', 'NOT NULL'],
            'target' => ['VARCHAR(150)', 'NULL'],
            'record' => ['VARCHAR(36)', 'NULL'],
            'data' => ['TEXT', 'NULL'],
            'created_at' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['INT', 'NULL'],
        ]);

        // Create the 'wsql_customizations' table
        $database->create('wsql_customizations', [
            'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'user' => ['INT', 'NULL'],
            'name' => ['VARCHAR(150)', 'NOT NULL'],
            'value' => ['TEXT', 'NULL'],
            'created_at' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['INT', 'NULL'],
        ]);

        // Create the 'wsql_media' table
        $database->create('wsql_media', [
            'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'filename' => ['VARCHAR(200)', 'NOT NULL'],
            'mimetype' => ['VARCHAR(50)', 'NOT NULL'],
            'size' => ['INT', 'NOT NULL'],
            'tag' => ['VARCHAR(255)', 'NULL'],
            'thumbnail' => ['INT', 'NULL'],
            'path' => ['VARCHAR(300)', 'NOT NULL'],
            'created_at' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['INT', 'NULL'],
        ]);

        // Create 'wsql_modules' table
        $database->create('wsql_modules', [
            'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'slug' => ['VARCHAR(100)', 'NOT NULL', 'UNIQUE'],
            'name' => ['VARCHAR(100)', 'NOT NULL'],
            'description' => ['TEXT', 'NULL'],
            'version' => ['VARCHAR(20)', 'NOT NULL'],
            'author' => ['VARCHAR(250)', 'NOT NULL'],
            'enabled' => ['INT', 'NOT NULL', 'DEFAULT 0'],
            'created_at' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP'],
        ]);

        // Create 'wsql_permissions' table
        $database->create('wsql_permissions', [
            'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'role' => ['INT', 'NOT NULL'],
            'name' => ['VARCHAR(100)', 'NOT NULL'],
            'enabled' => ['BOOL', 'NOT NULL', 'DEFAULT 0'],
            'filter' => ['TEXT', 'NULL'],
            'created_at' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['INT', 'NULL'],
        ]);

        // Create 'wsql_roles' table
        $database->create('wsql_roles', [
            'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'name' => ['VARCHAR(100)', 'NOT NULL', 'UNIQUE'],
            'description' => ['TEXT', 'NULL'],
            'public_access' => ['BOOL', 'NOT NULL', 'DEFAULT 0'],
            'app_access' => ['BOOL', 'NOT NULL', 'DEFAULT 0'],
            'administrator' => ['BOOL', 'NOT NULL', 'DEFAULT 0'],
            'created_at' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['INT', 'NULL'],
        ]);

        // Create 'wsql_tokens' table
        $database->create('wsql_tokens', [
            'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'user' => ['INT', 'NOT NULL'],
            'action' => ['VARCHAR(100)', 'NOT NULL', 'DEFAULT ' . $database->quote('authentication')],
            'token' => ['VARCHAR(255)', 'NOT NULL', 'UNIQUE'],
            'expires_at' => ['TIMESTAMP', 'NOT NULL'],
            'created_at' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP'],
        ]);

        // Create 'wsql_users' table
        $database->create('wsql_users', [
            'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'firstname' => ['VARCHAR(100)', 'NOT NULL'],
            'lastname' => ['VARCHAR(100)', 'NOT NULL'],
            'email' => ['VARCHAR(250)', 'NOT NULL', 'UNIQUE'],
            'role' => ['INT', 'NOT NULL'],
            'password' => ['TEXT', 'NOT NULL'],
            'approved' => ['INT(11)', 'NOT NULL', 'DEFAULT 0'],
            'locked' => ['INT(11)', 'NOT NULL', 'DEFAULT 0'],
            'email_verified' => ['INT(11)', 'NOT NULL', 'DEFAULT 0'],
            'email_verify_code' => ['VARCHAR(250)', 'NULL'],
            'email_verify_expiry' => ['TIMESTAMP', 'NULL'],
            'created_at' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP'],
            'created_by' => ['INT', 'NULL'],
        ]);
    }

    public function down(Medoo $database)
    {
        // Drop the 'wsql_activity' table
        $database->drop('wsql_activity');

        // Drop the 'wsql_customizations' table
        $database->drop('wsql_customizations');

        // Drop the 'wsql_media' table
        $database->drop('wsql_media');

        // Drop the 'wsql_modules' table
        $database->drop('wsql_modules');

        // Drop the 'wsql_permissions' table
        $database->drop('wsql_permissions');

        // Drop the 'wsql_roles' table
        $database->drop('wsql_roles');

        // Drop the 'wsql_tokens' table
        $database->drop('wsql_tokens');

        // Drop the 'wsql_users' table
        $database->drop('wsql_users');
    }
}