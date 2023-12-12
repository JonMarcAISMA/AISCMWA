<?php
session_start();
include 'db_connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($username === 'admin' && $password === 'admin') {
     
        header("Location: superadmin_dashboard.php");
        exit();
    } elseif ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['id'];

        $scheduleGenerated = $user['schedule_generated'];

        if ($scheduleGenerated === '1') {
            header("Location: schedule_display.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    } else {
     
        $_SESSION['error'] = "Invalid username or password";

       
        header("Location: loginpage.php");
        exit();
    }
}


$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;


unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
        font-family: 'Poppins', sans-serif;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #e0f7d8; 
        margin: 0;
    }

        .container {
            width: 50vw;
            height: 90vh;
            display: grid;
            grid-template-columns: 50% 50%;
            grid-template-areas: "design login";
            box-shadow: 0 0 17px 10px rgb(0 0 0 / 30%);
            border-radius: 20px;
            overflow: hidden;
        }

        .design {
            grid-area: design;
            display: block;
            position: relative;
        }

        .rotate-45 {
            transform: rotate(-45deg);
        }

      

        .login {
    grid-area: login;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    background: #fff; 
    padding: 20px;
    border: 2px solid #76b041;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
        .login h2 {
            text-align: center;
            margin-bottom: 15px;
            color: #76b041;
        }

        .text-input {
            background: #e6e6e6;
            height: 40px;
            display: flex;
            width: 80%;
            margin: 5px auto;
            align-items: center;
            border-radius: 10px;
            padding: 0 15px;
            text-align: left;
        }

        .text-input input {
            background: none;
            border: none;
            outline: none;
            width: 100%;
            height: 100%;
            margin-left: 10px;
            color: #333;
        }

        .text-input i {
            color: #686868;
        }

        ::placeholder {
            color: #9a9a9a;
        }

        .login-btn {
            width: 100%;
            padding: 10px;
            color: white;
            background-color: #76b041;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            margin-top: 10px;
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        p {
            text-align: center;
            margin-top: 10px;
            color: #686868;
        }

        a {
            font-size: 12px;
            color: #9a9a9a;
            cursor: pointer;
            user-select: none;
            text-decoration: none;
            margin-top: 10px;
            color: #76b041;
        }

        a.forgot {
            margin-top: 15px;
        }

        .create {
            display: flex;
            align-items: center;
            position: absolute;
            bottom: 30px;
        }

        .create i {
            color: #9a9a9a;
            margin-left: 10px;
        }

        @media (max-width: 767px) {
            .container {
                grid-template-columns: 100%;
                grid-template-areas: "login";
            }

            .design {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="design">
         
        </div>
        <div class="login">
            <h2>Login</h2>
            <form method="post" action="">
                <div class="text-input">
                    <i>&#128100;</i>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="text-input">
                    <i>&#128273;</i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <input type="submit" name="login" value="Login" class="login-btn">
            </form>
            <?php
            if (isset($error)) {
                echo "<p class='error'>$error</p>";
            }
            ?>
            <p>Don't have an account? <br><a href="register.php">Register</a></p>
        </div>
    </div>
</body>

</html>
