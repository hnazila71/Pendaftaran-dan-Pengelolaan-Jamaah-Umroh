<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to use if no other is specified.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection.
     *
     * @var array<string, mixed>
     */
    public array $default = [
        'DSN'          => '',
        'hostname'     => '127.0.0.1',
        'username'     => 'postgres',
        'password'     => '',
        'database'     => 'postgres',
        'DBDriver'     => 'Postgre',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8',
        'DBCollat'     => '',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 5432,
        'schema'       => 'public',
        'sslmode'      => 'require',
        'connect_timeout' => '10',
        'numberNative' => false,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    //    /**
    //     * Sample database connection for SQLite3.
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'database'    => 'database.db',
    //        'DBDriver'    => 'SQLite3',
    //        'DBPrefix'    => '',
    //        'DBDebug'     => true,
    //        'swapPre'     => '',
    //        'failover'    => [],
    //        'foreignKeys' => true,
    //        'busyTimeout' => 1000,
    //        'dateFormat'  => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    //    /**
    //     * Sample database connection for Postgre.
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'DSN'        => '',
    //        'hostname'   => 'localhost',
    //        'username'   => 'root',
    //        'password'   => 'root',
    //        'database'   => 'ci4',
    //        'schema'     => 'public',
    //        'DBDriver'   => 'Postgre',
    //        'DBPrefix'   => '',
    //        'pConnect'   => false,
    //        'DBDebug'    => true,
    //        'charset'    => 'utf8',
    //        'swapPre'    => '',
    //        'failover'   => [],
    //        'port'       => 5432,
    //        'dateFormat' => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    //    /**
    //     * Sample database connection for SQLSRV.
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'DSN'        => '',
    //        'hostname'   => 'localhost',
    //        'username'   => 'root',
    //        'password'   => 'root',
    //        'database'   => 'ci4',
    //        'schema'     => 'dbo',
    //        'DBDriver'   => 'SQLSRV',
    //        'DBPrefix'   => '',
    //        'pConnect'   => false,
    //        'DBDebug'    => true,
    //        'charset'    => 'utf8',
    //        'swapPre'    => '',
    //        'encrypt'    => false,
    //        'failover'   => [],
    //        'port'       => 1433,
    //        'dateFormat' => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    //    /**
    //     * Sample database connection for OCI8.
    //     *
    //     * You may need the following environment variables:
    //     *   NLS_LANG                = 'AMERICAN_AMERICA.UTF8'
    //     *   NLS_DATE_FORMAT         = 'YYYY-MM-DD HH24:MI:SS'
    //     *   NLS_TIMESTAMP_FORMAT    = 'YYYY-MM-DD HH24:MI:SS'
    //     *   NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS'
    //     *
    //     * @var array<string, mixed>
    //     */
    //    public array $default = [
    //        'DSN'        => 'localhost:1521/XEPDB1',
    //        'username'   => 'root',
    //        'password'   => 'root',
    //        'DBDriver'   => 'OCI8',
    //        'DBPrefix'   => '',
    //        'pConnect'   => false,
    //        'DBDebug'    => true,
    //        'charset'    => 'AL32UTF8',
    //        'swapPre'    => '',
    //        'failover'   => [],
    //        'dateFormat' => [
    //            'date'     => 'Y-m-d',
    //            'datetime' => 'Y-m-d H:i:s',
    //            'time'     => 'H:i:s',
    //        ],
    //    ];

    /**
     * This database connection is used when running PHPUnit database tests.
     *
     * @var array<string, mixed>
     */
    public array $tests = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => '',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
        'foreignKeys' => true,
        'busyTimeout' => 1000,
        'dateFormat'  => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        $this->default['DSN'] = (string) env('database.default.DSN', $this->default['DSN']);
        $this->default['hostname'] = (string) env('database.default.hostname', $this->default['hostname']);
        $this->default['username'] = (string) env('database.default.username', $this->default['username']);
        $this->default['password'] = (string) env('database.default.password', $this->default['password']);
        $this->default['database'] = (string) env('database.default.database', $this->default['database']);
        $this->default['DBDriver'] = (string) env('database.default.DBDriver', $this->default['DBDriver']);
        $this->default['charset'] = (string) env('database.default.charset', $this->default['charset']);
        $this->default['DBCollat'] = (string) env('database.default.DBCollat', $this->default['DBCollat']);
        $this->default['schema'] = (string) env('database.default.schema', $this->default['schema']);
        $this->default['sslmode'] = (string) env('database.default.sslmode', $this->default['sslmode']);
        $this->default['connect_timeout'] = (string) env('database.default.connect_timeout', $this->default['connect_timeout']);

        $dbDebug = env('database.default.DBDebug', null);
        if ($dbDebug !== null && $dbDebug !== '') {
            $debugValue = filter_var($dbDebug, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($debugValue !== null) {
                $this->default['DBDebug'] = $debugValue;
            }
        } else {
            $this->default['DBDebug'] = ENVIRONMENT !== 'production';
        }

        $port = env('database.default.port', null);
        if ($port !== null && $port !== '') {
            $this->default['port'] = (int) $port;
        }

        $databaseUrl = trim((string) (env('DATABASE_URL') ?: env('database.default.url') ?: ''));
        if ($databaseUrl !== '') {
            $this->applyDatabaseUrl($databaseUrl);
        }

        // Postgre only accepts "utf8", not "utf8mb4".
        if (
            strcasecmp((string) ($this->default['DBDriver'] ?? ''), 'Postgre') === 0
            && strcasecmp((string) ($this->default['charset'] ?? ''), 'utf8mb4') === 0
        ) {
            $this->default['charset'] = 'utf8';
            $this->default['DBCollat'] = '';
        }

        // Ensure that we always set the database group to 'tests' if
        // we are currently running an automated test suite, so that
        // we don't overwrite live data on accident.
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }

    private function applyDatabaseUrl(string $databaseUrl): void
    {
        $parts = parse_url($databaseUrl);
        if ($parts === false) {
            return;
        }

        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        if (! in_array($scheme, ['postgres', 'postgresql', 'pgsql'], true)) {
            return;
        }

        $queryParams = [];
        if (isset($parts['query'])) {
            parse_str((string) $parts['query'], $queryParams);
        }

        $hostname = (string) ($parts['host'] ?? $this->default['hostname']);
        $port = (int) ($parts['port'] ?? $this->default['port']);
        $database = ltrim((string) ($parts['path'] ?? ''), '/');
        $username = isset($parts['user']) ? urldecode((string) $parts['user']) : '';
        $password = isset($parts['pass']) ? urldecode((string) $parts['pass']) : '';
        $schema = (string) ($queryParams['schema'] ?? ($this->default['schema'] ?? 'public'));
        $sslmode = (string) ($queryParams['sslmode'] ?? ($this->default['sslmode'] ?? 'require'));
        $connectTimeout = (string) ($queryParams['connect_timeout'] ?? ($this->default['connect_timeout'] ?? '10'));

        $dsnParts = [];
        if ($hostname !== '') {
            $dsnParts[] = 'host=' . $hostname;
        }
        if ($port > 0) {
            $dsnParts[] = 'port=' . $port;
        }
        if ($database !== '') {
            $dsnParts[] = 'dbname=' . $database;
        }
        if ($username !== '') {
            $dsnParts[] = 'user=' . $username;
        }
        if ($password !== '') {
            $dsnParts[] = "password='" . str_replace("'", "\\'", $password) . "'";
        }

        foreach (['sslmode', 'connect_timeout', 'channel_binding', 'options', 'service'] as $param) {
            if (! isset($queryParams[$param])) {
                continue;
            }

            $value = trim((string) $queryParams[$param]);
            if ($value === '') {
                continue;
            }

            $dsnParts[] = $param . "='" . str_replace("'", "\\'", $value) . "'";
        }

        if ($sslmode !== '' && ! isset($queryParams['sslmode'])) {
            $dsnParts[] = "sslmode='" . str_replace("'", "\\'", $sslmode) . "'";
        }

        if ($connectTimeout !== '' && ! isset($queryParams['connect_timeout'])) {
            $dsnParts[] = "connect_timeout='" . str_replace("'", "\\'", $connectTimeout) . "'";
        }

        $this->default['DSN'] = implode(' ', $dsnParts);
        $this->default['DBDriver'] = 'Postgre';
        $this->default['hostname'] = $hostname;
        $this->default['port'] = $port;
        $this->default['database'] = $database;
        $this->default['username'] = $username;
        $this->default['password'] = $password;
        $this->default['schema'] = $schema;
        $this->default['sslmode'] = $sslmode;
        $this->default['connect_timeout'] = $connectTimeout;
    }
}
