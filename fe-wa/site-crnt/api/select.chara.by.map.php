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
  c.chara,c.atr,c.zoen
from battle b
left outer join chara c
on (b.map=c.map and b.x=c.x and b.y=c.y)
where b.map='$map' and b.x=$x and b.y=$y
EOT;

if(! $result = $mysqli->query($sql) ){exit();}
$rows = [];
while ($row = $result->fetch_assoc()){
  $rows[] = $row;
}
echo json_encode($rows, JSON_UNESCAPED_UNICODE);

// $result->close();
// $mysqli->close();

