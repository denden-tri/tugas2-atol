<?php
include_once "../universal/universal.php"
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                            <th>Id Name</th>
                            <th>Id Group</th>
                            <th>Nama Member</th>
                            <th>Tanggal Lahir</th>
                            <th>Aksi</th>
                        </tr>
                        <?php
                        if (isset($_GET['cari'])) {
                            $cari = isset($_GET["cari"]) ? (string) $_GET['cari'] : "";

                            $halaman = 10;
                            $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                            $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
                            $sql = "select * from group_member WHERE nama_member LIKE '%$cari%' limit $mulai,$halaman";
                            $query = "select * from group_member";
                            $total = $db->query($query);
                            $pages = ceil($total->num_rows / $halaman);

                            $res = $db->query($sql);
                            if ($res) { //query success
                                $row = $res->fetch_row();
                                do {
                                    list($id_name, $id_group, $nama_member, $tgl_lahir) = $row;
                                    echo "<tr><td>$id_name</td>";
                                    echo "<td>$id_group</td>";
                                    echo "<td>$nama_member</td>";
                                    echo "<td>$tgl_lahir</td>";
                                    echo "<td colspan='2'><button name='delete'><a href='?halaman=$page&delete=$id_name'>Delete</a></button>";
                                    echo "<button name='delete'><a href='?halaman=$page&edit=$id_name'>Edit</a></button></td></tr>";
                                } while ($row = $res->fetch_row());
                            }
                        } else {
                            if (isset($_GET["add-data"])) {
                                $id_group = isset($_POST["id-group"]) ? (int) $_POST["id-group"] : null;
                                $nama_member = isset($_POST["nama-member"]) ? (string) $_POST["nama-member"] : null;
                                $tgl_lahir = isset($_POST["tgl-lahir"]) ? (string) $_POST["tgl-lahir"] : null;

                                $sql = "SELECT MAX( id_name ) as maxi FROM group_member";
                                $res = $db->query($sql);

                                $row = $res->fetch_row();
                                list($maxi) = $row;

                                $sql = "ALTER TABLE group_member AUTO_INCREMENT = $maxi";
                                $db->query($sql);

                                $sql = "INSERT INTO group_member(id_group, nama_member, tgl_lahir) VALUES ('$id_group', '$nama_member', STR_TO_DATE('$tgl_lahir', '%Y-%m-%d'))";
                                $db->query($sql);
                                $halaman = 10;
                                $query = "select * from group_member";
                                $total = $db->query($query);
                                $pages = ceil($total->num_rows / $halaman);
                                header("Location: viewMember.php?halaman=$pages");
                            } else if (isset($_GET["add"])) {
                                echo "<form method='post' action='?add-data'>";
                                echo "<tr><td>Auto</td>";
                                echo "<td><input type='text' name='id-group'></input></td>";
                                echo "<td><input type='text' name='nama-member'></input></td>"; ?>
                                <td><input type="text" name="tgl-lahir" value="yyyy-mm-dd" onFocus="this.value=''" /></td>
                    <?php echo "<td colspan='2'><button type='submit' name='add-btn'>Add Data</button>";
                                echo "<button type='submit' name='cancel-btn'><a href='?'>Cancel</a></button></td></tr>";
                                echo "</form>";
                            } else {

                                if (isset($_GET["delete"]) and isset($_GET["halaman"])) {
                                    $id = isset($_GET["delete"]) ? (int) $_GET["delete"] : 0;
                                    $query = "DELETE FROM group_member WHERE id_name = $id";
                                    $db->query($query);
                                    $halaman = 10;
                                    $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                                    $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
                                    $sql = "select * from group_member limit $mulai,$halaman";
                                    $res = $db->query($sql);
                                    $page = $res->num_rows < 1 ? $page = $page - 1 : $page;
                                    header("Location: viewMember.php?halaman=$page");
                                }
                                if (isset($_GET["save"])) {
                                    $idSave = isset($_GET["save"]) ? (int) $_GET["save"] : null;

                                    $sql = "select * from group_member where id_name = '$idSave'";
                                    $res = $db->query($sql);

                                    $row = $res->fetch_row();
                                    list($id_name, $id_group, $nama_member, $tgl_lahir) = $row;
                                    $id_groupSave = isset($_POST["id-group"]) ? (int) $_POST["id-group"] : $id_group;
                                    $nama_memberSave = isset($_POST["nama-member"]) ? (string) $_POST["nama-member"] : $nama_member;
                                    $tgl_lahirSave = isset($_POST["tgl-lahir"]) ? (string) $_POST["tgl-lahir"] : $tgl_lahir;

                                    $query = "UPDATE group_member SET id_group = $id_groupSave, nama_member = '$nama_memberSave', tgl_lahir = '$tgl_lahirSave' WHERE id_name = '$idSave'";
                                    $save = $db->query($query);
                                    $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                                    header("Location: viewMember.php?halaman=$page");
                                } else {
                                    if (isset($_GET["edit"]) and isset($_GET["halaman"])) {
                                        $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                                        $id = (int) $_GET["edit"];
                                        $sql = "select * from group_member where id_name = $id";
                                        $res = $db->query($sql);
                                        $row = $res->fetch_row();
                                        list($id_name, $id_group, $nama_member, $tgl_lahir) = $row;
                                        echo "<form method='post' action='?halaman=$page&save=$id'>";
                                        echo "<tr><td>$id_name</td>";
                                        echo "<td><input type='text' name='id-group' value='$id_group'></input></td>";
                                        echo "<td><input type='text' name='nama-member' value='$nama_member'></input></td>";
                                        echo "<td><input type='text' name='tgl-lahir' value='$tgl_lahir'></input></td>";
                                        echo "<td colspan='2'><button name='save' type='submit'>Save</button>";
                                        echo "<button name='cancel'><a href='?halaman=$page'>Cancel</a></button></td></tr>";
                                        echo "</form>";
                                    } else {
                                        $halaman = 10;
                                        $page = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
                                        $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
                                        $sql = "select * from group_member limit $mulai,$halaman";
                                        $query = "select * from group_member";
                                        $total = $db->query($query);
                                        $pages = ceil($total->num_rows / $halaman);

                                        $res = $db->query($sql);
                                        if ($res) { //query success
                                            $row = $res->fetch_row();
                                            do {
                                                list($id_name, $id_group, $nama_member, $tgl_lahir) = $row;
                                                echo "<tr><td>$id_name</td>";
                                                echo "<td>$id_group</td>";
                                                echo "<td>$nama_member</td>";
                                                echo "<td>$tgl_lahir</td>";
                                                echo "<td colspan='2'><button name='delete'><a href='?halaman=$page&delete=$id_name'>Delete</a></button>";
                                                echo "<button name='delete'><a href='?halaman=$page&edit=$id_name'>Edit</a></button></td></tr>";
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