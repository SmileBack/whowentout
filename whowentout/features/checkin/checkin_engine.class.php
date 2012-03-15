<?php

class CheckinEngine
{

    /**
     * @var \Database
     */
    private $database;

    /**
     * @var \Clock
     */
    private $clock;

    /* @var $event_dispathcer EventDispatcher */
    private $event_dispatcher;

    /**
     * @var DatabaseTable
     */
    private $checkins;

    function __construct(Database $database, Clock $clock, EventDispatcher $event_dispatcher)
    {
        $this->database = $database;
        $this->clock = $clock;
        $this->event_dispatcher = $event_dispatcher;

        $this->checkins = $this->database->table('checkins');
    }

    private $event_checkins = array();

    private function load_event_cache_if_missing($event)
    {
        if (!isset($this->event_checkins[$event->id]))
            $this->load_event_cache($event);
    }

    private function load_event_cache($event)
    {
        $this->event_checkins[$event->id] = $this->checkins->where('event_id', $event->id)
                                                           ->order_by('time', 'desc')
                                                           ->to_array();
    }

    private function clear_event_cache($event)
    {
        unset($this->event_checkins[$event->id]);
    }

    function checkin_user_to_event($user, $event)
    {
        $previous_checkin = $this->get_checkin_on_date($user, $event->date);

        if ($previous_checkin && $previous_checkin->event == $event)
            return;

        if ($previous_checkin) {
            $this->remove_checkin_on_date($user, $event->date);
        }

        $checkin = $this->checkins->create_row(array(
            'time' => $this->clock->get_time(),
            'user_id' => $user->id,
            'event_id' => $event->id,
        ));
        $checkin->event->count++;
        $checkin->event->save();

        $this->event_dispatcher->trigger('checkin', array(
            'checkin' => $checkin,
        ));

        $this->clear_event_cache($event);
    }

    function user_has_checked_into_event($user, $event)
    {
        $this->load_event_cache_if_missing($event);
        foreach ($this->event_checkins[$event->id] as $checkin)
            if ($checkin->user_id == $user->id)
                return true;

        return false;
    }

    function get_checkin_on_date($user, DateTime $date)
    {
        if ($user == null)
            return null;

        return $this->checkins->where('user_id', $user->id)
                              ->where('event.date', $date)
                              ->first();
    }

    function remove_checkin_on_date($user, DateTime $date)
    {
        $checkin = $this->get_checkin_on_date($user, $date);

        if ($checkin) {
            $this->checkins->destroy_row($checkin->id);
            $checkin->event->count--;
            $checkin->event->save();
        }


        $this->clear_event_cache($checkin->event);
    }

    function get_checkins_for_event($event)
    {
        $this->load_event_cache_if_missing($event);
        return $this->event_checkins[$event->id];
    }

    function get_checkins_for_user($user)
    {
        return $user->checkins->order_by('event.date', 'desc')->to_array();
    }

    function get_events_on_date(DateTime $date)
    {
        $events = $this->database->table('events')
                              ->where('date', $date)
                              ->to_array();
        return $events;
    }

    function get_checkins_on_date(DateTime $date, $offset = 0, $limit = null)
    {
        benchmark::start(__METHOD__);

        $days_checkins = $this->database->table('checkins')
                                        ->where('event.date', $date)
                                        ->order_by(array(
                                            'event.count' => 'desc',
                                            'event.id' => 'asc',
                                        ));

        if ($limit)
            $days_checkins = $days_checkins->limit($limit, $offset);

        benchmark::end(__METHOD__);

        return $days_checkins->to_array();
    }

    function get_top_parties(DateTime $date)
    {
        $events = $this->database->table('events')
                                 ->where('date', $date)
                                 ->order_by('count', 'desc')
                                 ->limit(3)->to_array();
        return $events;
    }

    /**
     * @param DateTime $date
     * @param object $current_user
     * @param int $offset
     * @param int $limit
     * @return PdoDatabaseStatement
     */
    private function get_all_checkins_on_date_query(DateTime $date, $current_user, $offset = 0, $limit = 0)
    {
        $sql = "SELECT users.id AS user_id, first_name, last_name,
                		events.id AS event_id, events.name AS event_name, events.date,
                		networks.name as network_name,
                		checkins.id AS checkin_id,
                		(user_friends.friend_id IS NOT NULL) as is_friend,
                        (entourage.friend_id IS NOT NULL) AS is_in_entourage
                		FROM users
                          INNER JOIN user_networks
                            ON users.id = user_networks.user_id
                          INNER JOIN networks
                          	ON user_networks.network_id = networks.id AND networks.name IN ('GWU', 'Stanford', 'Maryland')
                          LEFT JOIN user_friends
                            ON user_friends.user_id = :user_id AND users.id = user_friends.friend_id
                          LEFT JOIN entourage
                            ON entourage.user_id = :user_id AND users.id = entourage.friend_id
                          LEFT JOIN checkins
                            ON users.id = checkins.user_id
                          LEFT JOIN events
                            ON checkins.event_id = events.id
                          WHERE (events.id IS NULL OR events.date = :date) AND last_login IS NOT NULL
                          GROUP BY users.id
                          ORDER BY events.count DESC, events.id ASC, checkins.time DESC";

        if ($limit)
            $sql .= " LIMIT $limit OFFSET $offset";

        return $this->database->query_statement($sql, array(
            'date' => $date->format('Y-m-d'),
            'user_id' => $current_user->id,
        ));
    }

    /**
     * @param DateTime $date
     * @param object $current_user
     * @param int $offset
     * @param int $limit
     * @return EventCheckin[]
     */
    function get_all_checkins_on_date(DateTime $date, $current_user, $offset = 0, $limit = 0)
    {
        $query = $this->get_all_checkins_on_date_query($date, $current_user, $offset, $limit);
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_OBJ);

        $event_checkins = array();
        foreach ($rows as $row) {
            $ec = new EventCheckin();
            $ec->user = $this->database->table('users')->row($row->user_id);

            if ($row->event_id)
                $ec->event = $this->database->table('events')->row($row->event_id);

            $ec->is_friend = !!$row->is_friend;
            $ec->is_in_entourage = !!$row->is_in_entourage;

            if ($ec->is_in_entourage)
                $ec->connection = 'entourage';
            elseif ($ec->is_friend)
                $ec->connection = 'friend';
            else
                $ec->connection = 'member';

            $event_checkins[] = $ec;
        }

        return $event_checkins;
    }

    function get_checkin_count($event)
    {
        return $event->count;
    }

}

class EventCheckin
{
    public $user;
    public $event = null;
    public $is_friend = false;
    public $is_in_entourage = false;
    public $connection = 'member';
}
