<?php
define("DB_SERVER", "localhost");
define("DB_USERNAME", "softtroni_treading");
define("DB_PASSWORD", "TreadingApp@123");
define("DB_NAME", "softtroni_treading");

# Connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

# Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
