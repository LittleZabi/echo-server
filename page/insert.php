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

            <form action="./op.php" method="GET" class="form" style="min-width: 400px;">
                <h3>Enter File Details</h3>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="filelink" style="text-align: left;display:block;font-size: 14px">Enter target file link</label>
                    <input type="text" id="filelink" name="file-link" class="form-control" placeholder="Enter target url...">
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="filelink" style="text-align: left;display:block;font-size: 14px">Enter file name</label>
                    <input type="text" id="filelink" name="file-name" class="form-control" placeholder="Enter file name...">
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="filelink" style="text-align: left;display:block;font-size: 14px">Enter cache name (default is Feature)</label>
                    <input type="text" id="filelink" name="cache-type" value="Featured" class="form-control" placeholder="Enter cache name...">
                </div>
                <br>
                <button type="submit" name="submit-link" class="btn btn-primary mb-2 pl-5 pr-5" style="width: 200px;">Save</button>
            </form>
        </div>
    </div>
</body>

</html>