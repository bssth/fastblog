<?php

namespace FastBlog;

/**
 * Class Post
 * @package FastBlog
 */
class Post extends Model
{
    /**
     * @var string
     */
    protected static $table = 'posts';

    /**
     * @return \MongoDB\Driver\Cursor
     */
    public static function getActual()
    {
        return parent::getAll(['active' => true], ['sort' => ['time' => -1]]);
    }

    /**
     * @param array $query
     * @param array $opt
     * @return \MongoDB\Driver\Cursor
     */
    public static function getAll(array $query, $opt = [])
    {
        return parent::getAll($query, array_merge(['sort' => ['time' => -1]], $opt));
    }

    /**
     * @param $cat
     * @return \MongoDB\Driver\Cursor
     */
    public static function getByCat($cat)
    {
        return parent::getAll(['cat' => ['$in' => [(int)$cat, (string)$cat]]], ['sort' => ['time' => -1]]);
    }

    /**
     * @param string $source
     * @return string
     */
    public static function compile($source)
    {
        $text = trim($source);
        $text = str_replace('[p]', '<p>', $text);
        $text = str_replace('[/p]', '</p>', $text);
        $text = str_replace('[section]', '<h2 class="section-heading">', $text);
        $text = str_replace('[/section]', '</h2>', $text);
        $text = str_replace('[quote]', '<blockquote class="blockquote">', $text);
        $text = str_replace('[/quote]', '</blockquote>', $text);
        $text = str_replace('[caption]', '<span class="caption text-muted">', $text);
        $text = str_replace('[/caption]', '</span>', $text);
        $text = str_replace('[insta]', '<blockquote class="instagram-media" data-instgrm-version="7" ><a href="https://www.instagram.com/p/', $text);
        $text = str_replace('[/insta]', '/"></a></blockquote>', $text);
        $text = str_replace(['(C)', '(С)'], '&copy;', $text);
        return $text;
    }

    /**
     * @param $cat
     * @return string
     */
    public static function catText($cat)
    {
        if(!is_numeric($cat))
            return 'неизвестная рубрика';

        switch( (int)$cat )
        {
            case 0:
                return 'Разное';

            case 1:
                return 'Заметки';

            case 2:
                return 'Разборы';

            case 3:
                return 'Неглубокий анализ';

            case 4:
                return 'Пища для размышлений';

            default:
                return 'без рубрики';
        }
    }

    /**
     * @return array
     */
    public static function getCats()
    {
        $return = [];

        for($i = 0; $i <= 4; $i++) {
            $return[$i] = self::catText($i); // так надо... потом сделаем получше
        }

        return $return;
    }
}