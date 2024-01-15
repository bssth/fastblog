<?php
    use \FastBlog\Post;

    if(isset($filter_cat))
        $posts = Post::getByCat($filter_cat);
    else
        $posts = Post::getActual();

    if(!defined('POST_EMBED')) {

        if(isset($filter_cat)) {
            $header_title = Post::catText($filter_cat);
            $header_desc = 'Все записи рубрики';
        } else {
            $header_title = 'Список записей';
            $header_desc = 'Мы знаем всякое-разное и готовы делиться';
        }

        require_once(__DIR__.'/_header.php');
?>
    <div class="row">
        <div class="col-lg-11 col-md-11 mx-auto">
            <?php }
            $cnt = 0;
            foreach($posts as $post) {
                if($post->active !== true)
                    continue;

                $cnt++;
                ?>
                <div class="post-preview">
                    <a href="<?=isset($post->link) ? '/'.$post->link : '/post?id='.$post->_id;?>">
                        <h3 class="post-title">
                            <?=$post->title;?>
                        </h3>
                        <h5 class="post-subtitle">
                            <?=isset($post->short) ? $post->short : '📖';?>
                        </h5>
                    </a>
                    <p class="post-meta">
                        опубликовано <?=date('d.m.Y', isset($post->time) ? $post->time : 0);?>
                        в рубрике <u><?=Post::catText(isset($post->cat) ? $post->cat : 'какой-нибудь удалённой');?></u>
                    </p>
                </div>
                <hr>
            <?php
                }
            ?>

            <?php if($cnt <= 0) { ?>
                <h3>
                    Записей в рубрике нет :(
                </h3>
            <?php } ?>
            <!--
            <div class="clearfix">
                <a class="btn btn-primary float-left disabled" href="#next">&larr; Назад</a>
                <a class="btn btn-primary float-right disabled" href="#next">Дальше &rarr;</a>
                <p style="text-align: center;">
                    <small>страница 1 из 1</small>
                </p>
            </div>
            -->

            <?php
            if(!defined('POST_EMBED')) {
            ?>

            </div>
        </div>
            <?php require_once(__DIR__.'/_footer.php'); } ?>

