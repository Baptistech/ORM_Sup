<?php
/**
 * Created by PhpStorm.
 * User: baptiste
 * Date: 07/12/2015
 * Time: 18:13
 */

require_once('autoload.php');

use model\Orm;
use model\QueryBuilder as Query;
use table\User;


// connection
try{
    $orm = new Orm('localhost', 'test', 'root', '');
}
catch (Exception $e){
    print "Erreur: \n";
}

try{
    $query = new Query('localhost', 'test', 'root', '');
}
catch (Exception $e){
    print 'Erreur: '.   $e->getMessage().  "\n";
}

// creating new user in database
$user = new User();
$user->setId('');
$user->setLogin('henri');
$user->setEmail('henri@supinternet.fr');
$user->setPassword(sha1('toto42'));
 // mettre le sha1 dans le setter et getter !


// The general persist
//$query->persist('users', ['id'=>'4', 'login'=>'paule',
//    'password'=>'sdrjog', 'email'=>'polo@gmail.fr']);


// To update a user
//$query->update('users', ['login'=>'kirikou'], ['login'=>'drg', 'id'=>'31']);


// The general select
//$query->select('users', 'email="polo@gmail.fr"', '*', 'id DESC', 3);


// The general select who return one user
//$query->selectOneBy('users', 'email="polo@gmail.fr"', '*', 'id DESC', 3);


// The count request
//$query->count('users');


// The exist request
//$query->exist('id','users', '3');


// The select request
//$query->selectAllOrByColone('users');


// The delete request
//$query->delete('users', 'id', '3');


//$orm->getAll('table\User');

//$all = $orm->getAll('table\User');

//foreach ($all as $u)
//{
//    $u->SetPassword(shal('xxx'));
//    $orm->persit($u);
//}


