<?php

namespace FastBlog;

/**
 * Class Model
 * @package FastBlog
 */
class Model
{
    /**
     * @var DB|null
     */
    protected $db = null;

    /**
     * @var \MongoDB\BSON\ObjectId|null
     */
    protected $id = null;

    /**
     * @var string
     */
    protected static $table = '';

    /**
     * Model constructor.
     * @param \MongoDB\BSON\ObjectId $id
     */
    public function __construct(\MongoDB\BSON\ObjectId $id)
    {
        $this->db = &DB::i();
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function check()
    {
        $res = $this->db->findOne(static::$table, ['_id' => $this->id]);

        if(!is_array($res) && !is_object($res)) {
            return false;
        }

        return true;
    }

    /**
     * @param $field
     * @param $value
     * @return int|null
     */
    public function __set($field, $value)
    {
        return $this->db->update(static::$table, ['_id' => $this->id], [$field => $value]);
    }

    /**
     * @return array|object|null
     */
    public function get()
    {
        return $this->db->findOne(static::$table, ['_id' => $this->id]);
    }

    /**
     * @param array $query
     * @param $opt
     * @return \MongoDB\Driver\Cursor
     */
    public static function getAll(array $query, $opt = null)
    {
        return DB::i()->find(static::$table, $query, $opt);
    }

    /**
     * @param array $query
     * @return array|object|null
     */
    public static function getOne(array $query)
    {
        return DB::i()->findOne(static::$table, $query);
    }

    /**
     * @param array $query
     * @param array $set
     * @return int|null
     */
    public static function update(array $query, array $set)
    {
        return DB::i()->update(static::$table, $query, $set);
    }

    /**
     * @param $field
     * @return object|null
     */
    public function __get($field)
    {
        $res = $this->db->findOne(static::$table, ['_id' => $this->id]);

        if(!is_object($res) || is_null($res->{$field}))
            return null;
        else
            return $res->{$field};
    }

    /**
     * @param array $insert
     * @return mixed
     */
    public static function create(array $insert)
    {
        return DB::i()->insert(static::$table, $insert);
    }
}