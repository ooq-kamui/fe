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
// $chara = '"lysithea","dorothea"';
$cnt = count($charas);

$sql = <<<EOT
select
  m.meal,
  _m.name,
  count(m.chara) as cnt,
  group_concat(m.fav) as fav,
  group_concat(_c.name) as chara
from meal m
inner join _meal _m
  on (m.meal=_m.meal)
inner join _chara _c
  on (m.chara=_c.chara)
where 
      m.fav in ("f")
      -- m.fav in ("f"," ")
  and m.chara in ( $chara )
group by meal
having 
          cnt >= $cnt
   -- and fav not like "% %"
   -- and fav = "f,f"
   -- and fav like "%f%"
order by cnt desc,fav desc,_m.odr,chara
EOT;

if(! $result = $mysqli->query($sql) ){exit();}
$rows = [];
while ($row = $result->fetch_assoc()){
  $rows[] = $row;
}
echo json_encode($rows, JSON_UNESCAPED_UNICODE);

// $result->close();
// $mysqli->close();

