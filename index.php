<?php
session_start();
require_once 'config/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container">
        <div class="box-con1">
            <div class="box-con2">
                <div class="items-1"></div>
                <form action="sign_in_db.php" method="POST">
                    <div class="input-con">
                        <?php if (isset($_SESSION['error'])) { ?>
                            <div class="alert-danger" role="alert">
                                <?php
                                echo $_SESSION['error'];
                                unset($_SESSION['error']);
                                ?>
                            </div>
                        <?php } ?>
                        <?php if (isset($_SESSION['success'])) { ?>
                            <div class="alert-success" role="alert">
                                <?php
                                echo $_SESSION['success'];
                                unset($_SESSION['success']);
                                ?>
                            </div>
                        <?php } ?>
                        <h1 class="text-login">Log In</h1>
                        <div class="input-1">
                            <input type="text" name="username" placeholder="Username" />
                        </div>
                        <div class="input-1">
                            <input type="password" name="password" placeholder="Password" />
                        </div>
                        <button type="submit" name="signin" class="btn-login">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>