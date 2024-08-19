<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=ql_nha_hang", "root", "");
    $pdo->query("set names utf8");
} catch (PDOException $ex) {
    echo "Lỗi kết nối: " . $ex->getMessage();
    die();
}
?>
<body>
    
</body>
</html>