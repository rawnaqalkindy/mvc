<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm;

final class DataLayerConfiguration
{
    private ?array $dataLayerConfiguration;

    public function __construct(?string $dotEnvString = null, ?array $dataLayerConfiguration = null)
    {
        $this->dataLayerConfiguration = $dataLayerConfiguration;
        if ($dotEnvString !== null) {
            (new $dotEnvString())->load(ROOT_PATH . '/.env');
        }
    }

    /**
     * Returns an array of dataLayer database configurations. Various drivers are
     * supported. Please see supported drivers list. Values are feed from the document
     * root .env file if that file exists. and will load those environment values
     * else revert to the default settings which is set up for development environment
     */
    public function baseConfiguration(): array
    {
        if (is_array($this->dataLayerConfiguration) && ($this->dataLayerConfiguration !== null) && count($this->dataLayerConfiguration) > 0) {
            return $this->dataLayerConfiguration;
        }

        return [

            'driver' => [

                'mysql' => [
                    'dsn' => "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}",
                    'username' => $this->dbUsername(),
                    'password' => $this->dbPassword()
                ],
                'pgsql' => [
                    'dsn' => "pgsql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}",
                    'username' => $this->dbUsername(),
                    'password' => $this->dbPassword()
                ],
                'sqlite' => [
                    'dsn' => "pgsql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}",
                    'username' => $this->dbUsername(),
                    'password' => $this->dbPassword()
                ]
            ]

        ];
    }

    /**
     * Return the database environment username
     */
    public function dbUsername(): string
    {
        return $_ENV['DB_USER'] ?: 'root';
    }

    /**
     * return the database environment password
     */
    public function dbPassword(): string
    {
        return $_ENV['DB_PASSWORD'] ?: '';
    }
}
