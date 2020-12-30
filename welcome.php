  
<?php
session_start();
 
// Kiểm tra xem người dùng đã đăng nhập chưa, nếu chưa thì chuyển hướng người đó đến trang đăng nhập
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Chào mừng bạn đến với website adbxyz.</h1>
    </div>
    <p>
        <a href="logout.php" class="btn btn-danger">Đăng xuất tài khoản</a>
    </p>
</body>
</html>