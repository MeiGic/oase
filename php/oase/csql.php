<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8" />
</head>
<body>
<h1>店鋪一覽</h1>
<table border="1">
    <tr>
        <th>編號</th>
        <th>店名</th>
        <th>地址</th>
    </tr>
<?php
$link = mysql_connect("127.0.0.1" , "root" , "meigic1212");
mysql_query("SET names 'utf8'");
mysql_select_db('oase' , $link);

$sql = "SELECT * from oase_unit";
$query = mysql_query($sql , $link);
while($r = mysql_fetch_array($query , MYSQL_ASSOC)){
?>
    <tr>
        <td><?=$r['u_id']?></td>
        <td><?=$r['u_name']?></td>
        <td><?=$r['u_address']?></td>
    </td>
<?
}

?>
</table>

</body>
</html>
