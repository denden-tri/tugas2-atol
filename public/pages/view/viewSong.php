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
                    if(isset($_GET["cari"])){
                        $cari = isset($_GET["cari"]) ? (string) $_GET['cari'] : "";

                        $halaman = 10;
                        $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                        $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
                        $sql = "select * from group_song WHERE title_song LIKE '%$cari%' limit $mulai,$halaman";
                        $query = "select * from group_song";
                        $total = $db->query($query);
                        $pages = ceil($total->num_rows / $halaman);

                        $res = $db->query($sql);
                        if ($res) { //query success
                            $row = $res->fetch_row();
                            do {
                                list($id_song, $id_group, $id_album, $title_song, $main_track) = $row;
                                echo "<tr><td>$id_song</td>";
                                echo "<td>$id_group</td>";
                                echo "<td>$id_album</td>";
                                echo "<td>$title_song</td>";
                                echo "<td>$main_track</td>";
                                echo "<td colspan='2'><button name='delete'><a href='?halaman=$page&delete=$id_song'>Delete</a></button>";
                                echo "<button name='delete'><a href='?halaman=$page&edit=$id_song'>Edit</a></button></td></tr>";
                            } while ($row = $res->fetch_row());
                        }
                    }else{
                        if (isset($_GET["add-data"])) {
                            $id_group = isset($_POST["id-group"]) ? (int) $_POST["id-group"] : null;
                            $id_album = isset($_POST["id-album"]) ? (int) $_POST["id-album"] : null;
                            $title_song = isset($_POST["title-song"]) ? (string) $_POST["title-song"] : null;
                            $main_track = isset($_POST["main-track"]) ? (int) $_POST["main-track"] : null;

                            $mainBool = $main_track == 1 ? "TRUE" : "FALSE";

                            $sql = "SELECT MAX( id_song ) as maxi FROM group_song";
                            $res = $db->query($sql);

                            $row = $res->fetch_row();
                            list($maxi) = $row;

                            $sql = "ALTER TABLE group_song AUTO_INCREMENT = $maxi";
                            $db->query($sql);

                            $sql = "INSERT INTO group_song(id_group, id_album, title_song, main_track) VALUES ('$id_group', '$id_album', '$title_song', $main_track)";
                            $db->query($sql);
                            $halaman = 10;
                            $query = "select * from group_song";
                            $total = $db->query($query);
                            $pages = ceil($total->num_rows / $halaman);
                            header("Location: viewSong.php?halaman=$pages");
                        } else if (isset($_GET["add"])) {
                            echo "<form method='post' action='?add-data'>";
                            echo "<tr><td>Auto</td>";
                            echo "<td><input type='text' name='id-group'></input></td>";
                            echo "<td><input type='text' name='id-album'></input></td>";
                            echo "<td><input type='text' name='title-song'></input></td>";
                            echo "<td><select name='main-track'>
        <option value='1'>TRUE</option>
        <option value='0'>FALSE</option>
        </select></td>";
                            echo "<td colspan='2'><button type='submit' name='add-btn'>Add Data</button>";
                            echo "<button type='submit' name='cancel-btn'><a href='?'>Cancel</a></button></td></tr>";
                            echo "</form>";
                        } else {

                            if (isset($_GET["delete"]) and (int) $_GET["halaman"]) {
                                $id = isset($_GET["delete"]) ? (int) $_GET["delete"] : 0;
                                $query = "DELETE FROM group_song WHERE id_song = $id";
                                $db->query($query);
                                $halaman = 10;
                                $page = (int) $_GET["halaman"] ? (int) $_GET["halaman"] : 1;
                                $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
                                $sql = "select * from group_song limit $mulai,$halaman";
                                $res = $db->query($sql);
                                $page = $res->num_rows < 1 ? $page = $page - 1 : $page;
                                header("Location: viewSong.php?halaman=$page");
                            }
                            if (isset($_GET["save"])) {
                                $idSave = isset($_GET["save"]) ? (int) $_GET["save"] : null;

                                $sql = "select * from group_song where id_song = '$idSave'";
                                $res = $db->query($sql);

                                $row = $res->fetch_row();
                                list($id_song, $id_group, $id_album, $title_song, $main_track) = $row;
                                $id_groupSave = isset($_POST["title-song"]) ? (int) $_POST["id-group"] : $id_group;
                                $id_albumSave = isset($_POST["title-song"]) ? (int) $_POST["id-album"] : $id_album;
                                $judulSave = isset($_POST["title-song"]) ? (string) $_POST["title-song"] : $title_song;
                                $trackSave = isset($_POST["title-song"]) ? (int) $_POST["main-track"] : $main_track;

                                $query = "UPDATE group_song SET id_group = $id_groupSave, id_album = $id_albumSave, title_song = '$judulSave', main_track = $trackSave WHERE id_song = '$idSave'";
                                $save = $db->query($query);
                                echo $db->error;
                                $page = (int) $_GET["halaman"] ? (int) $_GET["halaman"] : 1;
                                header("Location: viewSong.php?halaman=$page");
                            } else {
                                if (isset($_GET["edit"]) and (int) $_GET["halaman"]) {
                                    $page = (int) $_GET["halaman"] ? (int) $_GET["halaman"] : 1;
                                    $id = (int) $_GET["edit"];
                                    $sql = "select * from group_song where id_song = '$id'";
                                    $res = $db->query($sql);

                                    $row = $res->fetch_row();
                                    list($id_song, $id_group, $id_album, $title_song, $main_track) = $row;
                                    $mainBool = $main_track == 1 ? "TRUE" : "FALSE";
                                    echo "<form method='post' action='?halaman=$page&save=$id'>";
                                    echo "<tr><td>$id_song</td>";
                                    echo "<td><input type='text' name='id-group' value='$id_group'></input></td>";
                                    echo "<td><input type='text' name='id-album' value='$id_album'></input></td>";
                                    echo "<td><input type='text' name='title-song' value='$title_song'></input></td>";
                                    echo "<td><select name='main-track' value='$mainBool'>
                <option value='1'>TRUE</option>
                <option value='0'>FALSE</option>
                </select></td>";
                                    echo "<td colspan='2'><button name='save' type='submit'>Save</button>";
                                    echo "<button name='cancel'><a href='?halaman=$page'>Cancel</a></button></td></tr>";
                                    echo "</form>";
                                } else {
                                    $halaman = 10;
                                    $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                                    $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
                                    $sql = "select * from group_song limit $mulai,$halaman";
                                    $query = "select * from group_song";
                                    $total = $db->query($query);
                                    $pages = ceil($total->num_rows / $halaman);

                                    $res = $db->query($sql);
                                    if ($res) { //query success
                                        $row = $res->fetch_row();
                                        do {
                                            list($id_song, $id_group, $id_album, $title_song, $main_track) = $row;
                                            echo "<tr><td>$id_song</td>";
                                            echo "<td>$id_group</td>";
                                            echo "<td>$id_album</td>";
                                            echo "<td>$title_song</td>";
                                            echo "<td>$main_track</td>";
                                            echo "<td colspan='2'><button name='delete'><a href='?halaman=$page&delete=$id_song'>Delete</a></button>";
                                            echo "<button name='delete'><a href='?halaman=$page&edit=$id_song'>Edit</a></button></td></tr>";
                                        } while ($row = $res->fetch_row());
                                    } else {
                                        echo "Gagal Ekseksi SQL" . (DEVELOPMENT ? " : " . $db->error : "") . "<br>";
                                    }
                                }
                            }
                        }}
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