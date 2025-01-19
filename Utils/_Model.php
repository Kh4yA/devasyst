<?php

namespace App\Utils;

// class _Model qui va contenir toutes les methods generiques

class _Model
{
    protected $table;
    protected $fields = [];
    protected $values = []; // tableau indexé cle valeur au format ["nom"=>"valeur","prenom"=>"valeur",....]
    protected $id = 0; // On defini l'id a 0 par defaut

    //definir le __constructor pour que quand l'id est passé en parametre il charge l'objet null par defaut
    public function __construct($id = null)
    {
        if ($id) {
            $this->load($id);
        }
    }

    // GETTER MAGIQUE
    public function __get($fieldName)
    {
        if ($fieldName == "id") return $this->getId();
        elseif (in_array($fieldName, $this->fields)) return $this->getHtml($fieldName);
    }
    //GETTER
    /**
     * role : reccuperer la valeur d'un atribut
     * @param string 
     * @return string (valeur du champ)
     */
    function get($fieldName)
    {
        if (isset($this->values[$fieldName])) {
            return $this->values[$fieldName];
        } else {
            return null;
        }
    }
    //Methode GET avec le filtre htmlEntities ainsi que NL2BR
    /**
     * @param  string $fieldName
     * @return  string La chaine nettoyer
     */
    function getHtml($fieldName): string
    {
        return $this->get($fieldName) === null ? $fieldName = "" : nl2br(htmlentities($this->get($fieldName)));
    }

