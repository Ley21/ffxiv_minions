<body>
<?php

include_once("lodestone_api.php");


$charakter = Lodestone::findCharacterByNameAndServer("Ley Sego","Shiva");

foreach($charakter as $var => $value){
    echo "$var is $value</br>";
}
?>
</body>
</html>