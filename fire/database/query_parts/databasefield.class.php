<?php

class DatabaseField extends QueryPart
{
    
    /* @var $links DatabaseLinkPath */
    public $link_path;

    private $link_resolver;

    function __construct(DatabaseTable $table, $field_name)
    {
        $this->link_resolver = new DatabaseLinkResolver();

        $this->link_path = $this->link_resolver->resolve_link_path($table, $field_name);
    }

    function to_sql()
    {
        if ($this->column())
            return $this->column()->table()->name() . '.' . $this->column()->name();
        
        return null;
    }

    /**
     * @return DatabaseColumn|null
     */
    function column()
    {
        $count = count($this->link_path->links);
        $last_link = $this->link_path->links[$count - 1];
        
        if ($last_link instanceof DatabaseColumnLink) {
            /* @var $last_link DatabaseColumnLink */
            return $last_link->right_column;
        }
        
        return null;
    }
    
}
