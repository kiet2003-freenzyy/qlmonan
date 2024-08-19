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
			width:32%;
			border:1px solid grey;
			float:left;
			margin:5px;}
    </style>

</head>
<?php 
session_start();
    
    $cartItemCount = count($_SESSION['cart']);
    
   // Tạo kết nối
   try{
        $pdo = new PDO("mysql:host=localhost;dbname=ql_nha_hang","root","");
        $pdo->query("set names utf8");
        }
    catch (PDOException $ex){
        echo "Lỗi kết nối" . $ex->getMessage();
        die();	
        }
		
	
    if(isset($_POST['submit']))
    {
        if(isset($_POST['full_name'],$_POST['email'],$_POST['address']) && !empty($_POST['full_name']) && !empty($_POST['email']) && !empty($_POST['address']) )
        {
            	$CustomerID ="NULL";
                $fullName  = $_POST['full_name'];
                $email     = $_POST['email'];
                $address   = $_POST['address'];
                $phone	= $_POST['phone'];
                $image	= ""; //$_FILES["image"]["error"]==0 ? $_FILES["image"]["name"] : "";
                $note =  "";
				
				
                $sql = 'Select count(*) As count from khach_hang where email='."'".$email."'";
                      
                $statement = $pdo->prepare($sql);
                $statement->execute();
                $kq = $statement->fetch(PDO::FETCH_ASSOC);

                $count = $kq['count'];
                echo "Count: " . $count;
				
				if ($count == 0)
				{
					$sql1 = 'INSERT INTO khach_hang VALUES(?, ?, ?, ?, ?, ?, ?)';
					$param = array($CustomerID, $fullName, $email, $address,  $phone, $image, $note);
					$statement = $pdo->prepare($sql1);
          $statement->execute($param);

          $getCustomerId = $pdo->lastInsertId();
                  //  echo "MAKH: ".$getCustomerId;
        }
				else
                {               
                    $sql2 = 'SELECT * FROM khach_hang WHERE email = :email'; // chống SQL injection
                    $statement = $pdo->prepare($sql2);
                    $statement->bindParam(':email', $email);// chống SQL injection
                    $statement->execute();
                    $kq = $statement->fetch(PDO::FETCH_ASSOC);
                    $getCustomerId = $kq['ma_khach_hang'];
                 //   echo "MAKH: ".$getCustomerId;
                }
				// Chèn dl  vào hóa đơn
                $MaHD = NULL;
                $MaKH = $getCustomerId;
                $NgayDat = date('Y-m-d');
                $TongTien = $_SESSION['total']; unset($_SESSION['total']);
                $TienCoc = 0;
                $ConLai = $TongTien - $TienCoc;
                $HTTT = "Tiền Mặt";
                $GhiChu ="";
                
                $sql3 = 'INSERT INTO hoa_don VALUES(?,?,?,?,?,?,?,?)';
                $param3 =array($MaHD, $MaKH, $NgayDat, $TongTien, $TienCoc, $ConLai, $HTTT, $GhiChu);
                $statement = $pdo->prepare($sql3);
                $statement->execute($param3);
                
                // Chèn dl vào Chi tiết HD
                $getOrderId = $pdo->lastInsertId();
                foreach($_SESSION['cart'] as $key=>$item)
                {
                  $MaMon = $item['maMon'] ;
                  $sl= $item['sl'];
                  $donGia = $item['donGia'];
                  $monThucDon = 1;

                  $sql4 = 'INSERT INTO chi_tiet_hoa_don VALUES(?,?,?,?,?)';
                  $param4 = array($getOrderId, $MaMon, $sl, $donGia, $monThucDon);
                  $statement = $pdo->prepare($sql4);
                  $statement->execute($param4);
                }
                

                // Giải phóng biến giỏ hàng
                  unset($_SESSION['cart']);
                  $pdo = NULL;
        }
    }
	
?>
<body>
    <h2> Thông báo đơn hàng của bạn đã được ghi nhận !</h2>
</body>
</html>