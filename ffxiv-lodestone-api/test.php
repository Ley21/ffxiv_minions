<body>
<?php

include_once("lodestone_api.php");


$charakter = Lodestone::findCharacterByNameAndServer("Ley Sego","Shiva");

var_dump($charakter);
?>
</body>
</html>