<?php 
    session_start();
    // Nếu chưa tồn tại thì khởi tạo giỏ
    if(!isset($_SESSION['cart'])) $_SESSION['cart']=[];

    //Lấy dl từ form Xem Chi Tiết
    if(isset($_POST['add_to_cart']) && ($_POST['add_to_cart']))
    {
        $maMon = $_POST['maMon'];
        $tenMon=$_POST['tenMon'];
        $hinh=$_POST['hinh'];
        $donGia=$_POST['donGia'];
        $sl=$_POST['sl'];

        //Kiểm tra SP có trong giỏ hàng hay không 
        $flag = 0;
        foreach( $_SESSION['cart'] as $key=>$item )
        {
            if($item["maMon"]== $maMon)
            {
                $flag = 1;
                $sl_new= $sl + $item["sl"];
                $item["sl"] = $sl_new;
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
