<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "escola";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na ligação: " . $conn->connect_error);
} else {
    echo "Ligação à base de dados 'escola' estabelecida com sucesso!<br>";
    echo "Versão do MySQL: " . $conn->server_info . "<br>";

    $res = $conn->query("SHOW TABLES");
    echo "Tabelas existentes:<ul>";
    while ($row = $res->fetch_array()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
}

$conn->close();
?>
