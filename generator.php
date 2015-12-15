<?php


function connect ($host, $databasename, $user, $password) {

    $connexion = new PDO('mysql:host='.$host.';dbname='.$databasename, $user, $password);
    $req = $connexion->prepare('SHOW columns FROM users');
    $req->execute();
//    var_dump($req->fetchAll(),'test');

    return $req->fetchAll();
}

$infos = connect($argv[1], $argv[2], $argv[3], $argv[4], $argv[5]);
$fields = [];


foreach ($infos as $info) {
    $fields[] = $info["Field"];
}
var_dump($fields);

function do_tabs($tabs)
{
    $ret = '';
    for ($i = 0 ; $i < $tabs ; $i++)
        $ret .= ' ';
    return$ret;
}

$className = $argv[5];

$tabs = 2;
$code = "<?php\n\nnamespace table;\n\n";
$code .=  "class $className \n{\n";

$code .= "\n";
foreach ($fields as $field)
{
    $code .= do_tabs($tabs) . 'protected $'.$field.";\n";
}

$code .= "\n";

foreach ($fields as $field)
{
    $code .= do_tabs($tabs) . 'public function get'.ucfirst($field)."()\n";
    $code .= do_tabs($tabs) . "{\n";
    $code .= do_tabs($tabs+2) . 'return $this->'.$field.";\n";
    $code .= do_tabs($tabs) . "}\n\n";
    $code .= do_tabs($tabs) . 'public function set'.ucfirst($field).'($'.$field.")\n";
    $code .= do_tabs($tabs) . "{\n";
    $code .= do_tabs($tabs+2) . '$this->'.$field.' = $'.$field.";\n";
    $code .= do_tabs($tabs) . "}\n\n";
}
$code .= "}\n";

file_put_contents($className.".php", $code);