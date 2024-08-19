<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách món ăn</title>
    <style>
        .content {
            width: 80%;
            margin: 0 auto;
        }
        .mon_an {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .mon_an img {
            width: 100px;
            height: 100px;
        }
        .le {
            background-color: #f9f9f9;
        }
        .pagination {
            text-align: center;
        }
        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            color: #000;
        }
        .pagination a.active {
            font-weight: bold;
        }
    </style>
</head>
<?php
    include("Pager.php");
    include("Connection.php");

    // Fetch all records to count total number of records
    $sql = "SELECT ma_mon, ten_mon, noi_dung_tom_tat, don_gia, hinh FROM mon_an";
    $sta = $pdo->query($sql);
    $mon_an = $sta->fetchAll(PDO::FETCH_ASSOC);

    // Initialize pagination
    $p = new Pager();
    $limit = 3; // Number of records per page
    $count = count($mon_an); // Total number of records
    $vt = $p->findStart($limit); // Start position
    $pages = $p->findPages($count, $limit); // Total number of pages

    $cur = isset($_GET["page"]) ? $_GET["page"] : 1; // Current page
    $phantrang = $p->nextPrev($cur, $pages); // Generate pagination links

    // Fetch records for the current page
    $sql = "SELECT ma_mon, ten_mon, noi_dung_tom_tat, don_gia, hinh FROM mon_an LIMIT $vt, $limit";
    $sta = $pdo->query($sql);
    $mon_an = $sta->fetchAll(PDO::FETCH_ASSOC);
?>
<body>
<div class="content">
    <h2>Danh sách món ăn</h2>
    <?php
        for($i = 0; $i < count($mon_an); $i++) {
            $bg = ($i % 2 != 0) ? "le" : "";
            echo "<div class='mon_an $bg'>";
            echo "<img src='image_MonAn/" . $mon_an[$i]['hinh'] . "' alt='" . $mon_an[$i]['ten_mon'] . "'>";
            echo "<h3>" . $mon_an[$i]['ten_mon'] . "</h3>";
            echo "<p>" . $mon_an[$i]['noi_dung_tom_tat'] . "</p>";
            echo "<p>Giá: " . $mon_an[$i]['don_gia'] . " VND</p>";
            echo "</div>";
        }
    ?>
    <div class="pagination">
        <?php echo $phantrang; ?>
    </div>
</div>
</body>
</html>
