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
  t.x,t.y,t.lv,
  _b.name as battle,
  t.atr,
  _c.name
from (
  select
    c.map,c.x,c.y,chara,c.atr,
    b.lv,b.battle
  from chara c left outer join battle b
    on (c.map=b.map and c.x=b.x and c.y=b.y)
  where b.battle='tougi' and c.chara in ($chara)
) t
left outer join _map    _m on (t.map   =_m.map)
left outer join _battle _b on (t.battle=_b.battle)
left outer join _chara  _c on (t.chara =_c.chara)
order by map,t.lv,t.x,t.y,t.atr
EOT;

if(! $result = $mysqli->query($sql) ){exit();}
$rows = [];
while ($row = $result->fetch_assoc()){
  $rows[] = $row;
}
echo json_encode($rows, JSON_UNESCAPED_UNICODE);

// $result->close();
// $mysqli->close();

