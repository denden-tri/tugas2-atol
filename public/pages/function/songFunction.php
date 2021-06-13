<?php include_once "../universal/universal.php"; ?>
<?php include_once "../../../component/mysqlconnect.php"; ?>
<?php

function getDataSong($mulai)
{
    $db = dbConnect();
    $sql = "SELECT * FROM group_song  ORDER BY id_song LIMIT $mulai, 10";
    $res = $db->query($sql);
    return $res;
}

function cariDataSong($cari, $mulai)
{
    $db = dbConnect();
    $sql = "SELECT * FROM group_song WHERE title_song LIKE '%$cari%' LIMIT $mulai, 10";
    $res = $db->query($sql);
    return $res;
}

function deleteDataSong($id)
{
    $id = (int) decrypt($id, $_SESSION["passp"], $_SESSION["iv"]);
    $db = dbConnect();
    $sql = "DELETE FROM group_song WHERE id_song = $id";
    $db->query($sql);
}

function addDataSong($id_group, $id_album, $title_song, $main_track)
{
    $db = dbConnect();
    $sql = "SELECT MAX( id_song ) AS maxi FROM group_song";
    $res = $db->query($sql);

    $row = $res->fetch_row();
    list($maxi) = $row;

    $sql = "ALTER TABLE group_song AUTO_INCREMENT = $maxi";
    $db->query($sql);

    $sql = "INSERT INTO group_song(id_group, id_album, title_song, main_track) VALUES ('$id_group','$id_album' ,'$title_song', '$main_track')";
    $db->query($sql);
}

function formAddSong()
{ ?>
    <form method='post' action='?add-data'>
        <tr>
            <td>Auto</td>
            <td><input type='text' name='id-group' required></input></td>
            <td><input type='text' name='id-album' required></input></td>
            <td><input type='text' name='title-song' required></input></td>
            <td><select name='main-track' required>
                    <option value='1'>TRUE</option>
                    <option value='0'>FALSE</option>
                </select></td>
            <td colspan='2'><button type='submit' name='add-btn'>Add Data</button>
                <button type='submit' name='cancel-btn'><a href='?'>Cancel</a></button>
            </td>
        </tr>
    </form>
<?php }

function tableSong($row, $page)
{
    list($id_song, $id_group, $id_album, $title_song, $main_track) = $row; ?>
    <tr>
        <td><?php echo $id_song ?></td>
        <td><?php echo $id_group ?></td>
        <td><?php echo $id_album ?></td>
        <td><?php echo $title_song ?></td>
        <td><?php echo $main_track ?></td>
        <?php $id_song = urlencode(encrypt($id_song, $_SESSION["passp"], $_SESSION["iv"])) ?>
        <td colspan='2'><button name='delete'><a href='?halaman=<?php echo $page ?>&delete=<?php echo $id_song ?>'>Delete</a></button>
            <button name='delete'><a href='?halaman=<?php echo $page ?>&edit=<?php echo $id_song ?>'>Edit</a></button>
        </td>
    </tr>
<?php }

function tableEditSong($id, $page)
{
    $db = dbConnect();
    $id = (int) decrypt($id, $_SESSION["passp"], $_SESSION["iv"]);
    $sql = "SELECT * from group_song where id_song = '$id'";
    $res = $db->query($sql);

    $row = $res->fetch_row();

    list($id_song, $id_group, $id_album, $title_song, $main_track) = $row;
    $mainBool = $main_track == 1 ? "TRUE" : "FALSE"; ?>
    <form method='post' action='?halaman=<?php echo $page ?>&save=<?php echo $id = urlencode(encrypt($id, $_SESSION["passp"], $_SESSION["iv"])) ?>'>
        <tr>
            <td><?php echo $id_song ?></td>
            <td><input type='text' name='id-group' value='<?php echo $id_group ?>' required></input></td>
            <td><input type='text' name='id-album' value='<?php echo $id_album ?>' required></input></td>
            <td><input type='text' name='title-song' value='<?php echo $title_song ?>' required></input></td>
            <td><select name='main-track' value='<?php echo $mainBool ?>'>
                    <option value='1'>TRUE</option>
                    <option value='0'>FALSE</option>
                </select></td>
            <td colspan='2'><button name='save' type='submit'>Save</button>
                <button name='cancel'><a href='?halaman=<?php echo $page ?>'>Cancel</a></button>
            </td>
        </tr>
    </form>
<?php }

function saveEditSong($idSave, $id_groupSave, $id_albumSave, $judulSave, $trackSave)
{
    $db = dbConnect();
    $idSave = (int) decrypt($idSave, $_SESSION["passp"], $_SESSION["iv"]);
    $sql = "select * from group_song where id_song = '$idSave'";
    $res = $db->query($sql);

    $row = $res->fetch_row();
    list($id_song, $id_group, $id_album, $title_song, $main_track) = $row;
    $id_groupSave = $id_groupSave != "" ? $id_groupSave : $id_group;
    $id_albumSave = $id_albumSave != "" ? $id_albumSave : $id_album;
    $judulSave = $judulSave != "" ? $judulSave : $title_song;
    $trackSave = $trackSave != "" ? $trackSave : $main_track;

    $query = "UPDATE group_song SET id_group = $id_groupSave, id_album = $id_albumSave, title_song = '$judulSave', main_track = $trackSave WHERE id_song = '$idSave'";
    $db->query($query);
}
