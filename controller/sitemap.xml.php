<?php
    use \FastBlog\Post;
    use Thepixeldeveloper\Sitemap\Urlset;
    use Thepixeldeveloper\Sitemap\Url;
    use Thepixeldeveloper\Sitemap\Drivers\XmlWriterDriver;

    ob_clean();
    header('Content-type: text/xml');

    $base_url = 'https://'.$_SERVER['SERVER_NAME'].'/';
    $posts = Post::getActual();
    $urlset = new Urlset();

    foreach(['about', 'contact'] as $page) {
        $url = new Url($base_url.$page);
        $url->setLastMod( (new DateTime())->setTimestamp(filemtime(__FILE__)) );
        $url->setChangeFreq("daily");
        $url->setPriority(1);
        $urlset->add($url);
    }

    foreach(Post::getCats() as $id => $cat) {
        $page = "cat/".$id."-".str_replace(' ', '-', $cat);

        $url = new Url($base_url.$page);
        $url->setLastMod( (new DateTime())->setTimestamp(filemtime(__FILE__)) );
        $url->setChangeFreq("daily");
        $url->setPriority(0.8);
        $urlset->add($url);
    }

    foreach($posts as $post) {
        $time = (new DateTime())->setTimestamp( isset($post->time) ? $post->time : 0 );

        $url = new Url($base_url.$post->link);
        $url->setLastMod( $time );
        $url->setChangeFreq("weekly");
        $url->setPriority(0.5);
        $urlset->add($url);
    }

    $driver = new XmlWriterDriver();
    $urlset->accept($driver);

    print($driver->output());