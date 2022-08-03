<?php

function pre($str)
{
    echo '<pre>';
    print_r($str);
    echo '</pre>';
}
$datestamp = date('c');
function getPastMonth($datestamp)
{

    $pastMonth = $datestamp;
    $pastMonth = explode('-', $pastMonth)[1];
    $pastMonth = intval($pastMonth) - 1;
    $u = '';
    $i = 0;
    foreach (explode('-', $datestamp) as $k) {
        if ($i == 1) {
            $u .= '-' . $pastMonth;
        } else {
            if ($i == 0) $u .=  $k;
            else $u .= '-' . $k;
        }
        $i++;
    }
    return $u;
}
function getAnually($datestamp)
{
    $year = $datestamp;
    $year = explode('-', $year)[0];
    $year = intval($year) - 1;
    $u = '';
    $i = 0;
    foreach (explode('-', $datestamp) as $k) {
        if ($i == 0) {
            $u .= $year;
        } else {
            $u .= '-' . $k;
        }
        $i++;
    }
    return $u;
}
$nowDate = $datestamp;
$pastMonth = getPastMonth($datestamp);
$pastYear = getAnually($datestamp);
//get monthly visitors
$sql = "SELECT id FROM visitors
WHERE createdAt BETWEEN '$pastMonth' AND '$nowDate';";
$k = $db->query($sql);
$monthly = $k->num_rows;
//get monthly annually
$sql = "SELECT id FROM visitors
WHERE createdAt BETWEEN '$pastYear' AND '$nowDate';";
$k = $db->query($sql);
$annually = $k->num_rows;
//get complete tasks
$sql = "SELECT referToBot, complete FROM fileslist
WHERE 1;";
$k = $db->query($sql);
$fileslistCount = $k->num_rows;
$underProcess = 0;
$complete = 0;
$notComplete = 0;
$totalFiles = 0;
$totalChilds = 0;
if ($k->num_rows > 0) {
    while ($row = $k->fetch_assoc()) {
        if ($row['referToBot'] == 1) $underProcess++;
        if ($row['complete'] == 1)  $complete++;
        else $notComplete++;
        $totalFiles++;
    }
}
$percentage = ceil(($complete / $totalFiles) * 100);

$sql  = "SELECT id FROM child_list WHERE 1 ORDER BY id DESC";
$k = $db->query($sql);
$totalChilds = $k->num_rows;
