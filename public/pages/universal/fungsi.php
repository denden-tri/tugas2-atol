<?php include_once "universal.php"; ?>
<?php include_once "../../../component/mysqlconnect.php"; ?>
<?php
function getTotalHalaman($table)
{
    $db = dbConnect();
    $halaman = 10;
    switch ($table) {
        case 'group':
            $query = "SELECT * FROM group_name";
            break;

        case 'album':
            $query = "SELECT * FROM group_album";
            break;

        case 'member':
            $query = "SELECT * FROM group_member";
            break;

        case 'song':
            $query = "SELECT * FROM group_song";
            break;
        
        default:
            break;
    }
    
    $total = $db->query($query);
    $pages = ceil($total->num_rows / $halaman);
    return $pages;
}
