<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Thêm Khách Hàng</title>
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
                <h1 class="text-center">Thêm Khách Hàng</h1>
                <form action="CreateCustomer.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="ten_kh">Tên Khách Hàng</label>
                        <input type="text" class="form-control" id="ten_kh" name="ten_kh" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="dia_chi">Địa Chỉ</label>
                        <input type="text" class="form-control" id="dia_chi" name="dia_chi" required>
                    </div>
                    <div class="form-group">
                        <label for="dien_thoai">Điện Thoại</label>
                        <input type="text" class="form-control" id="dien_thoai" name="dien_thoai" required>
                    </div>
                    <div class="form-group">
                        <label for="hinh">Hình</label>
                        <input type="file" class="form-control-file" id="hinh" name="hinh">
                    </div>
                    <div class="form-group">
                        <label for="ghi_chu">Ghi Chú</label>
                        <textarea class="form-control" id="ghi_chu" name="ghi_chu" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm Khách Hàng</button>
                </form>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $ten_kh = $_POST['ten_kh'];
                    $email = $_POST['email'];
                    $dia_chi = $_POST['dia_chi'];
                    $dien_thoai = $_POST['dien_thoai'];
                    $ghi_chu = $_POST['ghi_chu'];
                    
                    // Upload file
                    $target_dir = "image/";
                    $target_file = $target_dir . basename($_FILES["hinh"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    
                    // Check if image file is a actual image or fake image
                    $check = getimagesize($_FILES["hinh"]["tmp_name"]);
                    if($check !== false) {
                        $uploadOk = 1;
                    } else {
                        echo "File is not an image.";
                        $uploadOk = 0;
                    }
                    
                    // Check if file already exists
                    if (file_exists($target_file)) {
                        echo "Sorry, file already exists.";
                        $uploadOk = 0;
                    }
                    
                    // Check file size
                    if ($_FILES["hinh"]["size"] > 500000) {
                        echo "Sorry, your file is too large.";
                        $uploadOk = 0;
                    }
                    
                    // Allow certain file formats
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif" ) {
                        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                        $uploadOk = 0;
                    }
                    
                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                        echo "Sorry, your file was not uploaded.";
                    // if everything is ok, try to upload file
                    } else {
                        if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $target_file)) {
                            echo "The file ". basename( $_FILES["hinh"]["name"]). " has been uploaded.";
                        } else {
                            echo "Sorry, there was an error uploading your file.";
                        }
                    }

                    try {
                        $pdo = new PDO("mysql:host=localhost;dbname=ql_nha_hang", "root", "");
                        $pdo->query("SET NAMES utf8");
                        $stmt = $pdo->prepare("INSERT INTO khach_hang (ten_khach_hang, email, dia_chi, dien_thoai, hinh, ghi_chu) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$ten_kh, $email, $dia_chi, $dien_thoai, basename($_FILES["hinh"]["name"]), $ghi_chu]);
                        echo "<div class='alert alert-success mt-3'>Thêm khách hàng thành công!</div>";
                    } catch (PDOException $e) {
                        echo "<div class='alert alert-danger mt-3'>Lỗi: " . $e->getMessage() . "</div>";
                    }
                }
                ?>
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
