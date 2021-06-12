<?php include_once "../universal/universal.php"; ?>
<?php include_once "../../../component/mysqlconnect.php"; ?>
<?php

function getDataGroup($mulai)
{
    $db = dbConnect();
    $sql = "SELECT * FROM group_name  ORDER BY id_group LIMIT $mulai, 10";
    $res = $db->query($sql);
    return $res;
}

function cariDataGroup($cari, $mulai)
{
    $db = dbConnect();
    $sql = "SELECT * FROM group_name WHERE nama_group LIKE '%$cari%' LIMIT $mulai, 10";
    $res = $db->query($sql);
    return $res;
}

function deleteDataGroup($id)
{
    $id = (int) decrypt($id,$_SESSION["passp"],$_SESSION["iv"]);
    $db = dbConnect();
    $sql = "DELETE FROM group_name WHERE id_group = $id";
    $db->query($sql);
}

function addDataGroup($nama_group, $tgl_debut, $agensi)
{
    $db = dbConnect();
    $sql = "SELECT MAX( id_group ) AS maxi FROM group_name";
    $res = $db->query($sql);

    $row = $res->fetch_row();
    list($maxi) = $row;

    $sql = "ALTER TABLE group_name AUTO_INCREMENT = $maxi";
    $db->query($sql);

    $sql = "INSERT INTO group_name(nama_group, tgl_debut, agensi) VALUES ('$nama_group', STR_TO_DATE('$tgl_debut', '%Y-%m-%d'), '$agensi')";
    $db->query($sql);
}

function formAddGroup()
{ ?>
    <form method="POST" action="?add-data" id="add-data-form">
        <tr>
            <td>Auto</td>
            <td><input type="text" name="nama-group" id="nama-group" oninvalid="erroMessage(this)" oninput="erroMessage(this)" required></input></td>
            <td><input type="date" name="tgl-debut" placeholder="dd/mm/yyyy" id="tgl-debut" required/></td>
            <td><input type='text' name='agensi' id="agensi" required></input></td>
            <td colspan="2"><button type="submit" name="add-btn">Add Data</button>
                <button type="submit" name="cancel-btn"><a href="?">Cancel</a></button>
            </td>
        </tr>
    </form>
<?php }

function tableGroup($row, $page)
{
    list($id_group, $nama_group, $tgl_debut, $agensi) = $row; ?>
    <tr>
        <td><?php echo $id_group ?></td>
        <td><?php echo $nama_group ?></td>
        <td><?php echo $tgl_debut ?></td>
        <td><?php echo $agensi ?></td>
        <?php $id_group = urlencode(encrypt($id_group, $_SESSION["passp"],$_SESSION["iv"])) ?>
        <td colspan='2'><button name='delete'><a href='?halaman=<?php echo $page ?>&delete=<?php echo $id_group ?>'>Delete</a></button>
            <button name='delete'><a href='?halaman=<?php echo $page ?>&edit=<?php echo $id_group ?>'>Edit</a></button>
        </td>
    </tr>
<?php }

function tableEditGroup($id, $page)
{
    $db = dbConnect();
    $id = (int) decrypt($id,$_SESSION["passp"],$_SESSION["iv"]);
    $sql = "SELECT * from group_name where id_group = '$id'";
    $res = $db->query($sql);

    $row = $res->fetch_row();

    list($id_group, $nama_group, $tgl_debut, $agensi) = $row; ?>
    <form method='POST' action='?halaman=<?php echo $page?>&save=<?php echo $id = urlencode(encrypt($id, $_SESSION["passp"],$_SESSION["iv"]))?>'>
    <tr><td><?php echo $id_group ?></td>
    <td><input type='text' name='nama-group' value='<?php echo $nama_group ?>'></input></td>
    <td><input type='text' name='tgl-debut' value='<?php echo $tgl_debut ?>'></input></td>
    <td><input type='text'name='agensi' value='<?php echo $agensi ?>'></input></td>
    <td colspan='2'><button name='save' type='submit'>Save</button>
    <button name='cancel'><a href='?halaman=<?php echo $page ?>'>Cancel</a></button></td></tr>
    </form>
<?php }

function saveEditGroup($idSave, $namaSave, $tglSave, $agensiSave)
{
    $db = dbConnect();
    $idSave = (int) decrypt($idSave,$_SESSION["passp"],$_SESSION["iv"]);
    $sql = "SELECT nama_group, tgl_debut, agensi FROM group_name WHERE id_group = '$idSave'";
    $res = $db->query($sql);

    $row = $res->fetch_row();
    list($nama_group, $tgl_debut, $agensi) = $row;
    $namaSave = $namaSave !="" ? $namaSave : $nama_group;
    $tglSave = $tglSave !="" ? $tglSave : $tgl_debut;
    $agensiSave = $agensiSave !="" ? $agensiSave : $agensi;

    $query = "UPDATE group_name SET nama_group = '$namaSave', tgl_debut = '$tglSave', agensi = '$agensiSave' WHERE id_group = '$idSave'";
    $db->query($query);
}

?>