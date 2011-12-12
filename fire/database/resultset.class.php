<?php

class ResultSet implements Iterator
{
    
    /**
     * @var DatabaseTable
     */
    private $base_table;

    /**
     * @var DatabaseFilter[]
     */
    private $filters = array();

    /**
     * @var DatabaseLimit|null
     */
    private $limit = null;

    /**
     * @var DatabaseOrderBy|null
     */
    private $order_by = null;

    function __construct(DatabaseTable $base_table)
    {
        $this->base_table = $base_table;
    }

    /**
     * @param  $field_name
     * @param  $field_value
     * @return ResultSet
     */
    function where($field_name, $field_value)
    {
        $set = clone $this;
        $set->set_where($field_name, $field_value);
        return $set;
    }

    function set_where($field_name, $field_value)
    {
        $this->filters[] = new DatabaseFilter($this->base_table, $field_name, $field_value);
    }

    /**
     * @param  $field_name
     * @param string $order
     * @return ResultSet
     */
    function order_by($field_name, $order = 'asc')
    {
        $set = clone $this;
        $set->set_order_by($field_name, $order);
        return $set;
    }

    function set_order_by($field_name, $order = 'asc')
    {
        $this->order_by = new DatabaseOrderBy($this->base_table, $field_name, $order);
    }

    /**
     * @param  $n
     * @return ResultSet
     */
    function limit($n)
    {
        $set = clone $this;
        $set->set_limit($n);
        return $set;
    }

    function set_limit($n)
    {
        $this->limit = new DatabaseLimit($n);
    }

    function to_sql()
    {
        $sql = array();

        $sql[] = "SELECT " . $this->base_table->name() . '.' . $this->base_table->id_column()->name()
                           . ' AS id FROM ' . $this->base_table->name();

        foreach ($this->joins() as $join) {
            $sql[] = "\n  " . $join->to_sql();
        }

        if (count($this->filters) > 0)
            $sql[] = "\n  " . $this->get_where_sql();
        
        if ($this->order_by)
            $sql[] = "\n  " . $this->order_by->to_sql();

        if ($this->limit)
            $sql[] = "\n  " . $this->limit->to_sql();
        
        return implode('', $sql);
    }

    function parameters()
    {
        $params = array();
        foreach ($this->filters as $filter) {
            $params = array_merge($params, $filter->parameters());
        }
        return $params;
    }

    /**
     * @return DatabaseRow|null
     */
    function first()
    {
        $set = clone $this;
        $set->limit(1);
        foreach ($set as $item) {
            return $item;
        }
        return null;
    }

    function count()
    {
        $query = $this->database()->query_statement($this->to_sql(), $this->parameters());
        $query->execute();
        return $query->rowCount();
    }

    /* Iterator Methods */
    /**
     * @var TableQueryIterator
     */
    private $iterator;

    function current()
    {
        return $this->iterator->current();
    }

    function key()
    {
        return $this->iterator->key();
    }

    function next()
    {
        return $this->iterator->next();
    }

    function rewind()
    {
        $table = $this->table();
        $sql = $this->to_sql();
        $params = $this->parameters();
        $this->iterator = new TableQueryIterator($table, $sql, $params);

        $this->iterator->rewind();
    }

    function valid()
    {
        return $this->iterator->valid();
    }

    function destroy()
    {
        foreach ($this as $row_id => $row) {
            $this->table()->destroy_row($row_id);
        }
    }

    /**
     * @return DatabaseTable
     */
    function table()
    {
        return $this->base_table;
    }

    /**
     * @return Database
     */
    function database()
    {
        return $this->table()->database();
    }

    /**
     * @return DatabaseTableJoin[]
     */
    private function joins()
    {
        $joins = array();
        foreach ($this->filters as $filter) {
            foreach ($filter->joins() as $join) {
                $joins[ $join->join_table->name() ] = $join;
            }
        }
        
        if ($this->order_by) {
            foreach ($this->order_by->joins() as $join) {
                $joins[ $join->join_table->name() ] = $join;
            }
        }

        return $joins;
    }
    
    private function get_where_sql()
    {
        $sql = array();

        foreach ($this->filters as $filter) {
            $sql[] = $filter->to_sql();
        }

        return "WHERE " . implode(' AND ', $sql);
    }

}
