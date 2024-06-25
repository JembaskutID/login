<?php

$host="localhost"; // nama server kita
$user="root"; // username dari server 
$password=""; // biarkan kosong karena default password telah kosong
$db="crud"; // masukan nama database yang telah kita buat 

$conn = mysqli_connect($host,$user,$password,$db);
if (!$conn){
         die("Koneksi Gagal:".mysqli_connect_error());
} else {
}
?>