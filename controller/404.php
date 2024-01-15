<?php
    header("HTTP/1.1 404 Not Found");
    http_response_code(404);

    require_once(__DIR__.'/_header.php');
?>

<h3>404: То, что вы хотели, не очень-то и существует 😔</h3>

<?php
    require_once(__DIR__.'/_footer.php');
?>