<?php

class XCollege extends XObject
{

    protected static $table = 'colleges';

    /**
     * @var Clock
     */
    private $clock;
    /**
     * @var Door
     */
    private $door;

    static function current()
    {
        $ci =& get_instance();
        return XCollege::get($ci->config->item('selected_college_id'));
    }

    function __construct($id = NULL)
    {
        parent::__construct($id);
        $this->clock = new PersistentClock($this->get_timezone());
        $this->door = new Door($this->clock);
    }

    /**
     * @return PersistentClock
     */
    function get_clock()
    {
        return $this->clock;
    }

    /**
     * @return Door
     */
    function get_door()
    {
        return $this->door;
    }

    function add_party($date, $place_id)
    {
        if ($date instanceof DateTime)
            $date = date_format($date, 'Y-m-d');

        $party = XParty::create(array(
                                     'place_id' => $place_id,
                                     'date' => $date,
                                ));
        return $party;
    }

    function add_place($name)
    {
        $place = XPlace::create(array(
                                     'college_id' => $this->id,
                                     'name' => $name,
                                ));

        return $place;
    }

    /**
     * Get all of the parties that the user can check into at $time.
     * @param DateTime $time
     * @return array
     *   An array of XParty objects.
     */
    function open_parties($time)
    {
        $parties = array();
        $query = $this->_get_open_parties_query($time);
        return XObject::load_objects('XParty', $query);
    }

    function get_timezone()
    {
        return new DateTimeZone('America/New_York');
    }

    function get_students()
    {
        $query = $this->db()->select('id')
                ->from('users')
                ->where('college_id', $this->id)
                ->order_by('first_name', 'ASC');
        return XObject::load_objects('XUser', $query);
    }

    function get_recent_dates()
    {
        $rows = $this->db()->select('DISTINCT(date) AS date')
                ->from('parties')
                ->join('places', 'parties.place_id = places.id')
                ->where('places.college_id', $this->id)
                ->order_by('date', 'desc')
                ->limit(3)
                ->get()->result();
        $dates = array();
        foreach ($rows as $row) {
            $dates[] = new DateTime($row->date, $this->timezone);
        }
        return $dates;
    }

    function find_student($full_name)
    {
        $parts = preg_split('/\s+/', trim($full_name));
        $first_name = $parts[0];
        $last_name = $parts[count($parts) - 1];
        $full_name = "$first_name $last_name";

        $college_id = $this->id;
        $students = $this->db()->from('college_students')
                ->where('college_id', $college_id)
                ->where('student_full_name', trim($full_name))
                ->get()->result();

        if (empty($students)) {
            $variations = $this->student_name_variations($full_name);
            if (!empty($variations)) {
                $students = $this->db()->from('college_students')
                        ->where('college_id', $college_id)
                        ->where_in('student_full_name', $variations)
                        ->get()->result();
            }
        }

        if (empty($students)) {
            $students = $this->db()->from('college_students')
                    ->where('college_id', $college_id)
                    ->like('student_full_name', substr($first_name, 0, 3), 'after')
                    ->like('student_full_name', " $last_name", 'before')
                    ->get()->result();
        }

        return count($students) == 1 ? $students[0] : FALSE;
    }

    function student_name_variations($full_name)
    {
        list($first_name, $last_name) = preg_split('/\s+/', $full_name);
        $rows = $this->db()->select('name')
                ->from('common_nicknames')
                ->where('nickname', $first_name)
                ->get()->result();
        $variations = array();
        foreach ($rows as $row) {
            $variations[] = "$row->name $last_name";
        }
        return $variations;
    }

    function get_places()
    {
        $query = $this->get_places_query();
        return XObject::load_objects('XPlace', $query);
    }

    function get_places_query()
    {
        return $this->db()->select('id')
                ->from('places')
                ->where('college_id', $this->id)
                ->order_by('name', 'ASC');
    }

    function parties($limit = 10, $date_sort = 'asc')
    {
        $query = $this->get_parties_query($date_sort)->limit($limit);
        return XObject::load_objects('XParty', $query);
    }

    function get_parties()
    {
        $query = $this->get_parties_query();
        return XObject::load_objects('XParty', $query);
    }

    function get_parties_query($date_sort = 'asc')
    {
        return $this->db()->select('parties.id AS id')
                ->from('parties')
                ->join('places', 'parties.place_id = places.id')
                ->where('college_id', $this->id)
                ->order_by('date', $date_sort)
                ->order_by('name', 'ASC');
    }

    function parties_on(XDateTime $date)
    {
        $query = $this->db()
                ->select('parties.id AS id')
                ->from('parties')
                ->where(array(
                             'college_id' => $this->id,
                             'date' => $date->format('Y-m-d'),
                        ))
                ->join('places', 'parties.place_id = places.id');
        return XObject::load_objects('XParty', $query);
    }

    private function _get_open_parties_query(XDateTime $date)
    {
        //open parties today means parties that occured yesterday
        $time = $date->getDay(-1);

        return $this->db()
                ->select('parties.id AS id')
                ->from('parties')
                ->where(array(
                             'college_id' => $this->id,
                             'date' => $time->format('Y-m-d'),
                        ))
                ->join('places', 'parties.place_id = places.id');
    }

    function format_time(DateTime $dt, $format = 'default')
    {
        $formats = array('default' => 'l, M. jS', 'short' => 'D, M. jS');
        return $dt->format($formats[$format]);
    }

    function format_relative_night(DateTime $dt)
    {
        $dt->setTime(0, 0, 0);
        return $dt->format('l') . ' night';
    }

    function to_array()
    {
        $college = array();
        $college['id'] = $this->id;

        $college['currentTime'] = $this->clock->get_time()->getTimestamp();
        $college['doorsClosingTime'] = $this->get_door()->get_opening_time()->getTimestamp();
        $college['doorsOpeningTime'] = $this->get_door()->get_closing_time()->getTimestamp();
        $college['yesterdayTime'] = $this->get_clock()->get_time()->getDay(-1)->getTimestamp();
        $college['tomorrowTime'] = $this->get_clock()->get_time()->getDay(+1)->getTimestamp();
        $college['doorsOpen'] = $this->get_door()->is_open();

        return $college;
    }

}
