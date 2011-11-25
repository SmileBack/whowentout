<?php

class Database
{

    /* @var PDO */
    public $dbh = null;

    private $config = array();
    private $tables = array();

    private $required_options = array('host', 'database', 'username', 'password');
    
    function __construct($config)
    {
        $this->connect($config);
        $this->load_tables();
    }
    
    function connect(array $options)
    {
        check_required_options($options, $this->required_options);
        
        $this->config = $options;
        $this->dbh = new PDO("mysql:host={$options['host']};dbname={$options['database']}",
            $options['username'], $options['password']);

        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->load_tables();
    }

    function name()
    {
        return $this->config['name'];
    }

    function begin_transaction()
    {
        $this->dbh->beginTransaction();
    }

    function commit_transaction()
    {
        $this->dbh->commit();
    }

    function rollback_transaction()
    {
        $this->dbh->rollBack();
    }

    function last_insert_id()
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * @param  $table_name
     * @return DatabaseTable
     */
    function table($table_name)
    {
        if ( ! isset($this->tables[$table_name] ) ) {
            return null;
        }

        if ( $this->tables[$table_name] == null ) {
            $this->tables[$table_name] = new DatabaseTable($this, $table_name);
        }

        return $this->tables[$table_name];
    }

    function list_table_names()
    {
        return array_keys($this->tables);
    }

    /**
     * @param  $table_name
     * @param  $columns
     * @return DatabaseTable
     */
    function create_table($table_name, $columns)
    {
        $sql = $this->create_table_sql($table_name, $columns);
        $query = $this->query_statement($sql);
        $query->execute();

        $this->tables[$table_name] = new DatabaseTable($this, $table_name);

        return $this->tables[$table_name];
    }

    function rename_table($table_name, $new_table_name)
    {
        $table = $this->table($table_name);

        $query = $this->query_statement("RENAME TABLE $table_name TO $new_table_name");
        $query->execute();

        unset($this->tables[$table_name]);
        $table->_load_schema_from_database($new_table_name); //refresh schema so it has updated name
        $this->tables[$new_table_name] = $table;
    }

    function destroy_table($table_name)
    {
        $query = $this->query_statement("DROP TABLE $table_name");
        $query->execute();

        unset($this->tables[$table_name]);
    }

    function drop_table($table_name)
    {
        throw new Exception("Please use the destroy_table method.");
    }

    function create_table_sql($table_name, $columns)
    {
        $column_schemas = array();
        foreach ($columns as $column_name => $column_config) {
            $column_schemas[] = $this->get_column_sql($column_name, $column_config);
        }

        return "CREATE TABLE $table_name\n"
               . "(\n"
               . implode(",\n", $column_schemas) . "\n"
               . ") ENGINE=INNODB";
    }

    private function get_column_sql($column_name, $column_config)
    {
        $column_type = f()->class_loader()->init_subclass('columntype', $column_config['type'], $column_config);
        return $column_name . ' ' . $column_type->to_sql();
    }

    private function load_tables()
    {
        $statement = $this->query_statement('SHOW TABLES');
        $statement->execute();
        $table_names = $statement->fetchAll(PDO::FETCH_COLUMN);

        foreach ($table_names as $name) {
            // lazy loading of schema.
            // when we ask for the schema of a table for the first time, the schema will be loaded into $this->tables[table name]
            $this->tables[$name] = null;
        }
    }

    /**
     *
     * @param string $sql An SQL query
     * @param array $params If placeholders are used in your sql, place your values in $params.
     * @return PDOStatement
     */
    function query_statement($sql, $params = array())
    {
        $statement = $this->dbh->prepare($sql);
        foreach ($params as $name => $value) {
            // PDO wants all parameter placeholders to start with a ':'
            $placeholder = $name{0} == ':' ? $name : ":$name";
            $statement->bindParam($placeholder, $params[$name]);
        }
        return $statement;
    }

}