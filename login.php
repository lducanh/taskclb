<?php
session_start();
  include 'configuration.php';
//Kiểm tra xem người dùng đã đăng nhập chưa, nếu rồii thì chuyển hướng người đó đến trang welcome
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
 
// Định nghĩa biến và khởi tạo với giá trị trống
$username = $password = "";
$username_err = $password_err = "";
 
// Xử lý dữ liệu biểu mẫu khi biểu mẫu được gửi
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    //Kiểm tra xem tên người dùng có trống không
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Kiểm tra xem mật khẩu có trống không
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Xác thực thông tin đăng nhập
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
           // Liên kết các biến với câu lệnh đã chuẩn bị dưới dạng tham số
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Đặt tham số
            $param_username = $username;
            
            // thực hiện câu lệnh đã chuẩn bị

            if(mysqli_stmt_execute($stmt)){
                // Lưu trữ kết quả
                mysqli_stmt_store_result($stmt);
                
                // Kiểm tra xem tên người dùng có tồn tại không, nếu có thì xác minh mật khẩu
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Các biến kết quả ràng buộc
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            //Mật khẩu đúng thì chuyển tiếp
                            session_start();
                            
                            // Lưu trữ dữ liệu trong các biến
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $username;                            
                            
                            //Chuyển hướng người dùng đến trang welcome
                            header("location: welcome.php");
                        } else{
                            // Hiển thị thông báo lỗi nếu mật khẩu không hợp lệ
                            $password_err = "Mật khẩu bạn vừa nhập không đúng";
                        }
                    }
                } else{
                    // Hiển thị thông báo lỗi nếu tên người dùng không tồn tại
                    $username_err = "Không tìm thấy tài khoản với tên người dùng đó.";
                }
            } else{
                echo "Đã xảy ra lỗi, vui lòng thử lại sau.";
            }
        }
        
        mysqli_stmt_close($stmt);
    }
    
    //Đóng kết nối
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ĐĂNG NHẬP</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>ĐĂNG NHẬP</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Bạn  chưa có tài khoản? <a href="register.php">Đăng ký ngay!!</a>.</p>
        </form>
    </div>    
</body>
</html>