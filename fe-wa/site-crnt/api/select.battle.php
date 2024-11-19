<?
header("Access-Control-Allow-Origin: *");

require("config.php");

$mysqli = new mysqli($host, $user, $pw, $db);
if( $mysqli->connect_error ){
  echo $mysqli->connect_error;
  exit();
}
$mysqli->set_charset("utf8");

$map = $_GET["map"]; $x = $_GET["x"]; $y = $_GET["y"];

$sql = <<<EOT
select
  _m.name as map,
  b.x,b.y,b.lv,
  _b.name as battle,
  b.sortie
from battle b
left outer join _map _m
on (b.map=_m.id)
left outer join _battle _b
on (b.battle=_b.id)
where b.map='$map' and b.x=$x and b.y=$y
EOT;
// echo $sql;

if(! $result = $mysqli->query($sql) ){exit();}
$rows = [];
while ($row = $result->fetch_assoc()){
  $rows[] = $row;
}
echo json_encode($rows, JSON_UNESCAPED_UNICODE);

// $result->close();
// $mysqli->close();

