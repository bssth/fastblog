<?php
    $header_background = "/img/mailbox.jpg";
    $header_title = "Обратная связь";
    $header_desc = "Напиши, мы не обидимся 📭";

    require_once(__DIR__.'/_header.php');
?>
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <p>
                Самый простой способ связаться - через бота BOT <i class="fab fa-telegram"></i>
            </p>
            <p>
                Если же вы не любите Telegram или любите E-Mail - кнопка ниже специально для вас.
            </p>
            <hr>

            <a class="btn btn-primary" href="mailto:">Открыть почтовый клиент и строчить</a>
        </div>
    </div>
<?php
    require_once(__DIR__.'/_footer.php');
?>