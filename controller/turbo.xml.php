<?php
    use FastBlog\Post;

    ob_clean();
    header('Content-type: text/xml');
    echo '<?xml version="1.0" encoding="UTF-8"?>';

    $posts = Post::getActual();
?>
<rss
        xmlns:yandex="http://news.yandex.ru"
        xmlns:media="http://search.yahoo.com/mrss/"
        xmlns:turbo="http://turbo.yandex.ru"
        version="2.0">
    <channel>
        <title>Title</title>
        <link>https://<?=$_SERVER['SERVER_NAME'];?></link>
        <description>Desc</description>
        <language>ru</language>
        <turbo:analytics
                type="Yandex"
                id=""
        >
        </turbo:analytics>
        <?php
            foreach($posts as $post) {
                if($post->active !== true)
                    continue;
                ?><item turbo="true">
                    <link>https://<?=$_SERVER['SERVER_NAME'];?><?=isset($post->link) ? '/'.$post->link : '/post?id='.$post->_id;?></link>
                    <pubDate><?=date(DATE_RFC822, $post->time);?></pubDate>
                    <author>Test</author>
                    <turbo:content>
                        <![CDATA[
                            <header>
                                <h1><?=$post->title;?></h1>
                                <figure>
                                    <img src="<?=$post->img;?>"/>
                                </figure>
                                <h2><?=$post->sub;?></h2>
                                <menu>
                                <?php foreach(\FastBlog\Post::getCats() as $id => $cat) { if($id==0) continue;?>
                                    <a href="/cat/<?=$id;?>-<?=str_replace(' ', '-', $cat);?>"><?=$cat;?></a>
                                <?php } ?>
                                    <a href="/cat/0-Разное">Разное</a>
                                </menu>
                            </header>
                            <?=Post::compile($post->full);?>

                        ]]>
                    </turbo:content>
                </item><?php } ?>
    </channel>
</rss>