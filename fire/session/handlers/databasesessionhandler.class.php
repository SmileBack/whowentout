<?php

class DatabaseSessionHandler extends SessionHandler
{

    /* @var $db Database */
    private $db;

    private $sessions_table = 'sessions';

    function __construct(Database $database)
    {
        $this->db = $database;
    }

    function open($path, $name)
    {
        return true;
    }

    function close()
    {
        return true;
    }

    function read($sess_id)
    {
        $row = $this->table()->row($sess_id);
        return $row ? $row->data : '';
    }

    function write($sess_id, $data)
    {
        $row = $this->table()->row($sess_id);
        
        if ($row) {
//            $row->data = $data;
//            $row->updated = new DateTime();
//            $row->save();
        }
        else {
            $this->table()->create_row(array(
                                           'id' => $sess_id,
                                           'created' => new DateTime(),
                                           'updated' => new DateTime(),
                                           'data' => $data,
                                       ));
        }
        
        return true;
    }

    function destroy($sess_id)
    {
        $this->table()->destroy_row($sess_id);
        return true;
    }

    function gc($sess_maxlifetime)
    {
        //todo: garbage collection
        return true;
    }

    /**
     * @return DatabaseTable
     */
    private function table()
    {
        return $this->db->table($this->sessions_table);
    }

}
