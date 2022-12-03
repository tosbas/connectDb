<?php

declare(strict_types=1);

/**
 * Class pour init une connexion Mysql
 */
class ConnectDb
{

    private string $host;
    private string $db_name;
    private string $username;
    private string $password;

    /**
     * Function pour crée une connexion Mysql
     */
    public function connect()
    {
        $this->host = "DB_HOST";
        $this->db_name = "DB_NAME";
        $this->username = "DB_USERNAME";
        $this->password = "DB_PASSWORD";

        $pdo = new PDO("mysql:host={$this->host};dbname={$this->db_name}", "{$this->username}", "{$this->password}");

        return $pdo;
    }
}

/**
 * Class pour intéragir avec la db
 */
class Request extends ConnectDb
{

   /**
    * Function pour simplifier les requetes
    */
    public function query(string $query ,string $param ,array $datas = []) : array
    {


        if(!in_array($param, ["ALL", "UNIQUE"])){
            trigger_error(sprintf('%s n\'est pas valide. Les status possibles sont : ALL et UNIQUE', $param, E_USER_ERROR));
        }

        $result = $this->connect()->prepare($query);

        if(count($datas) !== 0){

            $count = 1;
            foreach($datas as $key => $value){
                
                if(is_numeric($value)){
                    
                    $dataSanitize = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                    $result->bindParam($count, $dataSanitize, PDO::PARAM_INT);

                }else{
                    
                    $dataSanitize = filter_var($value, FILTER_SANITIZE_STRING);
                    $result->bindParam($count, $dataSanitize, PDO::PARAM_STR);
                }

                $count++;
                
            }
        }

        $result->execute();

        switch($param){
            case "ALL":
                $fetchData = $result->fetchAll();
            break;

            case "UNIQUE":
                $fetchData = $result->fetch();  
            break;

            default:
                $fetchData = NULL;  
            break;
        }

       

        return $fetchData;
    }
}
