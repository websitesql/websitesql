<?php declare(strict_types=1); // Migration: 2.1.0

namespace WebsiteSQL\WebsiteSQL\Migrations;

use Medoo\Medoo;

class Version21105122024
{
    public function up(Medoo $database)
    {
        // Add uuid column to 'wsql_users' table after 'id' column and make it unique
        $database->query('ALTER TABLE wsql_users ADD uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE(uuid)');

        // Generate a UUIDv4 for each user
        $users = $database->select('wsql_users', ['id']);
        foreach ($users as $user) {
            $database->update('wsql_users', ['uuid' => $this->generateUuid()], ['id' => $user['id']]);
        }

        // Add uuid column to 'wsql_roles' table after 'id' column and make it unique
        $database->query('ALTER TABLE wsql_roles ADD uuid VARCHAR(36) NULL AFTER id, ADD UNIQUE(uuid)');

        // Generate a UUIDv4 for each role
        $roles = $database->select('wsql_roles', ['id']);
        foreach ($roles as $role) {
            $database->update('wsql_roles', ['uuid' => $this->generateUuid()], ['id' => $role['id']]);
        }
    }

    public function down(Medoo $database)
    {
        // Remove uuid column from 'wsql_users' table
        $database->query('ALTER TABLE wsql_users DROP COLUMN uuid');

        // Remove uuid column from 'wsql_roles' table
        $database->query('ALTER TABLE wsql_roles DROP COLUMN uuid');
    }

    public function generateUuid(): string
    {
        // Return UUID v4
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            random_int(0, 0xffff), random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0x0fff) | 0x4000,
            random_int(0, 0x3fff) | 0x8000,
            random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff)
        );
    }
}