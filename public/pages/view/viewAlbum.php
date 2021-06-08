<?php
include_once "../universal/universal.php";
include_once "../universal/icon.php";
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

                            $halaman = 10;
                            $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                            $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
                            $sql = "select * from group_album WHERE title_album LIKE '%$cari%' limit $mulai,$halaman";
                            $query = "select * from group_album";
                            $total = $db->query($query);
                            $pages = ceil($total->num_rows / $halaman);

                            $res = $db->query($sql);
                            if ($res) { //query success
                                $row = $res->fetch_row();
                                do {
                                    list($id_album, $id_group, $title_album, $jenis, $tgl_rilis) = $row;
                                    echo "<tr><td>$id_album</td>";
                                    echo "<td>$id_group</td>";
                                    echo "<td>$title_album</td>";
                                    echo "<td>$jenis</td>";
                                    echo "<td>$tgl_rilis</td>";
                                    echo "<td colspan='2'><button name='delete'><a href='?halaman=$page&cari=$cari&delete=$id_album'>Delete</a></button>";
                                    echo "<button name='delete'><a href='?halaman=$page&cari=$cari&edit=$id_album'>Edit</a></button></td></tr>";
                                } while ($row = $res->fetch_row());
                            }
                        } else {


                            if (isset($_GET["add-data"])) {
                                $id_group = isset($_POST["id-group"]) ? (int) $_POST["id-group"] : null;
                                $title_album = isset($_POST["title-album"]) ? (string) $_POST["title-album"] : null;
                                $jenis = isset($_POST["jenis"]) ? (string) $_POST["jenis"] : null;
                                $tgl_rilis = isset($_POST["tgl-rilis"]) ? (string) $_POST["tgl-rilis"] : null;

                                $sql = "SELECT MAX( id_album ) as maxi FROM group_album";
                                $res = $db->query($sql);

                                $row = $res->fetch_row();
                                list($maxi) = $row;

                                $sql = "ALTER TABLE group_album AUTO_INCREMENT = $maxi";
                                $db->query($sql);

                                $sql = "INSERT INTO group_album(id_group, title_album, jenis, tgl_rilis) VALUES ('$id_group','$title_album', '$jenis', STR_TO_DATE('$tgl_rilis', '%Y-%m-%d'))";
                                $db->query($sql);
                                $halaman = 10;
                                $query = "select * from group_album";
                                $total = $db->query($query);
                                $pages = ceil($total->num_rows / $halaman);
                                header("Location: viewAlbum.php?halaman=$pages");
                            } else if (isset($_GET["add"])) {
                                echo "<form method='post' action='?add-data'>";
                                echo "<tr><td>Auto</td>";
                                echo "<td><input type='text' name='id-group'></input></td>";
                                echo "<td><input type='text' name='title-album'></input></td>";
                                echo "<td>
                                    <select name='jenis'>
                                    <option value='Mini Album'>Mini Album</option>
                                    <option value='Full Album'>Full Album</option>
                                    </select>
                                    </input></td>"; ?>
                                <td><input type="text" name="tgl-rilis" value="yyyy-mm-dd" onFocus="this.value=''" /></td>
                    <?php echo "<td colspan='2'><button type='submit' name='add-btn'>Add Data</button>";
                                echo "<button type='submit' name='cancel-btn'><a href='?'>Cancel</a></button></td></tr>";
                                echo "</form>";
                            } else {

                                if (isset($_GET["delete"]) and isset($_GET["halaman"])) {
                                    $id = isset($_GET["delete"]) ? (int) $_GET["delete"] : 0;
                                    $query = "DELETE FROM group_album WHERE id_album = $id";
                                    $db->query($query);
                                    $halaman = 10;
                                    $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                                    $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
                                    $sql = "select * from group_album limit $mulai,$halaman";
                                    $res = $db->query($sql);
                                    $page = $res->num_rows < 1 ? $page = $page - 1 : $page;
                                    header("Location: viewAlbum.php?halaman=$page");
                                }
                                if (isset($_GET["save"])) {
                                    $idSave = isset($_GET["save"]) ? (int) $_GET["save"] : null;

                                    $sql = "select * from group_album where id_album = '$idSave'";
                                    $res = $db->query($sql);

                                    $row = $res->fetch_row();
                                    list($id_album, $id_group, $title_album, $jenis, $tgl_rilis) = $row;
                                    $titleSave = isset($_POST["title-album"]) ? (string) $_POST["title-album"] : $title_album;
                                    $jenisSave = isset($_POST["jenis"]) ? (string) $_POST["jenis"] : $jenis;
                                    $tglSave = isset($_POST['tgl-rilis']) ? (string) $_POST["tgl-rilis"] : $tgl_rilis;

                                    $query = "UPDATE group_album SET title_album = '$titleSave', jenis = '$jenisSave', tgl_rilis = '$tglSave' WHERE id_album = '$idSave'";
                                    $save = $db->query($query);
                                    $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                                    header("Location: viewAlbum.php?halaman=$page");
                                } else {
                                    if (isset($_GET["edit"]) and isset($_GET["halaman"])) {
                                        $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                                        $id = (int) $_GET["edit"];
                                        $sql = "select * from group_album where id_album = '$id'";
                                        $res = $db->query($sql);

                                        $row = $res->fetch_row();
                                        list($id_album, $id_group, $title_album, $jenis, $tgl_rilis) = $row;
                                        echo "<form method='post' action='?halaman=$page&save=$id'>";
                                        echo "<tr><td>$id_album</td>";
                                        echo "<td>$id_group</td>";
                                        echo "<td><input type='text' name='title-album' value='$title_album'></input></td>";
                                        echo "<td><select name='jenis'>";
                                        echo "<option value='$jenis'>$jenis</option>";
                                        echo "<option value='Mini Album'>Mini Album</option>";
                                        echo "<option value='Full Album'>Full Album</option></select></td>";
                                        echo "<td><input type='text'name='tgl-rilis' value='$tgl_rilis'></input></td>";
                                        echo "<td colspan='2'><button name='save' type='submit'>Save</button>";
                                        echo "<button name='cancel'><a href='?halaman=$page'>Cancel</a></button></td></tr>";
                                        echo "</form>";
                                    } else {
                                        $halaman = 10;
                                        $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                                        $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
                                        $sql = "select * from group_album limit $mulai,$halaman";
                                        $query = "select * from group_album";
                                        $total = $db->query($query);
                                        $pages = ceil($total->num_rows / $halaman);

                                        $res = $db->query($sql);
                                        if ($res) { //query success
                                            $row = $res->fetch_row();
                                            do {
                                                list($id_album, $id_group, $title_album, $jenis, $tgl_rilis) = $row;
                                                echo "<tr><td>$id_album</td>";
                                                echo "<td>$id_group</td>";
                                                echo "<td>$title_album</td>";
                                                echo "<td>$jenis</td>";
                                                echo "<td>$tgl_rilis</td>";
                                                echo "<td colspan='2'><button name='delete'><a href='?halaman=$page&delete=$id_album'>Delete</a></button>";
                                                echo "<button name='delete'><a href='?halaman=$page&edit=$id_album'>Edit</a></button></td></tr>";
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