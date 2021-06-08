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
                            <input type="text" class="input-cari" name="cari" id="cari" value="Cari nama group..." onFocus="this.value=''">
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

                            $halaman = 10;
                            $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                            $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
                            $sql = "select * from group_name WHERE nama_group LIKE '%$cari%' limit $mulai,$halaman";
                            $query = "select * from group_name";
                            $total = $db->query($query);
                            $pages = ceil($total->num_rows / $halaman);

                            $res = $db->query($sql);
                            if ($res) { //query success
                                $row = $res->fetch_row();
                                do {
                                    list($id_group, $nama_group, $tgl_debut, $agensi) = $row;
                                    echo "<tr><td>$id_group</td>";
                                    echo "<td>$nama_group</td>";
                                    echo "<td>$tgl_debut</td>";
                                    echo "<td>$agensi</td>";
                                    echo "<td colspan='2'><button name='delete'><a href='?halaman=$page&delete=$id_group'>Delete</a></button>";
                                    echo "<button name='delete'><a href='?halaman=$page&edit=$id_group'>Edit</a></button></td></tr>";
                                } while ($row = $res->fetch_row());
                            }
                        } else {

                            if (isset($_GET["add-data"])) {
                                $nama_group = isset($_POST["nama-group"]) ? (string) $_POST["nama-group"] : null;
                                $tgl_debut = isset($_POST["tgl-debut"]) ? (string) $_POST["tgl-debut"] : null;
                                $agensi = isset($_POST["agensi"]) ? (string) $_POST["agensi"] : null;

                                $sql = "SELECT MAX( id_group ) as maxi FROM group_name";
                                $res = $db->query($sql);

                                $row = $res->fetch_row();
                                list($maxi) = $row;

                                $sql = "ALTER TABLE group_name AUTO_INCREMENT = $maxi";
                                $db->query($sql);

                                $sql = "INSERT INTO group_name(nama_group, tgl_debut, agensi) VALUES ('$nama_group', STR_TO_DATE('$tgl_debut', '%Y-%m-%d'), '$agensi')";
                                $db->query($sql);
                                $halaman = 10;
                                $query = "select * from group_album";
                                $total = $db->query($query);
                                $pages = ceil($total->num_rows / $halaman);
                                header("Location: viewGroup.php?halaman=$pages");
                            } else if (isset($_GET["add"])) {
                                echo "<form method='post' action='?add-data'>";
                                echo "<tr><td>Auto</td>";
                                echo "<td><input type='text' name='nama-group'></input></td>"; ?>
                                <td><input type="text" name="tgl-debut" value="yyyy-mm-dd" onFocus="this.value=''" /></td>
                    <?php echo "<td><input type='text'name='agensi'></input></td>";
                                echo "<td colspan='2'><button type='submit' name='add-btn'>Add Data</button>";
                                echo "<button type='submit' name='cancel-btn'><a href='?'>Cancel</a></button></td></tr>";
                                echo "</form>";
                            } else {

                                if (isset($_GET["delete"]) and isset($_GET["halaman"])) {
                                    $id = isset($_GET["delete"]) ? (int) $_GET["delete"] : 0;
                                    $query = "DELETE FROM group_name WHERE id_group = $id";
                                    $db->query($query);
                                    $halaman = 10;
                                    $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                                    $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
                                    $sql = "select * from group_name limit $mulai,$halaman";
                                    $res = $db->query($sql);
                                    $page = $res->num_rows < 1 ? $page = $page - 1 : $page;
                                    header("Location: viewGroup.php?halaman=$page");
                                }
                                if (isset($_GET["save"])) {
                                    $idSave = isset($_GET["save"]) ? (int) $_GET["save"] : null;

                                    $sql = "select * from group_name where id_group = '$idSave'";
                                    $res = $db->query($sql);

                                    $row = $res->fetch_row();
                                    list($id_group, $nama_group, $tgl_debut, $agensi) = $row;
                                    $namaSave = isset($_POST["nama-group"]) ? (string) $_POST["nama-group"] : $nama_group;
                                    $tglSave = isset($_POST["tgl-debut"]) ? (string) $_POST["tgl-debut"] : $tgl_debut;
                                    $agensiSave = isset($_POST["agensi"]) ? (string) $_POST["agensi"] : $agensi;

                                    $query = "UPDATE group_name SET nama_group = '$namaSave', tgl_debut = '$tglSave', agensi = '$agensiSave' WHERE id_group = '$idSave'";
                                    $save = $db->query($query);
                                    $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                                    header("Location: viewGroup.php?halaman=$page");
                                } else {
                                    if (isset($_GET["edit"]) and isset($_GET["halaman"])) {
                                        $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                                        $id = (int) $_GET["edit"];
                                        $sql = "select * from group_name where id_group = '$id'";
                                        $res = $db->query($sql);

                                        $row = $res->fetch_row();
                                        list($id_group, $nama_group, $tgl_debut, $agensi) = $row;
                                        echo "<form method='post' action='?halaman=$page&save=$id'>";
                                        echo "<tr><td>$id_group</td>";
                                        echo "<td><input type='text' name='nama-group' value='$nama_group'></input></td>";
                                        echo "<td><input type='text' name='tgl-debut' value='$tgl_debut'></input></td>";
                                        echo "<td><input type='text'name='agensi' value='$agensi'></input></td>";
                                        echo "<td colspan='2'><button name='save' type='submit'>Save</button>";
                                        echo "<button name='cancel'><a href='?halaman=$page'>Cancel</a></button></td></tr>";
                                        echo "</form>";
                                    } else {
                                        $halaman = 10;
                                        $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                                        $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
                                        $sql = "select * from group_name limit $mulai,$halaman";
                                        $query = "select * from group_name";
                                        $total = $db->query($query);
                                        $pages = ceil($total->num_rows / $halaman);

                                        $res = $db->query($sql);
                                        if ($res) { //query success
                                            $row = $res->fetch_row();
                                            do {
                                                list($id_group, $nama_group, $tgl_debut, $agensi) = $row;
                                                echo "<tr><td>$id_group</td>";
                                                echo "<td>$nama_group</td>";
                                                echo "<td>$tgl_debut</td>";
                                                echo "<td>$agensi</td>";
                                                echo "<td colspan='2'><button name='delete'><a href='?halaman=$page&delete=$id_group'>Delete</a></button>";
                                                echo "<button name='delete'><a href='?halaman=$page&edit=$id_group'>Edit</a></button></td></tr>";
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