<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
<div class="card">
    <div class="card-body">
        <?php
        require_once './db.php';
        $slug = '...';
        $finalLink = '';
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] != '') {
                echo '<pre style="padding: 10px;">';
                echo $_GET['msg'];
                echo '</pre>';
            }
        }
        if (isset($_GET['url'])) {
            $slug = $_GET['url'];
            $sql = "SELECT * FROM fileslist WHERE slug = '$slug'";
            $query = $db->query($sql);

            if ($query->num_rows > 0) {
                $data = $query->fetch_assoc();
                $sql = "SELECT * FROM `request_links` WHERE `file` = '$slug'";
                $k = $db->query($sql);
                if ($k->num_rows > 0) {
                    $l = $k->fetch_assoc();
                    $finalLink = $l['finalLink'];
                }
            } else {
                echo "<h2 class='card-title'>Link with this slug: $slug is not found!</h2>";
                exit();
            }
        } else {
            echo "<h2 class='card-title'>This page is empty with your shorts $slug</h2>";
            exit();
        }
        function isUrl($str)
        {
            $regex = "((https?|ftp)\:\/\/)?"; // SCHEME 
            $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass 
            $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP 
            $regex .= "(\:[0-9]{2,5})?"; // Port 
            $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path 
            $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query 
            $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor 

            if (preg_match("/^$regex$/i", $str)) // `i` flag for case-insensitive
            {
                return true;
            } else {
                return false;
            }
        }
        ?>
    </div>
</div>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <title>File</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.0.0-alpha.1/axios.min.js"></script>
</head>
<style>
    .container {
        position: relative;
        border: 1px solid rgb(0, 0, 0);

    }

    .flex {
        display: flex;
        align-items: center;
    }

    table th {
        line-break: anywhere;
        min-width: 100px;
    }
</style>

<body>
    <br />
    <div id="my_login_alert"></div>
    <div class="container">


        <div class="center">
            <br>
            <br>
            <?php
            if ($finalLink == '') {
            ?>
                <h5 id="iekxxl">Click on the button to send request</h5>
                <div class="flex">
                    <progress value="60" style="width: 100%" max="100" id="progressBar"></progress>
                    <h5 style="margin-left: 20px" id="demotext1">60</h5>
                </div>
            <?php } else {
            ?>
                <h5 id="iekxxl">This url is already finded if you are facing any issue then contact with admin</h5>
            <?php

            } ?>
            <br>
            <?php
            if ($finalLink != '') {
            ?>
                <a href="<?php echo isUrl($finalLink)  ? $finalLink : '#' ?>" onclick="" class="btn btn-danger btn-lg w-100" style="color: white">
                    <?php echo isUrl($finalLink)  ? $data['filename'] : $finalLink; ?>
                </a>
            <?php
            } else {
            ?>
                <button id="donwbtn" onclick="handleRequest('<?php echo $slug ?>')" class="btn btn-danger btn-lg w-100">
                    <i class="fas fa-chess-king"></i>
                    <?php echo $data['filename']; ?>
                </button>
            <?php
            }
            ?>
            <br>
            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Base url</th>
                        <th scope="col">Filename</th>
                        <th scope="col">Token</th>
                        <th scope="col">Status</th>
                        <th scope="col">Result</th>
                        <th scope="col">Cache</th>
                        <th scope="col">Created on</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row"><?php echo $data['id']; ?></th>
                        <td><?php echo $data['base_url']; ?></td>
                        <td><?php echo $data['filename']; ?></td>
                        <td><?php echo $data['slug']; ?></td>
                        <td><?php echo $data['complete'] ? 'Finded' : 'Not Tried Yet'; ?></td>
                        <td><?php echo $finalLink != '' ? $finalLink : 'Not Scrapped'; ?></td>
                        <td><?php echo $data['cache']; ?></td>
                        <td><?php echo date_format(date_create($data['createdAt']), 'd M Y') ?></td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
</body>

<script>
    const handleRequest = async (slug) => {
        console.log(slug)
        handleLoading(true)
        await axios.get('./op.php?set-req=1&slug=' + slug).then(e => {
            console.log(e.data)
        }).catch(e => {
            console.log(e.message)
        })
    }
    const handleLoading = status = () => {
        const time = 60
        const progress = document.querySelector('#progressBar')
        const urk = document.querySelector('#iekxxl')
        const progText = document.querySelector('#demotext1')
        if (status) {
            urk.innerHTML = "Please wait...";
            k = 1
            let u = 0
            c = setInterval(() => {
                let u = k / time * 100
                progress.value = u.toFixed(0)
                progText.innerHTML = k
                if (k >= 60) {
                    clearInterval(c)
                    location.reload();
                }
                k++
            }, 1000);
        }
    }
</script>

</html>