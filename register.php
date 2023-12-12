<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $collegeYear = $_POST['college_year'];
        $course = $_POST['course'];

  
        if (strtolower($username) === 'admin') {
            $_SESSION['error'] = "Username \"admin\" is not allowed.";
            header("Location: register.php");
            exit();
        }

        
        $check_username_sql = "SELECT * FROM users WHERE username = '$username'";
        $result_username = $conn->query($check_username_sql);

        if ($result_username->num_rows > 0) {
            $_SESSION['error'] = "Username \"$username\" is already taken. Please choose a different username.";
            header("Location: register.php");
            exit();
        }

   
        $check_email_sql = "SELECT * FROM users WHERE email = '$email'";
        $result_email = $conn->query($check_email_sql);

        if ($result_email->num_rows > 0) {
            $_SESSION['error'] = "Email already registered. Please use a different email.";
            header("Location: register.php");
            exit();
        } else {
      
            $sql = "INSERT INTO users (username, password, email, college_year, course) VALUES ('$username', '$password', '$email', '$collegeYear', '$course')";

            if ($conn->query($sql) === TRUE) {
            
                header("Location: loginpage.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}


if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
} else {
    $error = '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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
            grid-template-areas: "design register";
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

        .register {
            grid-area: register;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            background: white;
            padding: 20px;
            border: 2px solid #a8df65; 
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .register h2 {
            text-align: center;
            margin-bottom: 15px;
            font-size: 24px;
            color: #a8df65; 
        }

        .text-input {
            background: rgba(230, 230, 230, 0.8); 
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
            width: 90%; 
            height: 100%;
            margin-left: 10px;
        }

        .text-input i {
            color: #686868;
        }

        ::placeholder {
            color: #9a9a9a;
        }

        .register-btn {
            width: 100%;
            padding: 10px;
            color: white;
            background: linear-gradient(to right, #a8df65, #86c140); 
            border: none;
            border-radius: 20px;
            cursor: pointer;
            margin-top: 10px;
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
        }

        p {
            text-align: center;
            margin-top: 10px;
        }

        a {
            font-size: 12px;
            color: #a8df65; 
            cursor: pointer;
            user-select: none;
            text-decoration: none;
            margin-top: 10px;
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
            color: #a8df65;
            margin-left: 10px;
            font-size: 14px; 
        }

        @media (max-width: 767px) {
            .container {
                grid-template-columns: 100%;
                grid-template-areas: "register";
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
            <div class="pill-1"></div>
            <div class="pill-2"></div>
            <div class="pill-3"></div>
            <div class="pill-4"></div>
        </div>
        <div class="register">
            <h2>Registration</h2>
            <form method="post" action="">
                <div class="text-input">
                    <i>&#128100;</i>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="text-input">
                    <i>&#128273;</i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="text-input">
                    <i>&#128231;</i>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="text-input">
                    <i>&#128197;</i>
                    <input type="text" id="college_year" name="college_year" placeholder="College Year" required>
                </div>
                <div class="text-input">
                    <i>&#127891;</i>
                    <input type="text" id="course" name="course" placeholder="Course" required>
                </div>
                <input type="submit" name="register" value="Register" class="register-btn">
            </form>
            <?php
            if (isset($error)) {
                echo "<p class='error'>$error</p>";
            }
            ?>
            <p>Already have an account? <br><a href="loginpage.php">Login</a></p>
        </div>
    </div>
</body>

</html>
