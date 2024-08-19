<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php
    $pdo = new PDO("mysql:host=localhost; dbname=ql_nha_hang", "root", "");
    $pdo->query("SET Names utf8");
    $sql = "select ma_loai, ten_loai, mo_ta from loai_mon_an";
    $loai_mon = $pdo->query($sql);
    echo "Số mẫu tin trong bảng loai_mon_an là: ".$loai_mon->rowCount();
    $pdo = NULL;
?>
<body>
<?php
    if($loai_mon->rowCount() > 0)
    {
?>
    <table width="800" border="2" cellspacing="0" cellpadding="5" align="center">
        <caption>
            <h1> THÔNG TIN LOẠI MÓN ĂN </h1>
        </caption>
        <tr bgcolor="#99FF99" align="center" styles="font-weight:bold">
            <td>Mã Loại</td>
            <td>Tên Loại</td>
            <td>Mô tả</td>
        </tr>
        <?php
        foreach($loai_mon as $loai)
        {
        ?>
            <tr>
                <td><?php echo $loai['ma_loai'];?></td>
                <td><?php echo $loai['ten_loai'];?></td>
                <td><?php echo $loai[2];?></td>
            </tr>
        <?php
        } 
        ?>
    </table>
    <?php 
    }
    $pdo = NULL;
    ?>
</body>
</html>