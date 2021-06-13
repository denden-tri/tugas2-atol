<?php
include_once "../universal/universal.php";
include_once "../universal/icon.php";
include_once "../universal/fungsi.php";
include_once "../function/albumFunction.php";
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
            $pages = getTotalHalaman("album") ?>
            <div class="inner-container">
                <?php include_once "../../../component/mysqlconnect.php";
                $db = dbConnect();
                if ($db->connect_errno == 0) { ?>
                    <div class="search-container">
                        <form action="?search">
                            <input type="text" class="input-cari" name="cari" id="cari" value="Cari judul album..." onFocus="this.value=''">
                            <button type="submit" class="btn-cari">Cari</button>
                        </form>
                    </div>

                    <table>
                        <tr>
                            <th>Id Album</th>
                            <th>Id Group</th>
                            <th>Nama Album</th>
                            <th>Jenis Album</th>
                            <th>Tanggal Rilis</th>
                            <th>Aksi</th>
                        </tr>
                    <?php
                    if (isset($_GET["cari"])) {
                        $cari = isset($_GET["cari"]) ? (string) $_GET['cari'] : "";

                        $res = cariDataAlbum($cari, $mulai);

                        $pageCari = ceil($res->num_rows / $halaman);
                        $pages = ($pageCari >= 1) ? $pageCari : $pages;
                        if ($res) { //query success
                            $row = $res->fetch_row();
                            do {
                                tableAlbum($row, $page);
                            } while ($row = $res->fetch_row());
                        }
                    } else {


                        if (isset($_GET["add-data"])) {
                            $id_group = isset($_POST["id-group"]) ? (string) $_POST["id-group"] : "";
                            $title_album = isset($_POST["title-album"]) ? (string) $_POST["title-album"] : "";
                            $jenis = isset($_POST["jenis"]) ? (string) $_POST["jenis"] : "";
                            $tgl_rilis = isset($_POST["tgl-rilis"]) ? (string) $_POST["tgl-rilis"] : "";

                            addDataAlbum($id_group, $title_album, $jenis, $tgl_rilis);
                            header("Location: viewAlbum.php?halaman=$pages");
                        } else if (isset($_GET["add"])) {
                            formAddAlbum();
                        } else {

                            if (isset($_GET["delete"]) and isset($_GET["halaman"])) {
                                $id = isset($_GET["delete"]) ? (string) $_GET["delete"] : 0;
                                deleteDataAlbum($id);
                                header("Location: viewAlbum.php?halaman=$page");
                            }
                            if (isset($_GET["save"])) {
                                $idSave = isset($_GET["save"]) ? (string) $_GET["save"] : null;
                                $idGroupSave = isset($_POST["id-group"]) ? (string) $_POST["id-group"] : "";
                                $titleSave = isset($_POST["title-album"]) ? (string) $_POST["title-album"] : "";
                                $jenisSave = isset($_POST["jenis"]) ? (string) $_POST["jenis"] : "";
                                $tglSave = isset($_POST["tgl-rilis"]) ? (string) $_POST["tgl-rilis"] : "";

                                saveEditAlbum($idSave, $idGroupSave, $titleSave, $jenisSave, $tglSave);
                                header("Location: viewAlbum.php?halaman=$page");
                            } else {
                                if (isset($_GET["edit"]) and isset($_GET["halaman"])) {
                                    $id = (string) $_GET["edit"];

                                    tableEditAlbum($id, $page);
                                } else {
                                    $res = getDataAlbum($mulai);
                                    if ($res) { //query success
                                        $row = $res->fetch_row();
                                        do {
                                            tableAlbum($row, $page);
                                        } while ($row = $res->fetch_row());
                                    } else {
                                        echo "Gagal Ekseksi SQL" . (DEVELOPMENT ? " : " . $db->error : "") . "<br>";
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