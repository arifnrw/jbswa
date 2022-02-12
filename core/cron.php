<?php

include('config.php');
$conn = mysqli_connect($db_host,$db_user,$db_pass,'jbssms');

//isi detail token WA
$base_url   = "https://warayang.com/";
$token      = "xxx"; //diperoleh di device jbswa.my.id

$sql = "SELECT * FROM outbox LIMIT " . $cronLimit;
$res = $conn->query($sql);

if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $hp  = $row['DestinationNumber'];
        $msg = $row['Text'];
        $id  = $row['ID'];

        $response = sendwa($hp, $msg, $base_url, $token);
        $data     = json_decode($response,TRUE);

        // 1: sent, 0: pending/error
        $status   = ($data['status'] == 'sent') ? 1 : 0;

        // masukkan ke outboxhistory
        $resHistory = $conn->query("INSERT INTO outboxhistory SET Text='$msg', DesstinationNumber='$hp', status='$status'");

        // hapus outbox
        $resDel     = $conn->query("DELETE FROM outbox WHERE ID='$id'");


    }
}

mysqli_close($conn);
