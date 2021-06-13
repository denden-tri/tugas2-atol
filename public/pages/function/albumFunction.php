<?php include_once "../universal/universal.php"; ?>
<?php include_once "../../../component/mysqlconnect.php"; ?>
<?php

function getDataAlbum($mulai)
{
    $db = dbConnect();
    $sql = "SELECT * FROM group_album  ORDER BY id_album LIMIT $mulai, 10";
    $res = $db->query($sql);
    return $res;
}

function cariDataAlbum($cari, $mulai)
{
    $db = dbConnect();
    $sql = "SELECT * FROM group_album WHERE title_album LIKE '%$cari%' LIMIT $mulai, 10";
    $res = $db->query($sql);
    return $res;
}

function deleteDataAlbum($id)
{
    $id = (int) decrypt($id, $_SESSION["passp"], $_SESSION["iv"]);
    $db = dbConnect();
    $sql = "DELETE FROM group_album WHERE id_album = $id";
    $db->query($sql);
}

function addDataAlbum($id_group, $title_album, $jenis, $tgl_rilis)
{
    $db = dbConnect();
    $sql = "SELECT MAX( id_album ) AS maxi FROM group_album";
    $res = $db->query($sql);

    $row = $res->fetch_row();
    list($maxi) = $row;

    $sql = "ALTER TABLE group_album AUTO_INCREMENT = $maxi";
    $db->query($sql);

    $sql = "INSERT INTO group_album(id_group, title_album, jenis, tgl_rilis) VALUES ('$id_group','$title_album', '$jenis' , STR_TO_DATE('$tgl_rilis', '%Y-%m-%d'))";
    $db->query($sql);
}

function formAddAlbum()
{ ?>
    <form method='post' action='?add-data'>
        <tr>
            <td>Auto</td>
            <td><input type='text' name='id-group' required></input></td>
            <td><input type='text' name='title-album' required></input></td>
            <td>
                <select name='jenis' required>
                    <option value='Mini Album'>Mini Album</option>
                    <option value='Full Album'>Full Album</option>
                </select>
            </td>
            <td><input type="date" name="tgl-rilis" placeholder="dd/mm/yyyy" id="tgl-rilis" required /></td>
            <td colspan="2"><button type="submit" name="add-btn">Add Data</button>
                <button type="submit" name="cancel-btn"><a href="?">Cancel</a></button>
            </td>
        <?php }

    function tableAlbum($row, $page)
    {
        list($id_album, $id_group, $title_album, $jenis, $tgl_rilis) = $row; ?>
        <tr>
            <td><?php echo $id_album ?></td>
            <td><?php echo $id_group ?></td>
            <td><?php echo $title_album ?></td>
            <td><?php echo $jenis ?></td>
            <td><?php echo $tgl_rilis ?></td>
            <?php $id_album = urlencode(encrypt($id_album, $_SESSION["passp"], $_SESSION["iv"])) ?>
            <td colspan='2'><button name='delete'><a href='?halaman=<?php echo $page ?>&delete=<?php echo $id_album ?>'>Delete</a></button>
                <button name='delete'><a href='?halaman=<?php echo $page ?>&edit=<?php echo $id_album ?>'>Edit</a></button>
            </td>
        </tr>
    <?php }

    function tableEditAlbum($id, $page)
    {
        $db = dbConnect();
        $id = (int) decrypt($id, $_SESSION["passp"], $_SESSION["iv"]);
        $sql = "SELECT * from group_album where id_album = '$id'";
        $res = $db->query($sql);

        $row = $res->fetch_row();

        list($id_album, $id_group, $title_album, $jenis, $tgl_rilis) = $row; ?>
        <form method='post' action='?halaman=<?php echo $page ?>&save=<?php echo $id = urlencode(encrypt($id, $_SESSION["passp"],$_SESSION["iv"]))?>'>
            <tr>
                <td><?php echo $id_album ?></td>
                <td><input type="text" name="id-group" value="<?php echo $id_group ?>" required/></td>
                <td><input type='text' name='title-album' value='<?php echo $title_album ?>' required /></td>
                <td><select name='jenis' required>
                        <option value='<?php echo $jenis ?>'><?php echo $jenis ?></option>
                        <option value='Mini Album'>Mini Album</option>
                        <option value='Full Album'>Full Album</option>
                    </select></td>
                <td><input type='text' name='tgl-rilis' value='<?php echo $tgl_rilis ?>' required></input></td>
                <td colspan='2'><button name='save' type='submit'>Save</button>
                    <button name='cancel'><a href='?halaman=<?php echo $page ?>'>Cancel</a></button>
                </td>
            </tr>
        </form>
    <?php }

    function saveEditAlbum($idSave,$idGroupSave, $titleSave, $jenisSave, $tglSave)
    {
        $db = dbConnect();
        $idSave = (int) decrypt($idSave, $_SESSION["passp"], $_SESSION["iv"]);
        $sql = "SELECT id_group, title_album, jenis, tgl_rilis FROM group_album WHERE id_album = '$idSave'";
        $res = $db->query($sql);

        $row = $res->fetch_row();
        list($id_group, $title_album, $jenis, $tgl_rilis) = $row;
        $idGroupSave = $idGroupSave != "" ? $idGroupSave : $id_group;
        $titleSave = $titleSave != "" ? $titleSave : $title_album;
        $jenisSave = $jenisSave != "" ? $jenisSave : $jenis;
        $tglSave = $tglSave != "" ? $tglSave : $tgl_rilis;

        $query = "UPDATE group_album SET title_album = '$titleSave', jenis = '$jenisSave', tgl_rilis = '$tglSave' WHERE id_album = '$idSave'";
        $db->query($query);
    }

    ?>