    /**
     * recupere la valeur de l'id
     *  @return int (valeur de l'id)
     */
    function getId(): int
    {
        return $this->id;
    }
    //SETTER
    /**
     * role : valoriser un attribut
     * @param string|int $fieldName(valeur a valoriser)
     * @return true (true si accepter false sinon)
     */
    function set($fieldName, $value)
    {
        $this->values[$fieldName] = $value;
        return true;
    }
    /**
     * construit une liste de champs pour la requete
     * @return string retourne une chaine de caractere au format "`nom`, `prenom`, ..."
     */
    function listField(): string
    {
        $tab = array();
        foreach ($this->fields as $field) {
            $tab[] = "`$field`";
        }
        return implode(', ', $tab);
    }
    /**
     * loadFromTab charge un tableau de données a partir d'un autre tableau
     * @param array ($tab)
     */
    function loadFromTab($tab): bool
    {
        foreach ($this->fields as $fieldName) {
            if (isset($tab[$fieldName])) {
                $this->values[$fieldName] = $tab[$fieldName];
            }
        }
        return true;
    }
    /**
     * Role : Verifier si un champs existe si oui on verifie si une valeur a ssocié a ce champs existe
     * @param string $fieldName (Champs dans lesquel on recherche)
     * @param string $value (valeur a chercher dans le champs)
     *  @return bool (true si existe sinon false)
     */
    function exist($fieldName, $value): bool
    {
        if (!in_array($fieldName, $this->fields)) {
            echo 'Ce champ n\'existe pas';
            return false;
        }
        $sql = "SELECT * FROM `$this->table` WHERE `$fieldName` = :value";
        $param = [":value"=>$value];
        global $bdd;
        $req = $bdd->fetch($sql, $param);
        if ($req) {
            return true;
            }
            return false;
    }
    /**
     * charge un objet de la classe courante
     * @param $id de l'objet a charger
     * @return  object|bool $this ou false
     */
    function load($id): object|bool
    {
        global $bdd;
        $sql = " SELECT `id`," . $this->listField() . " FROM `$this->table` WHERE `id` = :id";
        $param = [":id" => $id];
        $obj = $bdd->fetch($sql, $param);
        if ($obj) {
            $this->id = $obj["id"];
            foreach ($this->fields as $data) {
                $this->values[$data] = $obj[$data];
            }
            return $this;
        }
        return false;
    }
    /**
     * role : inserer des donnée en bdd
     * @param neant
     * @return int id de la derniere pizza inserer
     */
    function insert()
    {
        global $bdd;
        $bdd->insertBDD($this->table, $this->values);
        return $bdd->lastInsertId();
    }
    /**
     * role : modifer des donnée en bdd
     * @param neant
     * @return bool true si ok
     */
    function update(): bool
    {
        global $bdd;
        $bdd->updateBDD($this->table, $this->values, $this->id);
        return true;
    }
    /**
     * Role : lister tous les éléments d'une table avec une limite de résultats en paramètre
     * @param string (role à null par defaut)
     * @param int|null $limit
     * @param int|null $offset
     * @param string|null $value (La valeur rechercher)
     * @return array|false
     */
    function listAll(int $limit = null, int $offset = null, string $value = ''): array
    {
        global $bdd;
        $param = [];
        // Construire la requête SQL de base
        $sql = "SELECT `id`, " . $this->listField() . " FROM `$this->table`";
        // Ajouter les conditions de recherche si nécessaire            
        if ($value) {
            $sql .= " WHERE `nom` LIKE :value";
            $param[":value"] = '%' . $value . '%';
        }
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }
        if ($offset !== null) {
            $sql .= " OFFSET " . (int)$offset;
        }
        global $bdd;
        $req = $bdd->sqlExecute($sql, $param);
        $result = [];
        // Traitement des résultats
        while ($data = $req->fetch(\PDO::FETCH_ASSOC)) {
            $class = get_class($this);
            $obj = new $class();
            $obj->loadFromTab($data);
            $obj->id = $data["id"];
            $result[$obj->id] = $obj;
        }
        return $result;
    }
    /**
     * Rôle : extraire une liste d'objet avec des critères de tri et de filtrage en option
     * @param array $filtres permet de filtrer par nom de champ ['nomChamp' => valeur]
     * @param array $tris liste de tri ['+/-nomChamp']
     * @param string $tableJoin table de la jointure à utiliser
     * @param string $joinKey champ dans la table principale qui est utilisé pour faire la jointure avec l’ID de la table associée
     * @param string $fieldSelected champ spécifique à sélectionner dans la table de jointure
     * @param string $allias allias a donner au champs specifique pour le cibler
     * @param string $limit limit a donner pour la requete
     * @param string $offset decalage a donner dans le cas de la pagination
     * @return array tableau d'objet de la classe courante indexé par l'id
     */
    function listEtendue(array $filtres = [], array $tris = [], string $tableJoin = null, string $joinKey = null, string $fieldSelected = null, string $allias = null, int $limit = null, int $offset = null): array
    {
        // Construction de la requête de base
        $sql = "SELECT `$this->table`.`id`";
    
        // Ajout conditionnel du champ de la table de jointure
        if (!empty($tableJoin) && !empty($fieldSelected)) {
            $sql .= ", `$tableJoin`.`$fieldSelected` AS $allias";
        }
        $sql .= ", " . $this->listfield() . " FROM `$this->table`";
        $param = [];
        $tabFiltre = [];
        // Gestion des filtres
        foreach ($filtres as $fieldName => $valeur) {
            // Valider les noms de colonnes pour éviter les injections SQL
            if (preg_match('/^[a-zA-Z0-9_]+$/', $fieldName)) {
                $tabFiltre[] = "`$fieldName` = :$fieldName";
                $param[":$fieldName"] = $valeur;
            }
        }
        // Ajout de la jointure si elle existe
        if (!empty($tableJoin) && !empty($joinKey)) {
            $sql .= " JOIN `$tableJoin` ON `$this->table`.`$joinKey` = `$tableJoin`.`id`";
        }
        // Ajout des conditions de filtre
        if (!empty($tabFiltre)) {
            $sql .= " WHERE " . implode(" AND ", $tabFiltre);
        }
        // Construction de la liste des critères de tri
        $tabOrder = [];
        foreach ($tris as $tri) {
            $car1 = substr($tri, 0, 1);
            $ordre = ($car1 === '-') ? "DESC" : "ASC";
            $nomField = ($car1 === '+' || $car1 === '-') ? substr($tri, 1) : $tri;
            $tabOrder[] = "`$nomField` $ordre";
        }
        if (!empty($tabOrder)) {
            $sql .= " ORDER BY " . implode(", ", $tabOrder);
        }
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }
        if ($offset !== null) {
            $sql .= " OFFSET " . (int)$offset;
        }
        // Affichage de la requête pour débogage
        // print_r($sql); // Décommenter pour déboguer
        // die();
        global $bdd;
        $req = $bdd->sqlExecute($sql, $param);
        $result = [];
        // Traitement des résultats
        while ($data = $req->fetch(\PDO::FETCH_ASSOC)) {
            $class = get_class($this);
            $obj = new $class();
            $obj->loadFromTab($data);
            if ($allias !== null) {
                $obj->set($allias, $data[$allias]);
            }
            $obj->id = $data["id"];
            $result[$obj->id] = $obj;
        }
        
        return $result;
    }    /**
     * role : supprime un champ selectionner par l'id
     * @param neant
     * @return true
     */
    function delete(): bool
    {
        $sql = "DELETE FROM `$this->table` WHERE `id` = :id";
        $param = [":id" => $this->id];
        global $bdd;
        $bdd->sqlExecute($sql, $param);
        return true;
    }
    /**
     * Verifie que le mot de passe corespond bien
     * @param string $login (l'identifiant du compte a verfié)
     * @param string $password ( Le mot de passe a verifié )
     * @param bool (true si existe false si non)
     */
    function verif_connexion($login, $password): bool
    {
        $sql = "SELECT `id`, " . $this->listField() . " FROM `$this->table` WHERE `identifiant` = :identifiant";
        $param = [':identifiant' => $login];
        global $bdd;
        $data = $bdd->fetch($sql, $param);
        if (empty($data)) {
            return false;
        }
        if (password_verify($password, $data["password"])) {
            $this->id = $data["id"];
            $this->loadFromTab($data);
            return true;
        } else {
            return false;
        }
    }
    public function findByEmail($email){
        $sql = "SELECT `id`, " . $this->listField() . " FROM `$this->table` WHERE `email` = :email";
        $param = [':email' => $email];
        global $bdd;
        $data = $bdd->fetch($sql, $param);
        if (empty($data)) {
            return false;
        }
        $this->id = $data["id"];
        $this->loadFromTab($data);
        return true;
    }
    

}
