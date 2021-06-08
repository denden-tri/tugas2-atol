<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: /public/index.php?error=4");
} ?>

<?php function logout()
{
    session_destroy();
    setcookie("username", "", time() - 3600, "/public");
    setcookie("password", "", time() - 3600, "/public");
    header("Location: /public/index.php");
}
?>

<?php function navBar()
{ ?>
    <nav class="view-menu">
        <div class="banner">
            <h1 class="banner h1"><a href="/public/">Menu Admin</a></h1>
        </div>
        <div class="inner-view">
            <div>Lihat Data : </div>
            <div>
                <ul>
                    <li><a class="link-list" href="/public/pages/view/viewGroup.php">Group</a></li>
                    <li><a class="link-list" href="/public/pages/view/viewMember.php">Member</a></li>
                    <li><a class="link-list" href="/public/pages/view/viewAlbum.php">Album</a></li>
                    <li><a class="link-list" href="/public/pages/view/viewSong.php">Song</a></li>
                </ul>
            </div>
            <div>
                <button class="btn-logout"><a class="logout" href="/public/pages/logout.php">Logout</a></button>
            </div>
        </div>
    </nav>
<?php } ?>

<?php function banner()
{ ?>
    <h1 class="banner">Menu Admin</h1>

<?php } ?>