<?php
    if(isset($_SERVER['PHP_AUTH_USER'])) {
        $cookie = $_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW'];
        $_COOKIE['access'] = $cookie;
        setcookie('access', $cookie);
    }

    if(
        !isset($_COOKIE['access'])
        ||
        !strlen($_COOKIE['access'])
        ||
        $_COOKIE['access'] !== 'admin:000123123123' // @todo ENV var
    ) {
        header('WWW-Authenticate: Basic realm="You should not pass!"');
        header('HTTP/1.0 401 Unauthorized');
        return;
    }

    use FastBlog\Post;

    $header_desc = 'Привет! О чем напишем? <a href="?">🐈</a>';
    $header_background = 'https://static1.squarespace.com/static/5464f80fe4b09b9aff070764/t/56ccd02f22482ed43c61604f/1456263230902/';

    if(isset($_POST['new_post'])) {
        Post::create([
            'active' => false,
            'title' => $_POST['new_post'],
            'time' => time()
        ]);

        header('Location: ?act=welcome');
        return;
    }

    if(isset($_POST['update']) && isset($_POST['id'])) {
        Post::update(['_id' => new \MongoDB\BSON\ObjectId($_POST['id'])], ['time' => time()]);
        header('Location: ' . $_SERVER['REQUEST_URI']);
        return;
    }

    if(isset($_POST['new']) && isset($_GET['id'])) {
        $new = $_POST['new'];
        $new['short'] = strip_tags($new['short']);

        if(isset($new['active']))
            $new['active'] = (bool)$new['active'];

        Post::update(['_id' => new \MongoDB\BSON\ObjectId($_GET['id'])], $new);
        header('Location: ' . $_SERVER['REQUEST_URI']);
        return;
    }

    if(isset($_GET['debug']))
    {
        $_SESSION['debug'] = true;
        header('Location: /');
        return;
    }

    if(isset($_GET['act']) && $_GET['act'] === 'test')
    {
        $post = Post::getOne(['_id' => new \MongoDB\BSON\ObjectId($_GET['id'])]);

        if(isset($post->img) && strlen($post->img))
            $header_background = $post->img;
    }

    require_once(__DIR__.'/_header.php');
?>
    <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <?php switch(@$_GET['act']) { case 'new': ?>
                <form action="" method="POST">
                    <input type="text" name="new_post" style="width:70%" value="" placeholder="Заговолок" />
                    <input type="submit" style="width:20%" value="Черновик" />
                </form>
            <?php break;

                case 'test':
                    echo Post::compile($post->full);
                    break;

                case 'edit':
                    $post = Post::getOne(['_id' => new \MongoDB\BSON\ObjectId($_GET['id'])]);
            ?>
                    <style>
                        input[type=text], select {
                            width: 100%;
                        }

                        textarea {
                            width: 100%;
                            height: 200px;
                        }
                    </style>

                    <form action="" method="POST">
                        <p>
                            <input autocomplete="off" type="text" name="new[title]" value="<?=$post->title;?>" placeholder="Заговолок" />
                        </p>
                        <p>
                            <input autocomplete="off" type="text" name="new[sub]" value="<?=isset($post->sub) ? $post->sub : '';?>" placeholder="Подзаговолок" />
                        </p>
                        <p>
                            <input autocomplete="off" type="text" name="new[link]" value="<?=isset($post->link) ? $post->link : uniqid().'.html';?>" placeholder="URL домен" />
                        </p>
                        <p>
                            <input autocomplete="off" type="text" name="new[img]" value="<?=isset($post->img) ? $post->img: '';?>" placeholder="Обложка" />
                        </p>
                        <p>
                            <select name="new[cat]" autocomplete="off">
                                <?php foreach(Post::getCats() as $id => $cat) { ?>
                                    <option <?=($post->cat==$id) ? 'selected="selected"' : '';?> value="<?=$id;?>"><?=$cat;?></option>
                                <?php } ?>
                            </select>
                        </p>
                        <p>
                            <textarea autocomplete="off" name="new[short]"><?=isset($post->short) ? $post->short : 'Краткое содержание под кат';?></textarea>
                        </p>
                        <p>
                            <div class="editor"></div>
                            <textarea style="display:none;" autocomplete="off" id="editorcfg" name="new[full]"><?=isset($post->full) ? $post->full : 'Полная версия';?></textarea>
                        </p>
                        <p>
                            <input autocomplete="off" type="radio" name="new[active]" <?=$post->active==false?'checked="checked"':''?> value="0"> Черновик
                            <br>
                            <input autocomplete="off" type="radio" name="new[active]" <?=$post->active==true?'checked="checked"':''?> value="1"> Опубликовано
                        </p>

                        <input type="button" onclick="window.open('?act=test&id=<?=$_GET['id'];?>')" value="Предпросмотр" />
                        <input type="submit" value="Сохранить" />
                    </form>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            var options = {
                                placeholder: 'Строчим...',
                                theme: 'snow'
                            };

                            window.myeditor = new Quill($('.editor')[0], options);
                            window.myeditor.clipboard.dangerouslyPasteHTML( $('#editorcfg').val() );
                        });

                        var form = document.querySelector('form');

                        form.onsubmit = function() {
                            $('#editorcfg').val( window.myeditor.root.innerHTML );
                            return true;
                        };
                    </script>
            <?php break; default: ?>
                <script>
                    function _setupdate(id) {
                        if(!confirm('Обновить дату '+id+'?') || !confirm('Точно-точно?'))
                            return;

                        $.post('', {'id': id, 'update': 1}, function(d) {
                            console.log(d);
                            location.reload();
                        });
                    }
                </script>
                <ul>
                    <li><a href="?act=new">Создать</a></li>
                    <li>Редактировать:</li>
                    <ul>
                        <?php
                            foreach(Post::getAll([]) as $post) {
                                echo '<li><b><a href="?act=edit&id='.$post->_id.'">'.$post->title.'</a></b>';

                                if($post->active == false)
                                    echo ' <small>(черновик)</small>';

                                echo ' - <small><a href="#" onclick="_setupdate(\''.$post->_id.'\')">'.date('r', isset($post->time) ? $post->time : 0).'</a></small>';
                                echo '</li>';
                            }
                        ?>
                    </ul>
                </ul>
            <?php break; } ?>
        </div>
    </div>

<hr>
<a href="?logout=1" class="btn btn-default pull-right">Логаут</a>
<?php
require_once(__DIR__.'/_footer.php');
?>