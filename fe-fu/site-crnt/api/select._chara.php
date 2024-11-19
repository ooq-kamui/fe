<?
header("Access-Control-Allow-Origin: *");

require("config.php");

$mysqli = new mysqli($host, $user, $pw, $db);
if( $mysqli->connect_error ){
  echo $mysqli->connect_error;
  exit();
}
$mysqli->set_charset("utf8");

$sql = <<<EOT
select
  chara,name
from _chara
order by chara
EOT;

if(! $result = $mysqli->query($sql) ){exit();}
$rows = [];
while ($row = $result->fetch_assoc()){
  $rows[] = $row;
}
echo json_encode($rows, JSON_UNESCAPED_UNICODE);

// $result->close();
// $mysqli->close();

