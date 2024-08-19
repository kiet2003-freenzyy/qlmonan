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
<div class="container">
<div class="btn btn-primary" style="width:100%; height:100px;"></div>
<h1 align="center" style="margin:20px;">THÔNG TIN ĐƠN HÀNG</h1>
<div class="row mt-3">
        <div class="col-md-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Giỏ hàng</span>
            <span class="badge badge-secondary badge-pill"><?php echo $cartItemCount;?></span>
          </h4>
          <ul class="list-group mb-3">
            <?php
                $total = 0;
                foreach($_SESSION['cart'] as $key=>$cartItem)
                {
                    $total+=$cartItem['sl']*$cartItem['donGia'];
                ?>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0"><?php echo $cartItem['tenMon'] ?></h6>
                            <small class="text-muted">Quantity: <?php echo $cartItem['sl'] ?> X Price: <?php echo $cartItem['donGia'] ?></small>
                        </div>
                        <span class="text-muted">$<?php echo $cartItem['sl']*$cartItem['donGia']?></span>
                    </li>
            <?php
                }
            ?>
           
            <li class="list-group-item d-flex justify-content-between">
              <span>Total (USD)</span>
              <strong>$<?php echo number_format($total,2);  $_SESSION['total'] = $total;?></strong>
            </li>
          </ul>
        </div>
        <div class="col-md-8 order-md-1">
          <h4 class="mb-3">Địa chỉ đơn hàng</h4>
          <?php 
           /*  if(isset($errorMsg) && count($errorMsg) > 0)
            {
                foreach($errorMsg as $error)
                {
                    echo '<div class="alert alert-danger">'.$error.'</div>';
                }
            } */
          ?>
          <form method="POST" action="ThongBaoDH.php" >
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="fullName">Full name</label>
                <input type="text" class="form-control" id="fullName" name="full_name" placeholder="Full Name"  >
              </div>
              
            </div>

            <div class="mb-3">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com">
            </div>

            <div class="mb-3">
              <label for="address">Address</label>
              <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" >
            </div>

			<div class="mb-3">
              <label for="address">Phone</label>
              <input type="text" class="form-control" id="phone" name="phone" placeholder="09323456789" >
            </div>
            
            <div class="mb-3">
              <label for="address">Image</label>
              <input type="file" class="form-control" id="image" name="image" placeholder="image">
            </div> 
                      
            <hr class="mb-4">

            <button class="btn btn-primary btn-lg btn-block" type="submit" name="submit" value="submit">Tiếp tục thanh toán</button>
          </form>
           </div>
        </div>
      </div>
      </div>

</body>
</	html>