<?php
session_start();
if (isset($_POST['login'])) {

    $password = '123123';
    $username = 'admin';
    if ($_POST['username'] == $username && $_POST['password'] == $password) {
        $_SESSION['login'] = 1;
        header('location: ./insert.php');
    } else {
        echo '<pre>Try again username or email is incorrect</pre>';
    }
}


?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
<style>
    .container {
        display: flex;
        height: 100vh;
        width: 100%;
        justify-content: center;
        align-items: center;
    }

    .wrap {
        padding: 30px;
        background: #0000001a;
        border-radius: 6px;
        text-align: center
    }

    select.form-control {
        margin-left: 5px;
    }
</style>
<div class="container">

    <div class="wrap">

        <form method="POST" action="./login.php">
            <div class="form-group">
                <label for="exampleInputEmail1">Enter admin username </label>
                <input type="text" class="form-control" name="username" placeholder="Enter username">

            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Enter Admin password</label>
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <input type="hidden" name="login" value="1">
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>