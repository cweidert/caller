<?php
require_once "util_require_login.php";

echo "<div>";
echo "<span class='left'><a href='view_sections.php' class='button'>Classes</a></span>";
echo "<span><span class='title'>Student Caller</span></span>";
echo "<span class='right'><a href='util_logout.php' class='button'>Log Out</a></span>";
echo "</div>";
echo "<div><span class='right small'>Logged in as $userFirst $userLast</span></div>";
?>