<?php
require_once('menu.php');

$soba = new Menu('そば',570);
$sara = new Menu('皿そば', 830);
$tyai = new Menu('琉球チャイ', 510);
$zenzai = new Menu('氷ぜんざい', 310);

$menus = array($soba, $sara, $tyai, $zenzai);

?>