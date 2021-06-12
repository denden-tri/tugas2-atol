<?php include_once "../universal/universal.php"; ?>
<?php include_once "../../../component/mysqlconnect.php"; ?>
<?php

function getDataMember($mulai)
{
    $db = dbConnect();
    $sql = "SELECT * FROM group_member  ORDER BY id_member LIMIT $mulai, 10";
    $res = $db->query($sql);
    return $res;
}

function cariDataMember($cari, $mulai)
{
    $db = dbConnect();
    $sql = "SELECT * FROM group_member WHERE nama_member LIKE '%$cari%' LIMIT $mulai, 10";
    $res = $db->query($sql);
    return $res;
}

function deleteDataMember($id)
{
    $id = (int) decrypt($id, $_SESSION["passp"], $_SESSION["iv"]);
    $db = dbConnect();
    $sql = "DELETE FROM group_member WHERE id_member = $id";
    $db->query($sql);
}

function addDataMember($id_group, $nama_member, $tgl_lahir)
{
    $db = dbConnect();
    $sql = "SELECT MAX( id_member ) AS maxi FROM group_member";
    $res = $db->query($sql);

    $row = $res->fetch_row();
    list($maxi) = $row;

    $sql = "ALTER TABLE group_member AUTO_INCREMENT = $maxi";
    $db->query($sql);

    $sql = "INSERT INTO group_member(id_group, nama_member, tgl_lahir,) VALUES ('$id_group', '$nama_member', STR_TO_DATE('$tgl_lahir', '%Y-%m-%d'))";
    $db->query($sql);
}

function formAddMember()
{ ?>
    <form method='post' action='?add-data'>
        <tr>
            <td>Auto</td>
            <td><input type='text' name='id-group' required></input></td>
            <td><input type='text' name='nama-member' required></input></td>
            <td><input type="date" name="tgl-lahir" placeholder="dd/mm/yyyy" required/></td>
            <td colspan='2'><button type='submit' name='add-btn'>Add Data</button>
                <button type='submit' name='cancel-btn'><a href='?'>Cancel</a></button>
            </td>
        </tr>
    </form>
<?php }

function tableMember($row, $page)
{
    list($id_name, $id_group, $nama_member, $tgl_lahir) = $row; ?>
    <tr>
    <tr>
        <td><?php echo $id_name ?></td>
        <td><?php echo $id_group ?></td>
        <td><?php echo $nama_member ?></td>
        <td><?php echo $tgl_lahir ?></td>
        <?php $id_name = urlencode(encrypt($id_name, $_SESSION["passp"], $_SESSION["iv"])) ?>
        <td colspan='2'><button name='delete'><a href='?halaman=<?php echo $page ?>&delete=<?php echo $id_group ?>'>Delete</a></button>
            <button name='delete'><a href='?halaman=<?php echo $page ?>&edit=<?php echo $id_name ?>'>Edit</a></button>
        </td>
    </tr>
<?php }

function tableEditMember($id, $page)
{
    $db = dbConnect();
    $id = (int) decrypt($id, $_SESSION["passp"], $_SESSION["iv"]);
    $sql = "SELECT * from group_member where id_group = '$id'";
    $res = $db->query($sql);

    $row = $res->fetch_row();

    list($id_name, $id_group, $nama_member, $tgl_lahir) = $row; ?>
    <form method='POST' action='?halaman=<?php echo $page ?>&save=<?php echo $id = urlencode(encrypt($id, $_SESSION["passp"], $_SESSION["iv"])) ?>'>
        <tr>
            <td><?php echo $id_name ?></td>
            <td><input type='text' name='nama-member' value='<?php echo $id_group ?>'></input></td>
            <td><input type='text' name='nama-group' value='<?php echo $nama_member ?>'></input></td>
            <td><input type='text' name='tgl-lahir' value='<?php echo $tgl_lahir ?>'></input></td>
            <td colspan='2'><button name='save' type='submit'>Save</button>
                <button name='cancel'><a href='?halaman=<?php echo $page ?>'>Cancel</a></button>
            </td>
        </tr>
    </form>
<?php }

function saveEditMember($idSave,$idGroupSave, $namaSave, $tglSave)
{
    $db = dbConnect();
    $idSave = (int) decrypt($idSave, $_SESSION["passp"], $_SESSION["iv"]);
    $sql = "SELECT id_group, nama_member, tgl_lahir FROM group_member WHERE id_member = '$idSave'";
    $res = $db->query($sql);

    $row = $res->fetch_row();
    list($id_group, $nama_member, $tgl_lahir) = $row;
    $idGroupSave = $idGroupSave != "" ? $idGroupSave:$id_group;
    $namaSave = $namaSave != "" ? $namaSave : $nama_member;
    $tglSave = $tglSave != "" ? $tglSave : $tgl_lahir;

    $query = "UPDATE group_member SET id_group = '$idGroupSave',nama_member = '$namaSave', tgl_lahir = '$tglSave' WHERE id_group = '$idSave'";
    $db->query($query);
}

?>