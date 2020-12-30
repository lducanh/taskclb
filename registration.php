<?php
 include 'configuration.php';
// Xác định các biến và khởi tạo với các giá trị trống
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Xử lý dữ liệu biểu mẫu khi biểu mẫu được gửi
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    //Xác thực tên người dùng
	
    if(empty(trim($_POST["username"]))){
        $username_err = "Hãy nhập tên người d";
    } else{
        $sql = "SELECT username FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Liên kết các biến với câu lệnh đã chuẩn bị dưới dạng tham số
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Đặt thông số
            $param_username = trim($_POST["username"]);
            
            //Thực hiện cl
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Tên người dùng này đã được sử dụng.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Đã xảy ra lỗi. Vui lòng thử lại sau.";
            }
        }
             // Đóng câu  lệnh
        mysqli_stmt_close($stmt);   
    }
    
    // Xác thực mật khẩu
    if(empty(trim($_POST["password"]))){
        $password_err = "Vui lòng nhập mật khẩu
        .";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Mật khẩu phải có ít nhất 6 ký tự.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    //Xác thực xác nhận mật khẩu
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Vui lòng xác nhận mật khẩu.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Kiểm tra lỗi đầu vào trước khi chèn vào cơ sở dữ liệu

    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Chuẩn bị một câu lệnh chèn
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Liên kết các biến với câu lệnh đã chuẩn bị dưới dạng tham số
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Tạo mật khẩu
            
            // Thực hiện câu lệnh đã chuẩn bị
            if(mysqli_stmt_execute($stmt)){
                // Chuyển hướng đến trang đăng nhập
			   header("location: login.php");
            } else{
                echo "Đã xảy ra lỗi. Vui lòng thử lại sau.";
            }
        }
         
        // Đóng câu lệnh
        mysqli_stmt_close($stmt);
    }
    // đóng kết nối
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ĐĂNG KÝ</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>ĐĂNG KÝ TÀI KHOẢN</h2>
        <p>Điền thông tin vào để tạo tài khoản.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Bạn đã có tài khoản? <a href="login.php">Đăng nhập ngay!!</a>.</p>
        </form>
    </div>    
</body>
</html>