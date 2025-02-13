<?php

// gestion de la base de donnée

namespace App\Utils;

class Database
{
    protected $bdd;
    protected $db_host;
    protected $db_name;
    protected $user_name;
    protected $password;

    /**
     * Ouverture de la base de données
     * @param string $db_name (nom de la base de données)
     * @param string $db_host (hôte de la base de données, par défaut "localhost")
     * @param string $user_name (nom d'utilisateur, par défaut "mdaszczynski")
     * @param string $password (Mot de passe)
     */
    public function __construct($db_name, $db_host, $user_name, $password)
    {
        $this->db_host = $db_host;
        $this->db_name = $db_name;
        $this->user_name = $user_name;
        $this->password = $password;
    }

    private function getPDO()
    {
        if ($this->bdd === null) {
            try {
                $bdd = new \PDO("mysql:host={$this->db_host};dbname={$this->db_name};charset=utf8mb4", $this->user_name, $this->password);
                $bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->bdd = $bdd;
            } catch (\PDOException $exeption) {
                die("Erreur de connexion à la base de données :". $exeption->getMessage());
            }
        }
        return $this->bdd;
    }
    /**
     * role prepare la requete sql 
     * @param string $sql (requete a traiter)
     * @param array ($param array(tableau des parametres de la requete))
     * @return object $req
     */
    public function sqlExecute($sql, $params = [])
    {
        $req = $this->getPDO()->prepare($sql);
        if (!$req->execute($params)) {
            die("Erreur d'exécution de la requête : " . $sql);
            return false;
        }
        return $req;
    }
    /**
     * role utilsel la method fetchAll du PDO
     * @param string $sql (requete a traiter)
     * @param array ($param array(tableau des parametres de la requete))
     * @return object $req
     */
    public function fetchAll($sql, $params = [])
    {
        $req = $this->sqlExecute($sql, $params);
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }
    /**
     * role reccupere la ligne suivante un jeu de resultat
     * @param string $sql (requete a traiter)
     * @param array ($param array(tableau des parametres de la requete))
     * @return object $req
     */
    public function fetch($sql, $params = [])
    {
        $req = $this->sqlExecute($sql, $params);
        return $req->fetch(\PDO::FETCH_ASSOC);
    }
    /**
     * role creer la requete d'insertion de data 
     * @param string ($table) la table concerner par l'insertion
     * @param mixed ($data) donnée a inseré
     * @return string ($req => la requete preparer et executer ) 
     */
    public function insertBDD($table, $data = [])
    {
        global $bdd;
        if (!$bdd) {
            echo "Erreur : Connexion à la base de données non établie.";
            return false;
        } 
        // on creer le tableau de parametre
        $model = [];
        $param = [];
        foreach ($data as $key => $value) {
            $model[] = "`$key` = :$key";
            $param[":$key"] = $value;
        }
        $sql = "INSERT INTO `$table` SET " . implode(', ', $model);
        $req = $this->sqlExecute($sql, $param);
        return $req;
    }
    /**
     * role retoune le dernier id inserer
     * @return int dernier id inserer
     */
    public function lastInsertId() {
        return $this->getPDO()->lastInsertId();
    }
    /**
     * role creer la requete pour modifier les donnes
     * @param string $table(table a concerner)
     * @param mixed $data( donnée a modifier)
     * @param mixed $id(de l'objet a modifier)
     * @return string ($req => la requete preparer et executer ) 
     */
    public function updateBDD($table, $data = [], $id = 0)
    {
        $model = [];
        // on creer le tableau de parametre
        $param = [":id" => $id];
        foreach ($data as $key => $value) {
            $model[] = "`$key` = :$key";
            $param[":$key"] = $value;
        }
        $sql = "UPDATE `$table` SET " . implode(", ", $model) . " WHERE `id` = :id";
        $req = $this->sqlExecute($sql, $param);
        return $req;
    }
}
