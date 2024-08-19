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
    $sql = "select * from khach_hang";
    $khach_hang = $pdo->query($sql);
    echo "Số mẫu tin trong bảng Khach_Hang là: ".$khach_hang->rowCount();
    $pdo = NULL;
?>
<body>
<?php
    if($khach_hang->rowCount() > 0)
    {
?>
    <table width="800" border="2" cellspacing="0" cellpadding="5" align="center">
        <caption>
            <h1> THÔNG TIN KHÁCH HÀNG </h1>
        </caption>
        <tr bgcolor="#99FF99" align="center" styles="font-weight:bold">
            <td>Mã KH</td>
            <td>Tên KH</td>
            <td>Email</td>
            <td>Địa chỉ</td>
            <td>Điện thoại</td>
            <td>Hình</td>
            <td>Ghi chú</td>
        </tr>
        <?php
        foreach($khach_hang as $kh)
        {
        ?>
            <tr>
                <td><?php echo $kh[0];?></td>
                <td><?php echo $kh[1];?></td>
                <td><?php echo $kh[2];?></td>
                <td><?php echo $kh[3];?></td>
                <td><?php echo $kh[4];?></td>
                <td><div class="dd_mon"><img src="image/<?php echo $kh[5];?>" width="100%"height="170px"/></td>
                <td><?php echo $kh[6];?></td>
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