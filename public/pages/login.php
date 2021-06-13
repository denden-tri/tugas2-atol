<?php include_once "../../component/mysqlconnect.php"?>
<?php
$db = dbConnect();
if ($db->connect_errno == 0) {
    if (isset($_POST["remember"])) {
        session_set_cookie_params(time() + 60 * 60 * 24 * 30, "/");
        $user_cookie = "username";
        $pass_cookie = "password";
        $pass = sha1((string) $_POST["password"]);
        $user = (string) $_POST["username"];
        setcookie($user_cookie, $user, time() + 60 * 60 * 24 * 30, "/");
        setcookie($pass_cookie, $pass, time() + 60 * 60 * 24 * 30, "/");
    }
    if (isset($_POST["btnLogin"]) or isset($_COOKIE["username"])) {
        $username = isset($_COOKIE["username"]) ? (string) $_COOKIE["username"] : $db->escape_string($_POST["username"]);
        $password = isset($_COOKIE["password"]) ? (string) $_COOKIE["password"] : sha1($db->escape_string($_POST["password"]));
        $sql = "SELECT user_name, user_password FROM user_data WHERE user_name = '$username' AND user_password = '$password'";

        $res = $db->query($sql);
        $row = $res->fetch_row();
        if ($res) {
            if ($res->num_rows == 1) {
                list($user_name) = $row;
                session_start();
                $_SESSION["user"] = $user_name;

                $_SESSION["passp"] = openssl_random_pseudo_bytes(16);
                $_SESSION["iv"] = openssl_random_pseudo_bytes(16);
                header("Location: index-admin.php");
            } else {
                header("Location: ../index.php?error=1");
            }
        } else {
            header("Location: ../index.php?error=2");
        }
    }
} else {
    header("Location: ../index.php?error=3");
}
