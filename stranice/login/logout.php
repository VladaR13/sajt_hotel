<?php


if(isset($_POST["logout"])){
    session_destroy();
    header("Location: ../../index.php");
    exit();


}

?>

<form method="post">
<button name="logout" type="button" class="text-white ml-4 bg-red-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2 text-center  dark:hover:bg-red-700 dark:focus:ring-red-800">Odjavi se</button>


</form>