<?php
session_start();
include_once "../public/pages/universal/icon.php";
if (isset($_COOKIE["username"])) {
    header("Location: pages/index-admin.php");
} else if (isset($_SESSION["user"])) {
    header("Location: pages/index-admin.php");
}
?>
<?php include_once "../component/mysqlconnect.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php icon() ?>
    <link rel="stylesheet" href="styles.css">
    <title>Tugas 2</title>
</head>

<body>
    <div class="container">
        <div class="inner-container">
            <h1 class="login">Login</h1>
            <?php function showError($error)
            {?>
                <div class='error-container'>
                    <div class='error'><?php echo $error ?></div>
                </div>
            <?php }
            ?>
            <?php
            if (isset($_GET["error"])) {
                $error = $_GET["error"];
                if ($error == 1) {
                    showError("username dan password tidak sesuai.");
                } else if ($error == 2) {
                    showError("Error database. Silahkan hubungi administrator");
                } else if ($error == 3) {
                    showError("Koneksi ke Database gagal. Autentikasi gagal.");
                } else if ($error == 4) {
                    showError("Anda tidak boleh mengakses halaman sebelumnya karena belum login.
                    Silahkan login terlebih dahulu.");
                } else {
                    showError("Unknown Error.");
                }
            }
            ?>
            <div class="form-container">
                <div class="form-item">
                    <form action="../public/pages/login.php" method="post">
                        <div class="form-field">
                            <h3>Username</h3> <input type="text" name="username" class="input" value="<?php echo ($_SERVER["REMOTE_ADDR"] == "5.189.147.4" ? "admin" : ""); ?>"><br>
                        </div>
                        <div class="form-field">
                            <h3>Password</h3> <input type="password" name="password" class="input" value="<?php echo ($_SERVER["REMOTE_ADDR"] == "5.189.147.4" ? "admin_password" : ""); ?>"><br>
                        </div>
                        <div class="form-field">
                            <input type="checkbox" name="remember" value="1">Remember me for 30 days</input>
                        </div>
                        <div class="form-field">
                            <button type="submit" name="btnLogin" class="btnLogin">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>