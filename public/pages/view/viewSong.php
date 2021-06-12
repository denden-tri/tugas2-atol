<?php
include_once "../universal/universal.php";
include_once "../universal/icon.php";
include_once "../universal/fungsi.php";
include_once "../function/songFunction.php";
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
            $pages = getTotalHalaman("song") ?>
            <div class="inner-container">
                <?php include_once "../../../component/mysqlconnect.php";
                $db = dbConnect();
                if ($db->connect_errno == 0) { ?>
                    <div class="search-container">
                        <form action="?search">
                            <input type="text" class="input-cari" name="cari" id="cari" value="Cari nama member..." onFocus="this.value=''">
                            <button type="submit" class="btn-cari">Cari</button>
                        </form>
                    </div>
                    <table>
                        <tr>
                            <th>Id Song</th>
                            <th>Id Group</th>
                            <th>Id Album</th>
                            <th>Judul Lagu</th>
                            <th>Main Track</th>
                            <th>Aksi</th>
                        </tr>
                    <?php
                    if (isset($_GET["cari"])) {
                        $cari = isset($_GET["cari"]) ? (string) $_GET['cari'] : "";

                        $pageCari = ceil($res->num_rows / $halaman);
                        $pages = ($pageCari >= 1) ? $pageCari : $pages;
                        $res = cariDataSong($cari, $mulai);
                        if ($res) { //query success
                            $row = $res->fetch_row();
                            do {
                                tableSong($row, $page);
                            } while ($row = $res->fetch_row());
                        }
                    } else {
                        if (isset($_GET["add-data"])) {
                            $id_group = isset($_POST["id-group"]) ? (string) $_POST["id-group"] : null;
                            $id_album = isset($_POST["id-album"]) ? (int) $_POST["id-album"] : null;
                            $title_song = isset($_POST["title-song"]) ? (string) $_POST["title-song"] : null;
                            $main_track = isset($_POST["main-track"]) ? (int) $_POST["main-track"] : null;

                            addDataSong($id_group, $id_album, $title_song, $main_track);

                            header("Location: viewSong.php?halaman=$pages");
                        } else if (isset($_GET["add"])) {
                            formAddSong();
                        } else {

                            if (isset($_GET["delete"]) and (int) $_GET["halaman"]) {
                                $id = isset($_GET["delete"]) ? (string) $_GET["delete"] : 0;
                                deleteDataSong($id);
                                header("Location: viewSong.php?halaman=$page");
                            }
                            if (isset($_GET["save"])) {
                                $idSave = isset($_GET["save"]) ? (string) $_GET["save"] : null;
                                $id_groupSave = isset($_POST["id-song"]) ? (int) $_POST["id-group"] : "";
                                $id_albumSave = isset($_POST["id-album"]) ? (int) $_POST["id-album"] : "";
                                $judulSave = isset($_POST["title-song"]) ? (string) $_POST["title-song"] : "";
                                $trackSave = isset($_POST["main-track"]) ? (int) $_POST["main-track"] : "";

                                saveEditSong($idSave, $id_groupSave, $id_albumSave, $judulSave, $trackSave);
                                $page = (int) $_GET["halaman"] ? (int) $_GET["halaman"] : 1;
                                header("Location: viewSong.php?halaman=$page");
                            } else {
                                if (isset($_GET["edit"]) and (int) $_GET["halaman"]) {
                                    $id = (string) $_GET["edit"];

                                    tableEditSong($id, $page);
                                } else {
                                    $res = getDataSong($mulai);
                                    if ($res) { //query success
                                        $row = $res->fetch_row();
                                        do {
                                            tableSong($row, $page);
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
    </main>
</body>

</html>