<?php
    $header_title = 'Список записей';
    $header_desc = 'Мы знаем всякое-разное и готовы делиться';
    use \FastBlog\Post;

    require_once(__DIR__.'/_header.php');
?>
    <div class="row">
        <div class="col-lg-11 col-md-11 mx-auto">
            <p>
                Привет! Добро пожаловать на некий самостоятельный ресурс-просветитель.
                Если ты не понял, где находишься - приглашаем в раздел <a href="/about">"О проекте"</a>.
            </p>
            <p>
                Ниже отображены последние записи:
            </p>
            <hr>
            <!-- Посты -->
            <?php
                define('POST_EMBED', 1);
                require_once(__DIR__ . '/posts.php');
            ?>
        </div>
    </div>


<?php
    require_once(__DIR__.'/_footer.php');
?>