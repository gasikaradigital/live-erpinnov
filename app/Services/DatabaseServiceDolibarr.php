<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class DatabaseServiceDolibarr
{
    private $config;

    public function __construct()
    {
        $this->config = Config::get('dolibarr.cpanel');
    }

    public function createDatabase($dbNameCpanel)
    {
        $cpanel_host = $this->config['host'];
        $cpanel_user = $this->config['user'];
        $auth_token = $this->config['token'];
        $dbNameSuffix = $this->config['dbname_suffix'];
        $db_name = $dbNameSuffix . $dbNameCpanel . "-dolibarr";

        $api_url = "https://$cpanel_host:2083/execute/Mysql/create_database?name=$db_name";

        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: cpanel $cpanel_user:$auth_token"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $mysql_user_access = $this->config['mysql_user_access'];
        $privileges = $this->config['privileges'];

        $url = "https://$cpanel_host:2083/execute/Mysql/set_privileges_on_database?user=$mysql_user_access&database=$db_name&privileges=$privileges";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: cpanel $cpanel_user:$auth_token"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $db_name;
    }
    
    /*public function createDatabaseInnov($dbNameCpanel)
    {
        $cpanel_host = $this->config['host'];
        $cpanel_user = $this->config['user'];
        $auth_token = $this->config['token'];
        $dbNameSuffix = $this->config['dbname_suffix'];
        $db_name = $dbNameSuffix . $dbNameCpanel;

        $api_url = "https://$cpanel_host:2083/execute/Mysql/create_database?name=$db_name";

        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: cpanel $cpanel_user:$auth_token"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $mysql_user_access = $this->config['mysql_user_access'];
        $privileges = $this->config['privileges'];

        $url = "https://$cpanel_host:2083/execute/Mysql/set_privileges_on_database?user=$mysql_user_access&database=$db_name&privileges=$privileges";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: cpanel $cpanel_user:$auth_token"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $db_name;
    }*/

    public function importDatabase($db_name)
    {
        try {
            Config::set("database.connections.dynamic", [
                'driver' => 'mariadb',
                'host' => env('DB_HOST', 'localhost'),
                'port' => env('DB_PORT', '3306'),
                'database' => $db_name,
                'username' => $this->config['mysql_user'],
                'password' => $this->config['mysql_password'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => false,
                'engine' => null,
            ]);
        
            DB::purge('dynamic');
            $connection = DB::connection('dynamic');
            
            $fichierSql = base_path('database/sc2sylg_doli965.sql');
        
            if (!File::exists($fichierSql)) {
                throw new \Exception("Le fichier SQL n'existe pas.");
            }
        
            $sql = file_get_contents($fichierSql);
        
            $connection->unprepared($sql);
        
            return true;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de l'importation : " . $e->getMessage());
            return false;
        }
    }
    

    public function updateCredentials($login, $db_name, $password, $api_key)
    {
        try {
            config(['database.connections.dynamic' => [
                'driver' => 'mariadb',
                'host' => 'localhost',
                'database' => $db_name,
                'username' => $this->config['mysql_user'],
                'password' => $this->config['mysql_password'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => false,
                'engine' => null,
            ]]);
    
            DB::purge('dynamic');
            DB::reconnect('dynamic');
    
            // Crypter le mot de passe selon la méthode utilisée par Dolibarr
            $passwordCrypted = $this->cryptDolibarrPassword($password);
            
            
            DB::connection('dynamic')->table('llx2n_user')
                ->where('rowid', 1)
                ->update([
                    'login' => 'admin',
                    'pass_crypted' => $passwordCrypted,
                    'api_key' => $api_key
                ]);
    
            return true;
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la mise à jour : " . $e->getMessage());
            return false;
        }
    }
    
    private function cryptDolibarrPassword($password)
    {
        // Implémentez ici la méthode de cryptage utilisée par Dolibarr
        // Par exemple, si Dolibarr utilise password_hash :
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
