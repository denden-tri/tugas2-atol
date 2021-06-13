<?php
include_once "../universal/universal.php";
include_once "../universal/icon.php";
include_once "../universal/fungsi.php";
include_once "../function/groupFunction.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php icon() ?>
    <link rel="stylesheet" href="style.css">
    <title>Tugas 2 ATOL</title>
</head>

<body>
    <div class="notFooter">
        <header>
            <?php navBar() ?>
        </header>
        <main class="container">
            <?php
            $halaman = 10;
            $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
            $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
            $pages = getTotalHalaman("group") ?>
            <div class="inner-container">
                <?php include_once "../../../component/mysqlconnect.php";
                $db = dbConnect();
                if ($db->connect_errno == 0) { ?>
                    <div class="search-container">
                        <form action="?search">
                            <input type="text" class="input-cari" name="cari" id="cari" placeholder="Cari nama group..." onFocus="this.value=''">
                            <button type="submit" class="btn-cari">Cari</button>
                        </form>
                    </div>
                    <table>
                        <tr>
                            <th>Id Group</th>
                            <th>Nama Group</th>
                            <th>Tanggal Debut</th>
                            <th>Nama Agensi</th>
                            <th>Aksi</th>
                        </tr>
                        <?php
                        if (isset($_GET["cari"])) {
                            $cari = isset($_GET["cari"]) ? (string) $_GET['cari'] : "";
                            $res = cariDataGroup($cari, $mulai);

                            if ($res) { //query success
                                $row = $res->fetch_row();
                                $pageCari = ceil($res->num_rows / $halaman);
                                $pages = ($pageCari >= 1) ? $pageCari : $pages;

                                do {
                                    tableGroup($row, $page);
                                } while ($row = $res->fetch_row());
                            }
                        } else {

                            if (isset($_GET["add-data"])) {
                                $nama_group = isset($_POST["nama-group"]) ? (string) $_POST["nama-group"] : "";
                                $tgl_debut = isset($_POST["tgl-debut"]) ? (string) $_POST["tgl-debut"] : "";
                                $agensi = isset($_POST["agensi"]) ? (string) $_POST["agensi"] : "";

                                addDataGroup($nama_group, $tgl_debut, $agensi);
                                header("Location: viewGroup.php?halaman=$pages");
                            } else if (isset($_GET["add"])) {
                        ?>
                                <form method="POST" action="?add-data" id="add-data-form">
                                    <tr>
                                        <td>Auto</td>
                                        <td><input type="text" name="nama-group" id="nama-group" required></input></td>
                                        <td><input type="date" name="tgl-debut" placeholder="dd/mm/yyyy" id="tgl-debut" required /></td>
                                        <td><input type='text' name='agensi' id="agensi" required></input></td>
                                        <td colspan="2"><button type="submit" name="add-btn" id="add-btn">Add Data</button>
                                            <button type="submit" name="cancel-btn"><a href="?">Cancel</a></button>
                                        </td>
                                    </tr>
                                </form>
                    <?php             } else {

                                if (isset($_GET["delete"]) and isset($_GET["halaman"])) {
                                    $id = isset($_GET["delete"]) ? (string) $_GET["delete"] : 0;

                                    deleteDataGroup($id);

                                    $res = getDataGroup($mulai);

                                    header("Location: viewGroup.php?halaman=$page");
                                }
                                if (isset($_GET["save"])) {
                                    $idSave = isset($_GET["save"]) ? (string) $_GET["save"] : null;
                                    $namaSave = isset($_POST["nama-group"]) ? (string) $_POST["nama-group"] : "";
                                    $tglSave = isset($_POST["tgl-debut"]) ? (string) $_POST["tgl-debut"] : "";
                                    $agensiSave = isset($_POST["agensi"]) ? (string) $_POST["agensi"] : "";

                                    saveEditGroup($idSave, $namaSave, $tglSave, $agensiSave);
                                    header("Location: viewGroup.php?halaman=$page");
                                } else {
                                    if (isset($_GET["edit"]) and isset($_GET["halaman"])) {
                                        $id = (string) $_GET["edit"];
                                        tableEditGroup($id, $page);
                                    } else {
                                        $res = getDataGroup($mulai);
                                        if ($res) { //query success
                                            $row = $res->fetch_row();
                                            do {
                                                tableGroup($row, $page);
                                            } while ($row = $res->fetch_row());
                                        } else {
                                            echo "Gagal Ekseksi SQL : $db->error <br>";
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        echo "Gagal koneksi" . (DEVELOPMENT ? " : " . $db->connect_error : "") . "<br>";
                    }
                    ?>
                    </table>
                    <?php if (!isset($_GET["edit"]) and !isset($_GET["add"])) { ?>
                        <?php if (!isset($_GET["add"]) and !isset($_GET["add-data"])) { ?>
                            <div class="add">
                                <button type="submit" name="add" class="btn-add"><a href="?add">Add Data</a></button>
                            </div>
                        <?php } ?>
                    <?php } ?>
            </div>
        </main>
    </div>
    </div>
    <footer>
        <div>
            <?php if (!isset($_GET["edit"]) and !isset($_GET["add"])) { ?>
                <?php
                $page_aktif = "page-aktif";
                for ($i = 1; $i <= $pages; $i++) { ?>
                    <?php
                    $page_aktif = $i == 1 ? "page-aktif" : "page";
                    if (isset($_GET["halaman"])) {

                        if ((int) $_GET["halaman"] == $i) {
                            $page_aktif = "page-aktif"; ?>
                        <?php } else if ((int) $_GET["halaman"] != $i) {
                            $page_aktif = "page"; ?>
                        <?php } else { ?>
                    <?php }
                    } ?>
                    <div class="<?php echo $page_aktif ?>"><a href="?halaman=<?php echo $i; ?>"><?php echo $i; ?></a></div>
                <?php } ?>
            <?php } ?>
        </div>
    </footer>
</body>

</html>