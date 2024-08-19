
<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title></title>

    <!--Bootstrap 4-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.rawgit.com/PascaleBeier/bootstrap-validate/v2.2.0/dist/bootstrap-validate.js"></script>
    <style>
		.dd_mon{
			width:100%;
			border:1px solid grey;
			float:left;
			margin:5px;}
    </style>

</head>
<?php 
    // GIỎ HÀNG
    session_start();
    // Nếu chưa tồn tại thì khởi tạo giỏ
    if(!isset($_SESSION['cart'])) $_SESSION['cart']=[];

    //Xóa ALL giỏ
    if(isset($_GET['emptyCart'])&& ($_GET['emptyCart']==1)) unset($_SESSION['cart']);

    //Xóa item trong Giỏ
    if(isset($_GET['delId']) && ($_GET['delId']>=0))
    {
       // unset($_SESSION['cart'][$_GET['delId']]);
       array_splice($_SESSION['cart'],$_GET['delId'],1);
    }

    // Update item trong giỏ
    if(isset($_GET['updateId']) && ($_GET['updateId']>=0))
    {   $index = $_GET['updateId'];
        if (isset($_SESSION['cart'][$index])) {
            $new_quantity = $_GET['num_sl']; // Số lượng mới
            $_SESSION['cart'][$index]['sl'] = $new_quantity;
        }
    }

    //Lấy dl từ form Xem Chi Tiết
    if(isset($_POST['add_to_cart']) && ($_POST['add_to_cart']))
    {
        $maMon = $_POST['maMon'];
        $tenMon=$_POST['tenMon'];
        $hinh=$_POST['hinh'];
        $donGia=$_POST['donGia'];
        $sl=$_POST['sl'];

        //Kiểm tra SP có trong giỏ hàng hay không 
        /* $flag = 0;
         foreach( $_SESSION['cart'] as $key=>&$item )
        {
            if($item['maMon']== $maMon)
            {
                $flag = 1;
                $sl_new= $sl + $item['sl'];
                $item['sl'] = $sl_new;
                $_SESSION['cart'][$key] = $item;
                break;
            }
        } */
        $flag = 0;
        $count = count($_SESSION['cart']);
        for ($i = 0; $i < $count; $i++) {
            $item = $_SESSION['cart'][$i]; 
            if ($item["maMon"] == $maMon) {
                $flag = 1;
                $sl_new = $sl + $item["sl"];
                $item["sl"] = $sl_new; // Cập nhật số lượng trực tiếp trong mảng $_SESSION['cart']
                $_SESSION['cart'][$i] = $item;
                break;
            }
        }

        //Thêm SP vào giỏ nếu kg trùng
        if ($flag == 0)
        {
           // $sp = [$maMon,$tenMon, $hinh,$donGia,$sl];
            $sp= array(
                'maMon'=>$maMon,
                'tenMon'=>$tenMon,
                'hinh'=>$hinh,
                'donGia'=>$donGia,
                'sl'=>$sl,
            );
            $_SESSION['cart'][]= $sp;
        } 
    }
   
?>

<?php 
   
// ĐỌC DỮ LIỆU TỪ TABLE Loai_Mon_An - Khai báo biến đối tượng PDO kết nối CSDL
    $pdo = new PDO("mysql:host=localhost;dbname=ql_nha_hang","root","");
    $pdo->query("set names utf8");

    $sql = "select ma_loai, ten_loai, mo_ta from loai_mon_an";
    $loai_mon = $pdo->query($sql);

    $pdo = NULL;

// ĐỌC DỮ LIỆU TỪ TABLE Mon_An - Khai báo biến đối tượng PDO kết nối CSDL
    $pdo1 = new PDO("mysql:host=localhost;dbname=ql_nha_hang","root","");
    $pdo1->query("set names utf8");
    
    $sql1 = "select * from mon_an";
    $mon_an = $pdo1->query($sql1);
    $pdo1 = NULL;

