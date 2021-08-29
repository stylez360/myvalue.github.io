<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
$ftp_server="ftp.exchange-data.com";
$ftp_user_name="Marketocracy";
$ftp_user_pass="w72tQ7Rr";
$file = "";//tobe uploaded
$remote_file = "";

// set up basic connection
$conn_id = ftp_connect($ftp_server);

// login with username and password
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// upload a file
/*if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
    echo "successfully uploaded $file\n";
    exit;
} else {
    echo "There was a problem while uploading $file\n";
    exit;
    }*/

//directory listing
/*$contents = ftp_nlist($conn_id, "/689/");
print_r($contents);*/

//689

$file = date('Ymd').'.689';
print "<br><br><br><br>";
print $file;
print "<br><br><br><br>";


$local_file = '689/'.date('Ymd').'.689';

// open some file to write to
$handle = fopen($local_file, 'w');

// try to change the directory to somedir
if (ftp_chdir($conn_id, "689")) {
    echo "Current directory is now: " . ftp_pwd($conn_id) . "\n";
} else {
    echo "Couldn't change directory\n";
}

//directory listing
$contents = ftp_nlist($conn_id, ".");
print_r($contents);


// try to download $remote_file and save it to $handle
if (ftp_fget($conn_id, $handle, $file, FTP_BINARY, 0)) {
 echo "successfully written to $file\n";
} else {
 echo "There was a problem while downloading $file to $file\n";
}

// close the connection
ftp_close($conn_id);
fclose($handle);
?>