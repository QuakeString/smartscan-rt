<?php  
    define('DB_SERVER', '127.0.0.1');
    define('DB_USERNAME', 'sunoil');
    define('DB_PASSWORD', 'sunoil_inv');
    define('DB_DATABASE', 'sunoil_log');
    /*  
    define('DB_SERVER', 'panel.invenia.in');
    define('DB_USERNAME', 'mozammel_sunoil');
    define('DB_PASSWORD', 'Admin@123');
    define('DB_DATABASE', 'mozammel_sunoil');
    */
    $conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>