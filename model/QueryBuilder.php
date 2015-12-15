<?php
/**
 * Created by PhpStorm.
 * User: baptiste
 * Date: 09/12/2015
 * Time: 12:08
 */

namespace model;


class QueryBuilder
{
    private $co;

    /**
     * @param $host
     * @param $db
     * @param $user
     * @param $password
     */
    public function __construct($host, $db, $user, $password)
    {
        $this->co = new \PDO ('mysql:host='.$host.';dbname='.$db, $user, $password);
        $reqCo = $this->co->prepare("SET NAMES utf8;");
        $reqCo->execute();

        echo "Vous êtes connecté";
    }

    /**
     * @param $table
     * @param array $data
     */
    public function persist($table, array $data)
    {
        $fields = implode(',', array_keys($data));
        $values = implode(',', array_map(array($this, "quoteValue"), array_values($data)));
        $query = 'INSERT INTO ' . $table . ' (' . $fields . ') ' . ' VALUES (' . $values . ')';
        $exe = $this->execute($query);
    }

    /**
     * @param $tablename
     * @param string $colonne
     */
    public function selectAllOrByColone($tablename, $colonne='*')
    {
        if(is_array($colonne))
        {
            $colonne = implode(',', $colonne);
            $query = 'SELECT ' . $colonne .' FROM ' .$tablename;
            $PDOStatementObject = $this->co->query($query);
            $results = $PDOStatementObject->fetchAll(\PDO::FETCH_CLASS);
            var_dump($results);
        }
        else
        {
            $query = 'SELECT ' .$colonne. ' FROM ' .$tablename;
            $PDOStatementObject = $this->co->query($query);
            $results = $PDOStatementObject->fetchAll(\PDO::FETCH_CLASS);
            var_dump($results);
        }

    }

    /**
     * @param $tablename
     * @param string $where
     * @param string $fields
     * @param string $order
     * @param null $limit
     * @param null $offset
     */
    public function select($tablename, $where = '', $fields = '*', $order = '', $limit = null, $offset = null)
    {
        $query = 'SELECT ' . $fields . ' FROM ' . $tablename
               . (($where) ? ' WHERE ' . $where : '')
               . (($order) ? ' ORDER BY ' . $order : '')
                . (($limit) ? ' LIMIT ' . $limit : '')
                . (($offset && $limit) ? ' OFFSET ' . $offset : '');
        $PDOStatementObject = $this->co->query($query);
        $results = $PDOStatementObject->fetchAll(\PDO::FETCH_CLASS);
        var_dump($results);
    }

    public function selectOneBy($tablename, $where = '', $fields = '*', $order = '', $limit = null, $offset = null)
    {
        $query = 'SELECT ' . $fields . ' FROM ' . $tablename
            . (($where) ? ' WHERE ' . $where : '')
            . (($order) ? ' ORDER BY ' . $order : '')
            . (($limit) ? ' LIMIT ' . $limit : '')
            . (($offset && $limit) ? ' OFFSET ' . $offset : '');
        $PDOStatementObject = $this->co->query($query);
        $results = $PDOStatementObject->fetch(\PDO::FETCH_ASSOC);
        var_dump($results);
    }

    /**
     * @param $tablename
     * @param $where
     * @param $arg
     */
    public function delete($tablename, $where, $arg)
    {
        $query = 'DELETE FROM ' . $tablename .  ' WHERE ' . $where. '=' .$arg;
        $exe = $this->execute($query);
    }

    /**
     * @param $value
     * @return string
     */
    function quoteValue($value)
    {
        if ($value === null) {
            $value = 'NULL';
        }
        else
        {
            $value = "'" . $value . "'";
        }
        return $value;
    }


    /**
     * @param $tablename
     * @param array $data
     * @param array $where
     */
    public function update($tablename, array $data, array $where)
    {
        $set = array();
        $whereand = array();
        foreach ($data as $field => $value) {
            $set[] = $field . '=' . $this->quoteValue($value);
        }
        foreach ($where as $field => $value) {
            $whereand[] = $field . '=' . $this->quoteValue($value);
        }
        $whereand = implode(' AND ', $whereand);
        $set = implode(',', $set);
        $query = 'UPDATE ' . $tablename . ' SET ' . $set
        . ' WHERE ' . $whereand ;

        $exe = $this->execute($query);
    }


    /**
     * @param $query
     */
    private function execute($query)
    {
        $exe = $this->co->prepare($query);
        $result = $exe->execute();

        if($result === false) {
            echo"faux";
            $error = $exe->errorInfo();
            if (!file_exists("error.log")) {
                file_put_contents("error.log", "");
            }
            file_put_contents("error.log",date("[j/m/y H:i:s]")." - ". $error{2} . "\r\n".file_get_contents("error.log"));
        }
        else {
            echo "true";
            if (!file_exists("access.log")) {
                file_put_contents("access.log", "");
            }
            file_put_contents("access.log",date("[j/m/y H:i:s]")." - ". $query . "\r\n".file_get_contents("access.log"));
        }
    }

    /**
     * @param $tablename
     */
    public function count($tablename)
    {
        $query = 'SELECT COUNT(*) FROM '.$tablename;
        $PDOStatementObject = $this->co->query($query);
        $results = $PDOStatementObject->fetchAll(\PDO::FETCH_CLASS);
    }

    /**
     * @param $field
     * @param $tablename
     * @param $value
     */
    public function exist($field, $tablename, $value)
    {
        $query = 'SELECT ' . $field . ' FROM '.$tablename. ' WHERE ' . $field . ' = \'' . $value . '\'';
        $PDOStatementObject = $this->co->query($query);
        $results = $PDOStatementObject->fetchAll(\PDO::FETCH_CLASS);
        var_dump($results);
    }

    public function join()
    {

    }
}