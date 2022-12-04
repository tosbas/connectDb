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
        $this->host = "localhost";
        $this->db_name = "testdb";
        $this->username = "root";
        $this->password = "";

        $pdo = new PDO("mysql:host={$this->host};dbname={$this->db_name}", "{$this->username}", "{$this->password}");

        return $pdo;
    }
}

/**
 * Class pour intéragir avec la db
 */
class Request extends ConnectDb
{


    private int $resultCount = 0;

   /**
    * Function pour simplifier les requetes
    */
    public function query(string $query ,string $param = NULL ,array $datas = [])
    {


        if(!in_array($param, [NULL,"ALL", "UNIQUE"])){
            trigger_error(sprintf('%s n\'est pas valide. Les status possibles sont : ALL et UNIQUE', $param, E_USER_ERROR));
        }

        $result = $this->connect()->prepare($query);

        if(count($datas) !== 0){

            $count = 1;
            foreach($datas as &$value){
                
                if(is_numeric($value)){
                
                    $result->bindParam($count, $value, PDO::PARAM_INT);

                }else{

                    $result->bindParam($count, $value, PDO::PARAM_STR);
                       
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

        $this->resultCount = $result->rowCount();

        return $fetchData;
    }
    
    public function rowCount(){
        return $this->resultCount;
    }

}
