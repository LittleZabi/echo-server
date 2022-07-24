<?php
session_start();
if (isset($_SESSION['login'])) {
} else {
    header('location: ./login.php');
}
if (isset($_GET['msg'])) {
    if ($_GET['msg'] != '') {
        echo '<pre style="padding: 10px;">';
        echo $_GET['msg'];
        echo '</pre>';
    }
}

?>
<!DOCTYPE html>

<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
</head>
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

<body>
    <div class="container">

        <div class="wrap">

            <form action="./op.php" method="GET" class="form-inline">

                <div class="form-group mx-sm-3 mb-2">
                    <input type="text" id="filelink" name="file-link" class="form-control" placeholder="Enter target url...">
                    <select class="form-control" name="cache-type">
                        <option>Featured</option>
                        <option>Non-featured</option>
                    </select>
                </div>
                <button type="submit" name="submit-link" class="btn btn-primary mb-2">Save Link</button>
            </form>
        </div>
    </div>
</body>

</html>