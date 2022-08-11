<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare (strict_types = 1);

namespace Abc\Utility;

use Exception;
use Symfony\Component\Dotenv\Dotenv;

class Token
{
    protected string $token;

    /**
     * Class constructor. Create a new random token or assign an existing one if passed in.
     */
    public function __construct(string $tokenValue = null, int $bytes = 16)
    {
        if ($tokenValue) {
            $this->token = $tokenValue;
        } else {
            $this->token = bin2hex(random_bytes($bytes));
        }
    }

    /**
     * Get the token value
     */
    public function getTokenValue() : string
    {
        return $this->token;
    }

    /**
     * Get the hashed token value The hashed value
     */
    public function getHashedTokenValue() : string
    {
        (new Dotenv())->load(ROOT_PATH . '/.env');

        // sha256 = 64 chars
//        return hash_hmac('sha256', $this->token, Yaml::file('app')['settings']['secret_key']);
        return hash_hmac('sha256', $this->token, HASHING_SECRET_KEY);
    }

}