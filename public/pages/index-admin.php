<?php include_once "../../component/mysqlconnect.php";?>
<?php include_once "universal/universal.php";?>
<?php include_once "universal/icon.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php icon() ?>
    <link rel="stylesheet" href="style.css">
    <title>Tugas 2</title>
</head>
<body>
    <header>
    <?php navBar()?>
    </header>
    <main class="container">
        <div class="inner-container">
    <div>Selamat Datang di halaman admin</div>
    <div><?php echo $_SESSION["user"]; ?></div>
    </div>
    </main>
</body>
</html>
