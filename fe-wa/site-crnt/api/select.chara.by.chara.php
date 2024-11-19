<?
header("Access-Control-Allow-Origin: *");

require("config.php");

$mysqli = new mysqli($host, $user, $pw, $db);
if( $mysqli->connect_error ){
  echo $mysqli->connect_error;
  exit();
}
$mysqli->set_charset("utf8");

$charas = explode(',', $_GET["chara"]);
foreach($charas as $idx => $chara){
  $charas[$idx] = "'".$chara."'";
}
$chara = implode(',', $charas);

$sql = <<<EOT
select
  _m.name as map,
  c.x,c.y,
  _c.name,
  c.atr
from (
  select *
  from chara
  where chara in ( $chara )
) c
left outer join _map _m
on (c.map=_m.id)
left outer join _chara _c
on (c.chara=_c.id)
order by c.map,c.x,c.y
EOT;

if(! $result = $mysqli->query($sql) ){exit();}
$rows = [];
while ($row = $result->fetch_assoc()){
  $rows[] = $row;
}
echo json_encode($rows, JSON_UNESCAPED_UNICODE);

// $result->close();
// $mysqli->close();