// Món Theo Loai
    $pdo2 = new PDO("mysql:host=localhost;dbname=ql_nha_hang","root","");
    $pdo2->query("set names utf8");
    if(isset($_GET['ml']))
    {
        if ($_GET["ml"]==NULL)
            $ml = 0; // show all
        else
            $ml = $_GET["ml"];

        if ($ml == 0)
            $sql2 = "select * from mon_an";
        else
            $sql2 = "select * from mon_an where ma_loai =".$ml;

        $mon_an2 = $pdo2->query($sql2);
        $pdo2 = NULL;
    }   
    // Món theo Giá
    $pdo3 = new PDO("mysql:host=localhost;dbname=ql_nha_hang","root","");
    $pdo3->query("set names utf8");
    if(isset($_GET['gt']) && isset($_GET['gc']))
    {
        $gt = $_GET['gt'];
        $gc = $_GET['gc'];
        if ($gt == 0 && $gc ==0)
			$sql3 = "select * from mon_an";
		else
			$sql3 = "select * from mon_an where don_gia >".$gt."&& don_gia<=".$gc;
		
		$mon_an3 = $pdo3->query($sql3);
        $pdo3 = NULL;
    }
    // Tìm kiếm theo tên
    $pdo4 = new PDO("mysql:host=localhost;dbname=ql_nha_hang","root","");
    $pdo4->query("set names utf8");
    if(isset($_GET['txt_Search']))
    {
        $Ten = $_GET["txt_Search"];
		$sql4 = "select * from mon_an where ten_mon like '%".$Ten."%'"; 
		$mon_an4 = $pdo4->query($sql4);
        $pdo4 = NULL;
    }
    /* Chi Tiết món ăn
        $pdo5 = new PDO("mysql:host=localhost;dbname=ql_nha_hang","root","");
		$pdo5->query("set names utf8");
		
		$maMon = $_GET["id"];
		$sql5 = "select * from mon_an where ma_mon =".$maMon; 
		$mon = $pdo2->query($sql5);*/

