<?php
require_once('view-header.php');
require_once('function.php');
require_once './db.php';
$ip = get_client_ip();

$sql = "SELECT
            *
        FROM
            visitors
        WHERE
            ip_address = '$ip';";
$q = $db->query($sql);
if ($q->num_rows > 0) {
    $sql = "
    UPDATE 
        visitors
    SET count = count + 1
    WHERE
    ip_address = '$ip'";
    $q = $db->query($sql);
} else {
    $sql = "INSERT INTO visitors (ip_address) VALUES('$ip')";
    $q = $db->query($sql);
}
?>
<div class="card">
    <div class="card-body">
        <?php
        $slug = '...';
        $finalLink = '';
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] != '') {
                echo '<pre style="padding: 10px;">';
                echo $_GET['msg'];
                echo '</pre>';
            }
        }
        if (isset($_GET['slug'])) {
            $slug = $_GET['slug'];
            $sql = "
                    SELECT id FROM requests WHERE child_token = '$slug';
                ";

            $query = $db->query($sql);
            if ($query->num_rows > 0) {

                $sql = "
                        UPDATE requests SET count = count + 1 WHERE child_token = '$slug';
                    ";
            } else {
                $sql = "
                        INSERT INTO requests (child_token) VALUES ('$slug');
                    ";
            }
            $db->query($sql);
            $sql = "
            SELECT
                `child_list`.`id` AS `cid`,
                `child_list`.`token`,
                `child_list`.`parent_file_id` AS `pid`,
                `child_list`.`new_filename` AS `filename`,
                `fileslist`.`base_url`,
                `fileslist`.`finalLink`,
                `fileslist`.`id` as pid,
                `fileslist`.`complete`
            FROM
                child_list,
                fileslist
            WHERE
                child_list.token = '$slug' AND child_list.parent_file_id = fileslist.id;   
                    ";
            $query = $db->query($sql);
            if ($query->num_rows > 0) {
                $data = $query->fetch_assoc();
                $finalLink = $data['finalLink'];
                $parentID = $data['pid'];
            } else {
                echo "<h2 class='card-title'>Link with this id ($slug) is not found!</h2>";
                exit();
            }
        } else {
            echo "<h2 class='card-title'>We can't find page with this url!</h2>";
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
        $ku = isUrl($finalLink)  ? $finalLink : 0;
        ?>
    </div>
</div>


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
        max-width: 900px;
    }

    .view-wrapper {
        max-width: 900px;
        margin: auto;
    }
</style>

<body>
    <br />

    <div class="center view-wrapper">
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
            <button onclick="handleClientReq({
                    parent: <?php echo $data['pid']; ?>,
                    token: '<?php echo $data['token']; ?>',
                    redirect: '<?php echo $ku ?>'
                })" class="btn btn-danger btn-lg w-100" style="color: white">
                <?php echo isUrl($finalLink)  ? $data['filename'] : $finalLink; ?>
            </button>
        <?php
        } else {
        ?>
            <button id="donwbtn" onclick="handleRequest(<?php echo $parentID ?>, '<?php echo $slug; ?>', {
                    parent: <?php echo $data['pid']; ?>,
                    token: '<?php echo $data['token']; ?>',
                    redirect: '<?php echo $ku ?>'
                })" class="btn btn-danger btn-lg w-100">
                <i class="fas fa-chess-king"></i>
                <?php echo $data['filename']; ?>
            </button>
        <?php
        }
        ?>
        <span id="message" style="display:block;padding: 10px;font-weight:bold;text-align:center;color:dodgerblue;"></span>
        <br>
        <hr>
    </div>

</body>

<script>
    const handleClientReq = (data) => {
        axios.get(`./op.php?client-req=1&id=${data.id}&parent=${data.parent}&token=${data.token}`).then(e => {
            window.location.href = data.redirect == "0" ? '#' : data.redirect
        }).catch(e => {
            window.location.href = data.redirect == "0" ? '#' : data.redirect
        })
    }
    const handleRequest = async (pid, token = "", data) => {
        handleLoading(true)
        await axios.get('./op.php?set-req=1&pid=' + pid + '&token=' + token).then(e => {
            console.log(e.data)
            document.querySelector('#message').innerHTML = e.data
            handleClientReq(data)
        }).catch(e => {
            console.error(e.message)
            handleClientReq(data)
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