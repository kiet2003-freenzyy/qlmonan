<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách món ăn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        #main {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .Khung {
            display: flex;
            align-items: center;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            background-color: #fafafa;
            transition: background-color 0.3s;
        }
        .Khung:hover {
            background-color: #f0f0f0;
        }
        .Khung img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-right: 20px;
        }
        .Khung h3 {
            margin: 0;
            color: #333;
        }
        .Khung p {
            margin: 5px 0 0;
            color: #666;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            display: inline-block;
            padding: 10px 15px;
            margin: 0 5px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
            transition: background-color 0.3s;
        }
        .pagination a:hover {
            background-color: #f0f0f0;
        }
        .pagination a.active {
            font-weight: bold;
            background-color: #333;
            color: #fff;
        }
    </style>
</head>
<?php
    include("Pager.php");
    include("Connection.php");

    $sql = "SELECT ma_mon, ten_mon, noi_dung_tom_tat, don_gia, hinh FROM mon_an";
    $sta = $pdo->prepare($sql);
    $sta->execute();
    
    if($sta->rowCount() > 0) {
        $mon_an = $sta->fetchAll(PDO::FETCH_OBJ);
    }

    $p = new Pager();
    $limit = 2;
    $count = count($mon_an);
    $vt = $p->findStart($limit);
    $pages = $p->findPages($count, $limit);

    $cur = isset($_GET["page"]) ? $_GET["page"] : 1;
    $phantrang = $p->pageList($cur, $pages);

    $sql = "SELECT ma_mon, ten_mon, noi_dung_tom_tat, don_gia, hinh FROM mon_an LIMIT $vt, $limit";
    $sta = $pdo->prepare($sql);
    $sta->execute();
    $mon_an = $sta->fetchAll(PDO::FETCH_OBJ);
?>
<body>
    <div id="main">
        <h1>Danh sách món ăn</h1>
        <?php
        foreach ($mon_an as $mon) {
            ?>
            <div class="Khung">
                <img src="image_MonAn/<?php echo $mon->hinh; ?>" alt="<?php echo $mon->ten_mon; ?>">
                <div>
                    <h3><?php echo $mon->ten_mon; ?></h3>
                    <p><?php echo $mon->noi_dung_tom_tat; ?></p>
                    <p>Đơn giá: <?php echo number_format($mon->don_gia, 0, ',', '.'); ?> VND</p>
                </div>
            </div>
        <?php
        }
        ?>
        <div class="pagination"><?php echo $phantrang; ?></div>
    </div>
</body>
</html>
