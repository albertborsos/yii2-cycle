<?php

namespace albertborsos\cycle;

use Cycle\ORM\Factory;
use Cycle\ORM\ORM;
use Spiral\Database\Config\DatabaseConfig;
use Spiral\Database\DatabaseManager;

class Connection extends \yii\base\Component
{
    /**
     * @var array mapping between PDO driver names and [[Driver]] classes.
     */
    public $driverMap = [
        'pgsql'   => \Spiral\Database\Driver\Postgres\PostgresDriver::class,
        'mysqli'  => \Spiral\Database\Driver\MySQL\MySQLDriver::class,
        'mysql'   => \Spiral\Database\Driver\MySQL\MySQLDriver::class,
        'sqlite'  => \Spiral\Database\Driver\SQLite\SQLiteDriver::class,
        'sqlite2' => \Spiral\Database\Driver\SQLite\SQLiteDriver::class,
        'sqlsrv'  => \Spiral\Database\Driver\SQLServer\SQLServerDriver::class,
    ];
    /**
     * Schema mapping for entities
     *
     * ```php
     *     'schema' => [
     *         'project' => ProjectRepository::schema(),
     *         'project_image' => ProjectImageRepository::schema(),
     *         'project_slug' => ProjectSlugRepository::schema(),
     *     ],
     * ```
     *
     * @var array
     */
    public $schema;
    /**
     * Default connection driver, if it is null, then it tries to read from DSN string.
     * @var string
     */
    public $driver;
    /**
     * @var string the Data Source Name, or DSN, contains the information required to connect to the database.
     * Please refer to the [PHP manual](https://secure.php.net/manual/en/pdo.construct.php) on
     * the format of the DSN string.
     *
     * For [SQLite](https://secure.php.net/manual/en/ref.pdo-sqlite.connection.php) you may use a [path alias](guide:concept-aliases)
     * for specifying the database path, e.g. `sqlite:@app/data/db.sql`.
     *
     * @see charset
     */
    public $dsn;
    /**
     * @var string the username for establishing DB connection. Defaults to `null` meaning no username to use.
     */
    public $username;
    /**
     * @var string the password for establishing DB connection. Defaults to `null` meaning no password to use.
     */
    public $password;

    /**
     * If it is not empty, then you must provide a full config array.
     * @var array
     */
    public $config = [];

    /**
     * @var \Cycle\ORM\ORMInterface
     */
    private $orm;

    public function orm()
    {
        return $this->orm;
    }

    public function init()
    {
        $this->initializeConfig();
        $this->initializeOrm();
    }

    protected function initializeConfig(): void
    {
        if (!empty($this->config)) {
            return;
        }

        $this->config = [
            'default' => 'default',
            'databases' => [
                'default' => ['connection' => $this->getDriver()]
            ],
            'connections' => [
                $this->getDriver() => [
                    'driver' => $this->driverMap[$this->getDriver()],
                    'connection' => $this->dsn,
                    'username' => $this->username,
                    'password' => $this->password,
                ]
            ]
        ];
    }

    protected function initializeOrm(): void
    {
        $dbal = new DatabaseManager(new DatabaseConfig($this->config));
        $this->orm = (new ORM(new Factory($dbal)));
        $this->orm = $this->orm->withSchema(new \Cycle\ORM\Schema($this->schema));
    }

    public function cleanHeap()
    {
        return $this->orm->getHeap()->clean();
    }

    private function getDriver()
    {
        if (empty($this->driver)) {
            if (($pos = strpos($this->dsn, ':')) !== false) {
                $this->driver = strtolower(substr($this->dsn, 0, $pos));
            }
        }

        return $this->driver;
    }
}
