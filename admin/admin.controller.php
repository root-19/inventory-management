<?php
if($_SESSION['user_type'] == "staff"){

  header("Location: ../");
  exit();

}
?>