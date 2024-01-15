<?php
    namespace FastBlog;

    /**
     * Прокладка для работы с базой данных
     * Используется драйвер для PHP7 и pecl-расширение mongodb
     * @package FastBlog
     */
    class DB
    {
        /**
         * @var \MongoDB\Database
         */
        protected $db;
        protected $database;
        protected $mgr;

        /**
         * @var DB
         */
        protected static $i;

        /**
         * @return DB
         */
        public static function i()
        {
            if(is_null(self::$i))
                self::$i = new DB(_DB_CONNECTION, _DB_BASE);

            return self::$i;
        }

        /**
         * DB constructor.
         * @param string $conn
         * @param string $db
         */
        public function __construct($conn, $db = 'blog')
        {
            // используем и менеджер, и клиент; второй для обычных нужд, первый для расширенных
            $this->database = $db;
            $this->db = (new \MongoDB\Client($conn))->{$db};
            $this->mgr = new \MongoDB\Driver\Manager($conn);
        }

        /**
         * Какая-то статистика. Используем вместо ping запросов
         * @return \MongoDB\Driver\ReadPreference
         */
        public function stats()
        {
            return $this->mgr->getReadPreference();
        }

        /**
         * Выбрать конкретную таблицу. Обычно не требуется
         * @param string $table
         * @return \MongoDB\Collection
         */
        public function select($table)
        {
            return $this->db->{$table};
        }

        /**
         * Вставить объект в коллекцию
         * @param string $table
         * @param array $array
         * @return mixed
         */
        public function insert($table, array $array)
        {
            $collection = $this->db->{$table};
            $result = $collection->insertOne($array);
            return $result->getInsertedId();
        }

        /**
         * Удалить объекты из коллекции
         * @param string $table
         * @param array $array
         * @return int
         */
        public function delete($table, array $array)
        {
            $collection = $this->db->{$table};
            $result = $collection->deleteMany($array);
            return $result->getDeletedCount();
        }

        /**
         * Найти объекты по критериям
         * @param string $table
         * @param array $query
         * @param null|array $opt
         * @return \MongoDB\Driver\Cursor
         */
        public function find($table, array $query, $opt = null)
        {
            $collection = $this->db->{$table};
            return $collection->find($query, is_array($opt) ? $opt : []);
        }

        /**
         * Посчитать кол-во объектов по критериям
         * @param string $table
         * @param array $query
         * @param null $opt
         * @return int
         */
        public function count($table, array $query, $opt = null)
        {
            $collection = $this->db->{$table};
            return $collection->countDocuments($query, is_array($opt) ? $opt : []);
        }

        /**
         * Обновить ($set) объекты в коллекции (не заменить)
         * @param string $table
         * @param array $query
         * @param array $set
         * @return int|null
         */
        public function update($table, array $query, array $set)
        {
            $collection = $this->db->{$table};
            return $collection->updateMany($query, ['$set' => $set])->getModifiedCount();
        }

        /**
         * Обновить объекты в коллекции, если существуют, если нет - создать
         * Используется replace, т.е. замена, а не $set-обновление
         * @param string $table
         * @param array $query
         * @param array $set
         * @return int|null
         */
        public function upsert($table, array $query, array $set)
        {
            $collection = $this->db->{$table};
            return $collection->replaceOne($query, $set, ['upsert' => true])->getModifiedCount();
        }

        /**
         * Обновить один объект по критерию
         * @param string $table
         * @param array $query
         * @param array $set
         * @return int|null
         */
        public function updateOne($table, array $query, array $set)
        {
            $collection = $this->db->{$table};
            return $collection->updateOne($query, ['$set' => $set])->getModifiedCount();
        }

        /**
         * Найти один объект по критерию
         * @param string $table
         * @param array $query
         * @return array|object|null
         */
        public function findOne($table, array $query)
        {
            $collection = $this->db->{$table};
            return $collection->findOne($query);
        }

        /**
         * Получить массив всех используемых классов и название БД
         * Упаси вас Господь использовать эту функцию кроме как для отладки
         * @see \MongoDB\Client
         * @see \MongoDB\Driver\Manager
         * @return array
         */
        public function getClasses()
        {
            return [$this->database, $this->db, $this->mgr];
        }
    }