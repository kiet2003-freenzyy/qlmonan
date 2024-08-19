<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Khách Hàng</title>
<!-- Bootstrap 4 -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdn.rawgit.com/PascaleBeier/bootstrap-validate/v2.2.0/dist/bootstrap-validate.js"></script>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
    }
    #Wrapper {
        margin-top: 20px;
    }
    #menu {
        margin-bottom: 20px;
    }
    #Content table {
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    #Content table caption h1 {
        font-size: 1.5em;
        margin-bottom: 10px;
    }
    #Content table tr {
        transition: background-color 0.3s;
    }
    #Content table tr:hover {
        background-color: #f1f1f1;
    }
    #Footer {
        background-color: yellowgreen;
        color: white;
        padding: 10px 0;
        text-align: center;
    }
    #Footer a {
        color: white;
        text-decoration: underline;
    }
    .dd_mon img {
        width: 100%;
        height: 170px;
        object-fit: cover;
    }
</style>
</head>
<body>
    <div id="Wrapper" class="container">
        <div id="menu" style="background-color:yellowgreen;">
            <form method="get" action="SearchCustomer.php">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#"><img src="Images/HomeLogo.jpg" width="70" class="rounded" /></a>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" href="Layout_Admin.php">Khách Hàng</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="ShowCategoryMeal.php">Loại món ăn</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Món ăn</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Đăng ký</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Đăng Nhập</a>
                                </li>
                            </ul>
                            <input class="form-control mr-2 w-25" type="text" placeholder="Search" aria-label="Search" name="txt_Search">
                            <button class="btn btn-outline-dark" style="margin-right:20px;" name="btn_Search">Search</button>
                       </div>
                    </div>
                 </nav>
            </form>
        </div>
        
        <div id="Content" class="row">
            <div class="col-12">
                <a href="CreateCustomer.php" class="btn btn-success mb-3">Thêm Khách Hàng</a>
                <?php
                $pdo = new PDO("mysql:host=localhost;dbname=ql_nha_hang", "root", "");
                $pdo->query("SET Names utf8");
                $sql = "select * from khach_hang";
                $khach_hang = $pdo->query($sql);
                ?>
                <table class="table table-bordered table-hover" align="center">
                    <caption>
                        <h1>THÔNG TIN KHÁCH HÀNG</h1>
                    </caption>
                    <thead class="thead-light">
                        <tr align="center">
                            <th>Mã KH</th>
                            <th>Tên KH</th>
                            <th>Email</th>
                            <th>Địa chỉ</th>
                            <th>Điện thoại</th>
                            <th>Hình</th>
                            <th>Ghi chú</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($khach_hang as $kh) {
                    ?>
                        <tr>
                            <td><?php echo $kh[0]; ?></td>
                            <td><?php echo $kh[1]; ?></td>
                            <td><?php echo $kh[2]; ?></td>
                            <td><?php echo $kh[3]; ?></td>
                            <td><?php echo $kh[4]; ?></td>
                            <td><div class="dd_mon"><img src="image/<?php echo $kh[5]; ?>" /></div></td>
                            <td><?php echo $kh[6]; ?></td>
                            <td>
                                <a href="UpdateCustomer.php?id=<?php echo $kh[0]; ?>" class="btn btn-primary btn-sm">Sửa</a>
                                <a href="DeleteCustomer.php?id=<?php echo $kh[0]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php
                    }
                    $pdo = null;
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div id="Footer" class="row">
            <div class="col text-light mt-3 mb-3">
                Liên hệ: Khoa Công nghệ Thông tin - Trường Đại học Công nghiệp Thực phẩm Tp.HCM Link: <a href="#">fanpage</a> và link: <a href="#">youtube</a><br />
                Địa chỉ: 140 Lê Trọng Tấn, Phường Tây Thạnh, Quận Tân Phú, Tp.HCM. ĐT: (028).38161673 (ext 136) Mail: <a href="mailto:kcntt@hufi.edu.vn">kcntt@hufi.edu.vn</a>
            </div>
        </div>
    </div>
</body>
</html>
