<?php
define("DEVELOPMENT", true); //Menyatakan situs masih dalam pengembangan
function dbConnect()
{
    $db = new mysqli("localhost", "root", "sakura", "girlgroup");
    return $db;
}
