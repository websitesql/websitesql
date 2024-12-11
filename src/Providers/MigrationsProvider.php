<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

use Medoo\Medoo;
use Exception;

class MigrationsProvider
{
    /*
     * This object will hold the Medoo database object
     *
     * @var Medoo
     */
    private $database;

    /*
     * This string will hold the path to the Migrations directory
     *
     * @var string
     */
    private $migrationsPath;
    
    /*
     * This array will hold the migration classes
     *
     * @var array
     */
    private $migrations = [];

    /*
     * Constructor
     *
     * @param Medoo $database
     * @param StringsProvider $stringsProvider
     */
    public function __construct(Medoo $database, string $migrationsPath = null)
    {
        $this->database = $database;
        $this->migrationsPath = $migrationsPath ?? realpath(__DIR__ . '/../Migrations');
    }

    /*
     * Initialize the migrations provider
     * 
     * @return void
     */
    public function init(): void
    {
        // Initialize the migration table
        $this->initMigrationTable();

        // Initialize the migration classes
        $this->initMigrationClasses();
    }

    /*
     * This method initializes the migration table
     * 
     * @return void
     */
    private function initMigrationTable(): void
    {
        // Create the migrations table
        $this->database->create('wsql_migrations', [
            'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'version' => ['VARCHAR(14)', 'NOT NULL'],
            'batch' => ['INT', 'NOT NULL'],
            'created_at' => ['DATETIME', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP']
        ]);
    }

    /*
     * This method scans the Migrations directory and returns the migration classes
     *
     * @return void
     */
    private function initMigrationClasses(): void
    {
        // Scan the Migrations directory
        $migrationFiles = scandir($this->migrationsPath);

        foreach ($migrationFiles as $migrationFile) {
            // Skip the . and .. directories
            if ($migrationFile === '.' || $migrationFile === '..') {
                continue;
            }

            // Include the migration file
            require_once $this->migrationsPath . '/' . $migrationFile;

            // Get the class name
            $className = 'WebsiteSQL\WebsiteSQL\Migrations\\' . pathinfo($migrationFile, PATHINFO_FILENAME);

            // Create a new instance of the class
            $migration = new $className;

            // Add the migration to the migrations array
            $this->migrations[] = $migration;
        }
    }

    /*
     * This method runs the migrations
     *
     * @return bool
     */
    public function run(): bool
    {
        try {
            // Get the latest batch, defaulting to 0 if no records exist
            $latestBatch = $this->database->max('wsql_migrations', 'batch') ?? 0;

            // Increment the batch
            $batch = (int)$latestBatch + 1;

            // Get the latest version, defaulting to 0 if no records exist
            $latestVersion = $this->database->max('wsql_migrations', 'version') ?? 0;

            // Loop through the migrations
            foreach ($this->migrations as $migration) {
                // Run the migration
                try {
                    // Get the version of the migration
                    $version = str_replace('Version', '', str_replace('WebsiteSQL\WebsiteSQL\Migrations\\', '', get_class($migration)));
                    
                    // Check if the migration has already been run
                    if ((int)$version <= (int)$latestVersion) {
                        continue;
                    }
                    
                    $migration->up($this->database);

                    // Insert the migration into the migrations table
                    $this->database->insert('wsql_migrations', [
                        'version' => $version,
                        'batch' => $batch
                    ]);
                } catch (Exception $e) {
                    throw new Exception('Migration failed: ' . $e->getMessage());
                }
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /*
     * This method rolls back the migrations
     *
     * @return bool
     */
    public function rollback(): bool
    {
        try {
            // Get the latest batch
            $latestBatch = $this->database->max('wsql_migrations', 'batch');

            // Get the migrations in the latest batch
            $migrations = $this->database->select('wsql_migrations', '*', ['batch' => $latestBatch]);

            // Loop through the migrations
            foreach ($migrations as $migration) {
                // Get the version of the migration
                $version = $migration['version'];

                // Get the migration class
                $className = 'WebsiteSQL\WebsiteSQL\Migrations\Version' . $version;
                $migration = new $className;

                // Roll back the migration
                $migration->down($this->database);

                // Delete the migration from the migrations table
                $this->database->delete('wsql_migrations', ['version' => $version]);
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}