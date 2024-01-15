<?php
    use \FastBlog\Post;

    try {
        if(isset($_use_post_domain))
            $id = ['link' => $_use_post_domain];
        elseif(isset($_GET['id']))
            $id = ['_id' => new \MongoDB\BSON\ObjectId($_GET['id'])];
        else {
            header('Location: /');
            return;
        }
    } catch(Exception $e) {
        header('Location: /');
        return;
    }

    $post = Post::getOne($id);

    if(!is_object($post) || !isset($post->title)) {
        header('Location: /');
        return;
    }

    if(isset($post->img) && strlen($post->img))
        $header_background = $post->img;

    $header_title = $post->title;

    if(isset($post->short) && mb_strlen($post->short))
        $seo_desc = $post->short;

    if(isset($post->sub) && mb_strlen($post->sub))
        $header_desc = $post->sub;

    require_once(__DIR__.'/_header.php');
?>
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <?=Post::compile($post->full);?>

            <div align="center">
                <script async="async" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                <script async="async" src="//yastatic.net/share2/share.js"></script>
                <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,twitter,linkedin,lj,tumblr,viber,whatsapp,skype,telegram"></div>
            </div>

            <p style="text-align: center; font-size: 70%; margin-top: 0px;">
                опубликовано <?=date('d.m.Y', isset($post->time) ? $post->time : 0);?>
                в рубрике <u><?=Post::catText(isset($post->cat) ? $post->cat : 'какой-нибудь удалённой');?></u>
            </p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12 col-md-12 mx-auto">
            <!--
            <div id="disqus_thread"></div>
            <script>
                var disqus_config = function () {
                    this.page.url = 'https://<?=$_SERVER['SERVER_NAME'];?>/<?=$post->link;?>';
                    this.page.identifier = '<?=$post->_id;?>';
                };

                (function() {
                    var d = document, s = d.createElement('script');
                    s.src = 'https://xdepartment.disqus.com/embed.js';
                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                })();
            </script>
            <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

            <script type="text/javascript" src="https://vk.com/js/api/openapi.js?160"></script>
            <script type="text/javascript">
                VK.init({apiId: 6869645, onlyWidgets: true});
            </script>
            <div id="vk_comments"></div>
            <script type="text/javascript">
                VK.Widgets.Comments("vk_comments", {limit: 15, attach: "*"});
            </script>-->
        </div>
    </div>
<?php
    require_once(__DIR__.'/_footer.php');
?>