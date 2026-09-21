<?php
/**
 * Mahin Travel & Tours - Database Connection Manager
 * Dual Engine: MySQL (Primary for cPanel) + SQLite (Fallback for zero-config local dev)
 */

require_once __DIR__ . '/config.php';

class Database {
    private static ?PDO $instance = null;
    private static string $driverType = 'mysql';

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            self::connect();
        }
        return self::$instance;
    }

    public static function getDriverType(): string {
        return self::$driverType;
    }

    private static function connect(): void {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        // 1. Try MySQL First (Primary for cPanel Shared Hosting)
        $useSqlite = (getenv('DB_DRIVER') === 'sqlite');
        
        if (!$useSqlite) {
            try {
                $dsn = sprintf(
                    "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                    DB_HOST,
                    DB_PORT,
                    DB_NAME,
                    DB_CHARSET
                );
                
                // Low connection timeout so fallback is instant if MySQL daemon is not running
                $mysqlOptions = $options + [
                    PDO::ATTR_TIMEOUT => 2
                ];

                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $mysqlOptions);
                self::$driverType = 'mysql';
                return;
            } catch (PDOException $e) {
                // If MySQL is not running or db not found, fallback to SQLite for local development
                error_log("MySQL connection notice: " . $e->getMessage() . ". Falling back to local SQLite engine.");
            }
        }

        // 2. Fallback to Local SQLite (Zero-Configuration Local Development)
        try {
            $sqlitePath = __DIR__ . '/database.sqlite';
            $isNew = !file_exists($sqlitePath) || filesize($sqlitePath) === 0;
            
            $dsn = "sqlite:" . $sqlitePath;
            self::$instance = new PDO($dsn, null, null, $options);
            self::$driverType = 'sqlite';

            // Auto-initialize tables if newly created
            if ($isNew) {
                require_once __DIR__ . '/../scripts/init_sqlite.php';
                if (function_exists('initializeSqliteDatabase')) {
                    initializeSqliteDatabase(self::$instance);
                }
            }
        } catch (PDOException $e) {
            die("Database Initialization Error: " . htmlspecialchars($e->getMessage()));
        }
    }
}

/**
 * Global helper function to get database PDO instance
 */
function getDB(): PDO {
    return Database::getConnection();
}
