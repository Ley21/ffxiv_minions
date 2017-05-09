<body>
<?php

include_once("lodestone_api.php");


$charakter = Lodestone::findCharacterById("4476212");

foreach($charakter as $var => $value){
    echo "$var is $value</br>";
}

foreach ($charakter->mounts as $mount) {
    echo $mount['name'];
}
?>
</body>
</html>