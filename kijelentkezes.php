<?php
session_start();
session_destroy();
header("Location: index.php?kijelentkezes=1");
exit();
?>
