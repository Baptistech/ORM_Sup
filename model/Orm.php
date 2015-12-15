<?php

/**
 * Created by PhpStorm.
 * User: baptiste
 * Date: 09/12/2015
 * Time: 09:53
 */

namespace model;


class Orm
{
    private $connexion;

    public function __construct ($host, $db, $user, $password)
    {
        $this->connexion = new \PDO('mysql:host='.$host.';dbname='.$db, $user, $password);
        $this->connexion->query("SET NAMES utf8;");
    }

    public function addLog($sql) {
        if (!file_exists("access.log")) file_put_contents("access.log", "");
        file_put_contents("access.log",date("[j/m/y H:i:s]")." - $sql \r\n".file_get_contents("log.log"));
    }

    public function persist($object)
    {
        // - il faut trouver le type de $object (c'est-à-dire la class du conteneur $object)
        // ==> pour savoir où le sauvegarder
        // - je save ==> je dois construire la requête

        $objectClass = get_class($object);
        $tableName = $objectClass::getTableName();

        $query = "INSERT INTO `".$tableName."` (`id`, `login`, `password`, `email`) VALUES (NULL, '".$object->getLogin()."', '".$object->getPassword()."', '".$object->getEmail()."')";
        // construire la requête de manière générique
        $req = $this->connexion->prepare($query);
        var_dump($req);
        $req->execute();

    }

    public function getAll($table)
    {
        $tableName = $table::getTableName();
        $query = "SELECT * FROM `".$tableName."`";
        $PDOStatementObject = $this->connexion->query($query);
        $results = $PDOStatementObject->fetchAll();
        var_dump($results);
    }

    public function update($object)
    {
        $objectClass = get_class($object);
        $tableName = $objectClass::getTableName();

        $query = "UPDATE `".$tableName."` SET `login`='".$object->getLogin()."',`password`='".$object->getPassword()."',`email`='".$object->getEmail()."' WHERE id=".$object->getId()." ";
        $req = $this->connexion->exec($query);
    }

    public function deleteById($object, $id)
    {
        $tableName = $object::getTableName();

        $query = "DELETE FROM `".$tableName."` WHERE id = ".$id;
        $req = $this->connexion->prepare($query);
        $req->execute();
    }

    public function delete($object, $rowname, $value)
    {
        $tableName = $object::getTableName();

        $query = "DELETE FROM `".$tableName."` WHERE `"."$rowname"."` = "."'".$value."'";
        $req = $this->connexion->prepare($query);
        $req->execute();
    }

}