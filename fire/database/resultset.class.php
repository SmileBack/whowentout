<?php

class ResultSet implements Iterator
{

    /**
     * @var DatabaseField
     */
    private $select_field;

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

    private $link_resolver;

    function __construct(DatabaseTable $base_table)
    {
        $this->select_field = new DatabaseField($base_table, $base_table->id_column()->name());
        $this->link_resolver = new DatabaseLinkResolver();
    }

    /**
     * @param  $field_name
     * @param  $field_value
     * @return ResultSet
     */
    function where($field_name, $field_value)
    {
        $set = clone $this;
        $set->add_where($field_name, $field_value);
        return $set;
    }

    function add_where($field_name, $field_value)
    {
        $table = $this->select_field->column()->table();
        $field = new DatabaseField($table, $field_name);
        $this->filters[] = new DatabaseFilter($field, $field_value);
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
        $table = $this->select_field->column()->table();
        $field = new DatabaseField($table, $field_name);
        $this->order_by = new DatabaseOrderBy($field, $order);
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

    /**
     * @return DatabaseTableLink[]
     */
    function get_required_links()
    {
        $links = array();

        foreach ($this->select_field->link_path->links as $link) {
            if ($link instanceof DatabaseTableLink)
                $links[ $link->left_table->name() ] = $link;
        }

        foreach ($this->filters as $filter) {
            foreach ($filter->field->link_path->links as $link) {
                if ($link instanceof DatabaseTableLink)
                    $links[ $link->left_table->name() ] = $link;
            }
        }

        return $links;
    }

    function to_sql()
    {
        $sql = array();

        $sql[] = 'SELECT ' . $this->select_field->to_sql() . ' AS id FROM ' . $this->select_field->column()->table()->name();

        $links = $this->get_required_links();

        foreach ($links as $link) {
            $sql[] = "\n  INNER JOIN " . $link->right_table->name()
                    . " ON " . $link->left_table->name() . "." . $link->left_column->name()
                    . " = " . $link->right_table->name() . "." . $link->right_column->name();
        }

        if (count($this->filters) > 0)
            $sql[] = "\n  " . $this->get_where_sql();

//        if ($this->order_by)
//            $sql[] = "\n  " . $this->order_by->to_sql();
//
//        if ($this->limit)
//            $sql[] = "\n  " . $this->limit->to_sql();

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

    function __get($field_name)
    {
        $field = new DatabaseField($this->select_field, $field_name);
        if ($field->is_valid()) {

        }
        else {
            return null;
        }
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
        return $this->select_field->column()->table();
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
                $joins[$join->join_table->name()] = $join;
            }
        }

        if ($this->order_by) {
            foreach ($this->order_by->joins() as $join) {
                $joins[$join->join_table->name()] = $join;
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