?>
<body>

    <div id="Wrapper" class="container">
        <br />
        <div id="header" class="row bg-light">
            <div class="col-4">
            <br>
               <img src="Images/Logo.jpg" width="100%" height="70%" />
            </div>
            <div class="col-4">
                <b>Hà Nội:</b><br />
                Điện thoại: 024.73007.008 - 093.4647.172<br />
                Địa chỉ: Số 63/445 Lạc Long Quân, Tây Hồ, Hà Nội<br />
                Email: hn@ganhxua.com
            </div>
            <div class="col-4">
                <b>TP.Hồ Chí Minh:</b><br />
                Điện thoại: 028.73007.008 - 094.7723.444<br />
                Địa chỉ: 189 XVN Tĩnh, P.17, Q. Bình Thạnh<br />
                Email: hcm@ghanhxua.com
            </div>
        </div>
    <form method="get" action="Bai5.php">   
        <div id="menu" style="background-color:yellowgreen;">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"><img src="Images/HomeLogo.jpg" width="70" class="rounded" /></a>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                            <li class="nav-item nav-link active">
                                <a class="nav-link" href="Bai5.php">Trang Chủ </a>
                            </li>
                            <li class="nav-item nav-link active">
                                <a class="nav-link" href="#">Đăng ký </a>
                            </li>
                            <li class="nav-item nav-link active">
                                <a class="nav-link" href="#">Đăng Nhập </a>
                            </li>
                            <li class="nav-item nav-link active">
                                <a class="nav-link " href="#">Liên hệ </a>
                            </li>
                        </ul>
                        <input class="form-control mr-2 w-25" type="text" placeholder="Search" aria-label="Search" name="txt_Search" >
                        <button class="btn btn-outline-dark"  style="margin-right:10px;">Search</button>
                        <a href="ShowCart.php" style="color:#ffffff">
                            <i class="bi bi-cart4" style="font-size:30px; color:black;"></i>
                            <?php 
                                echo (isset($_SESSION['cart']) && count($_SESSION['cart'])) > 0 ? count($_SESSION['cart']):'';
                            ?>
                        </a>

                    </div>
                </div>
            </nav>
        </div>
    </form>     
        <div id="Content" class="row" style="margin-top:5px;">
        	<div class="col-3">
                <ul class="list-group list-group-flush text-left bg-light">
                    <li class="list-group-item" style="background-color:yellowgreen"><a href="#" style="text-transform:uppercase; text-decoration:none; color:white; font-weight:bold;">Loại món</a></li>
                    
                    <?php
                    foreach($loai_mon as $loai)
                    {
                    ?>
                        <li class="list-group-item bg-light"><a href="Bai5.php?ml=<?php echo $loai['ma_loai']?>&tl=<?php echo $loai['ten_loai'] ?>" style="text-transform:uppercase; text-decoration:none;"><?php echo $loai['ten_loai'] ?></a></li>
                    <?php 
                    }
                    ?>    
                    <li class="list-group-item bg-light"><a href="Bai5.php?ml=0&tl=TẤT CẢ CÁC MÓN ĂN" style="text-transform:uppercase; text-decoration:none;">SHOW ALL</a></li>               
                </ul>
                <ul class="list-group list-group-flush text-left bg-light">
                    <li class="list-group-item" style="background-color:yellowgreen;"><a href="#" style="text-transform:uppercase; text-decoration:none; color:white; font-weight:bold;">Chọn theo giá</a></li>
                    <li class="list-group-item bg-light"><a href="Bai5.php?gt=1000&gc=15000">15,000đ trở xuống</a></li>
                    <li class="list-group-item bg-light"><a href="Bai5.php?gt=20000&gc=30000">20,000đ - 30,000đ</a></li>
                    <li class="list-group-item bg-light"><a href="Bai5.php?gt=31000&gc=50000">31,000đ - 50,000đ</a></li>
                    <li class="list-group-item bg-light"><a href="Bai5.php?gt=51000&gc=100000">51,000đ - 100,000đ</a></li>
                    <li class="list-group-item bg-light"><a href="Bai5.php?gt=1001000&gc=2000000">Trên 100,000đ</a></li>
                    
                </ul>
            </div>
            <div class="col-9">
            <!--  Content
            -->  
                <h2 align="center" style="color:#900;">THÔNG TIN GIỎ HÀNG CỦA BẠN </h2>
                <?php if(empty($_SESSION['cart']))
                {?>
                <table class="table">
                    <tr>
                        <td>
                            <p>Your cart is emty</p>
                        </td>
                    </tr>
                </table>
                <?php 
                }?>
            <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){?>
            <table class="table">
            <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $totalCounter = 0;
                        $itemCounter = 0;
                        $count = count($_SESSION['cart']);
                        for ($i = 0; $i < $count; $i++) {
                            $item = $_SESSION['cart'][$i]; 
                        //foreach($_SESSION['cart'] as $key => $item){
                        $imgUrl = "image_MonAn"."/".$item["hinh"];
                        $total = (float)$item["donGia"]* (int)$item["sl"];
                        $totalCounter += $total;
                        $itemCounter += $item["sl"];
                        ?>
                        <tr>
                            <td>
                                <img src="<?php echo $imgUrl; ?>" class="rounded img-thumbnail mr-2" style="width:60px;"><?php echo $item['tenMon'];?>
                                
                                <a href="ShowCart.php?delId=<?php echo $i?>" class="text-danger">
                                    <i class="bi bi-trash-fill"></i>
                                </a>

                            </td>
                            <td>
                                $<?php echo $item['donGia'];?>
                            </td>
                            <td>
                                <form action="ShowCart.php" method="get">
                                    <input type="hidden" name = "updateId" value="<?php echo $i?>">
                                    <input type="number" name="num_sl" class="cart-qty-single" data-item="<?php echo $key?>" value="<?php echo $item['sl'];?>" min="1" max="1000" >
                                    <button type="submit" class="text-primary"><i class="bi bi-pencil-square"></i></button>
                                    
                               
                                </form>
                            </td>
                            <td>
                                <?php echo $total;?>
                            </td>
                        </tr>
                    <?php }?>
                    <tr class="border-top border-bottom">
                        <td><a class="btn btn-danger btn-sm" href="ShowCart.php?emptyCart=1">Clear Cart</a></td>
                        <td></td>
                        <td>
                            <strong>
                                <?php 
                                    echo ($itemCounter==1)?$itemCounter.' item':$itemCounter.' items'; ?>
                            </strong>
                        </td>
                        <td><strong>$<?php echo $totalCounter;?></strong></td>
                    </tr> 
                    </tr>
                </tbody> 
            </table>
            <div class="row">
            <div class="col-md-11">
				<a href="Checkout.php">
					<button class="btn btn-primary btn-lg float-right">Checkout</button>
				</a>
            </div>
        </div>
        
        <?php }?>


            </div>
        </div>
        <div id="Footer" class="row" style="background-color:yellowgreen;">
            <div class="col text-light mt-3 mb-3" style="text-align:center;">
                Liên hệ: Khoa Công nghệ Thông tin - Trường Đại học Công nghiệp Thực phẩm Tp.HCM Link: fanpage và link: youtube <br />
                Địa chỉ: 140 Lê Trọng Tấn, Phường Tây Thạnh, Quận Tân Phú, Tp.HCM. ĐT: (028).38161673 (ext 136) Mail: kcntt@hufi.edu.vn
            </div>
        </div>
    </div>
 </body>
</html>