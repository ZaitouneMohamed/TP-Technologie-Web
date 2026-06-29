<?php
session_start();
$_SESSION['panier'] = [];
header('Location: ../panier.php');
exit();