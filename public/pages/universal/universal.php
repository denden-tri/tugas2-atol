<?php
session_start();
if (!isset($_SESSION["user"])) {
    if(!isset($_COOKIE["username"]) ) {
        header("Location: /index.php?error=4");
    }
    header("Location: /index.php?error=4");
}?>

<?php 
function encrypt($id, $passp, $iv){
    $method = "aes-128-cbc";
    return openssl_encrypt($id,$method, $passp,0,$iv);

}

function decrypt($id, $passp, $iv){
    $method = "aes-128-cbc";
    return openssl_decrypt($id,$method, $passp,0,$iv);
}

function logout()
{
    session_destroy();
    setcookie("username", "", time() - 3600, "/");
    setcookie("password", "", time() - 3600, "/");
    header("Location: /index.php");
}
?>

<?php function navBar()
{ ?>
    <nav class="view-menu">
        <div class="banner">
            <h1 class="banner h1"><a href="/">Menu Admin</a></h1>
        </div>
        <div class="inner-view">
            <div class="option">Lihat Data : </div>
            <div>
                <ul>
                    <li><a class="link-list" href="/pages/view/viewGroup.php">Group</a></li>
                    <li><a class="link-list" href="/pages/view/viewMember.php">Member</a></li>
                    <li><a class="link-list" href="/pages/view/viewAlbum.php">Album</a></li>
                    <li><a class="link-list" href="/pages/view/viewSong.php">Song</a></li>
                </ul>
            </div>
            <div>
                <button class="btn-logout"><a class="logout" href="/pages/logout.php">Logout</a></button>
            </div>
        </div>
    </nav>
<?php } ?>
