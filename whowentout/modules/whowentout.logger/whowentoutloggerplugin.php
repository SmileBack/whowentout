<?php

class WhoWentOutLoggerPlugin extends Plugin
{

    /**
     * @var \UserEventLogger
     */
    private $logger;

    function __construct()
    {
        parent::__construct();
        $this->logger = new UserEventLogger();
    }

    function on_smile_sent(Smile_Sent_Event $e)
    {
        $data = array(
            'sender_id' => $e->sender->id,
            'receiver_id' => $e->receiver->id,
            'party_id' => $e->party->id,
        );
        $this->logger->log($e->sender, $this->get_time(), 'smile_sent', $data);
    }

    function on_smile_match(Smile_Match_Event $e)
    {

    }

    function on_checkin(Checkin_Event $e)
    {
        $data = array(
            'party_id' => $e->party->id,
            'previous_party_id' => $e->previous_party ? $e->previous_party->id : NULL,
        );
        $this->logger->log($e->user, $this->get_time(), 'checkin', $data);
    }

    function on_page_view(Page_View_Event $e)
    {
        $this->logger->log($e->user, $e->time, 'page_view', array(
                                                                 'url' => $e->url,
                                                            ));
    }

    function on_picture_view($e)
    {
        //        $this->logger->log($e->user, $e->time, 'picture_view', $data);
    }

    private function get_time()
    {
        return college()->get_time();
    }

}
