<?php
/**
 * Hichem : Ici se retrouve le model principal qui va être utile dans les autres models pour chaque page
 */

class Model
{
    static $connections = array();
    public $conf = 'default';
    public $db;
    public $primaryKey = 'id';
	public $id;
    public $errors = array();

    /**
     * Hichem : Constructeur de la classe Model permettant la connection à la base de données en fonction de la configuration (conf.php)
     */
    public function __construct()
    {
        $conf = Conf::$databases[$this->conf];
        if(isset(Model::$connections[$this->conf]))
        {
            $this->db = Model::$connections[$this->conf];
            return true;
        }
        try{
            $pdo = new PDO(
                'mysql:host='.$conf['host'].';dbname='.$conf['database'].';charset=utf8',
                $conf['login'],
                $conf['password'],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            Model::$connections[$this->conf] = $pdo;
            $this->db = $pdo;
        }catch(PDOException $e){
            if(Conf::$debug >= 1){
                die($e->getMessage());
            }else{
                die('Impossible de se connecter à la base de données');
            }
        }
    }

    /**
     * Hichem : Fonction permettant de récupérer les données d'une table (Find, FindAll, FindFirst, FindCount)
     */
}