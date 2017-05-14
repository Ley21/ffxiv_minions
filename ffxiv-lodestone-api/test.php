<body>
<?php

include_once("lodestone_api.php");

/*
$ids = array("4476212","2215586","2238881");

echo "Speed Test</br>=======</br></br>";
foreach($ids as $id){
    $startTime = microtime(true);

    Lodestone::findCharacterById("4476212");

    echo "Elapsed time is: ". (microtime(true) - $startTime) ." seconds.</br>";
}
*/

$charakter = Lodestone::findCharacterByNameAndServer("Bun Bun","Cactuar");

foreach($charakter as $var => $value){
    echo "$var is $value</br>";
}

foreach ($charakter->mounts as $mount) {
    echo $mount['name'];
}
?>
</body>
</html>