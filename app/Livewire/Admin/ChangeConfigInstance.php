<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ChangeConfigInstance extends Component
{
    public $dbName, $dbUser, $dbPassword, $dbPrefix;
    /**
     * Initializes a new instance of the class.
     *
     * @param string $dbName The name of the database.
     * @param string $dbUser  The username for the database connection.
     * @param string $dbPassword The password for the database connection.
     * @param string $dbPrefix The prefix for the database.
     *
     * @throws InvalidArgumentException If any of the required parameters are empty.
     */
    public function __construct(string $dbName, string $dbUser , string $dbPassword, string $dbPrefix)
    {
        if (empty($dbName) || empty($dbUser ) || empty($dbPassword)) {
            throw new InvalidArgumentException('Database name, user, and password are required.');
        }

        $this->dbName = $dbName;
        $this->dbUser  = $dbUser ;
        $this->dbPassword = $dbPassword;
        $this->dbPrefix = $dbPrefix;

        // Initialize properties or perform setup tasks here
    }

    public function ChangePassword($passwordHash){
        config(['database.connections.dynamic' => [
            'driver' => 'mariadb',
            'host' => 'localhost',
            'database' => $this->dbName,
            'username' => $this->dbUser,
            'password' => $this->dbPassword,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ]]);

        DB::purge('dynamic');
        DB::reconnect('dynamic');
       
        DB::connection('dynamic')->table($this->dbPrefix.'user')
            ->where('rowid', 1)
            ->update([
                'login' => 'admin',
                'api_key' => $api_key_dolibarr,
                'password' => $passwordHash
            ]);
    }

    private function cryptDolibarrPassword($password)
    {
        // Implémentez ici la méthode de cryptage utilisée par Dolibarr
        // Par exemple, si Dolibarr utilise password_hash :
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function render()
    {
        return view('livewire.admin.change-config-instance');
    }
}
