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

    function on_smile_received(Smile_Received_Event $e)
    {
        $data = array(
            'sender_id' => $e->sender->id,
            'receiver_id' => $e->receiver->id,
            'party_id' => $e->party->id,
        );
        $this->logger->log($e->receiver, $this->get_time(), 'smile_received', $data);
    }

    function on_smile_match(Smile_Match_Event $e)
    {
        $first_user = $e->match->first_user;
        $second_user = $e->match->second_user;

        $first_party = $e->match->first_smile->party;
        $second_party = $e->match->second_smile->party;

        $first_user_data = array(
            'first_party_id' => $first_party->id,
            'second_party_id' => $second_party->id,
            'matched_user_id' => $second_user->id,
            'sent_first_smile' => TRUE,
        );
        $this->logger->log($first_user, $this->get_time(), 'smile_match', $first_user_data);

        $second_user_data = array(
            'first_party_id' => $first_party->id,
            'second_party_id' => $second_party->id,
            'matched_user_id' => $first_user->id,
            'sent_first_smile' => FALSE,
        );
        $this->logger->log($second_user, $this->get_time(), 'smile_match', $second_user_data);
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

    function on_user_changed_visibility(User_Changed_Visibility_Event $e)
    {
        $this->logger->log($e->user, $this->get_time(), 'user_changed_visibility', array(
                                                                                        'visibility' => $e->visibility,
                                                                                   ));
    }

    function on_chat_sent(Chat_Sent_Event $e)
    {
        $this->logger->log($e->sender, $this->get_time(), 'chat_sent', array(
                                                                            'receiver_id' => $e->receiver->id,
                                                                       ));

        $this->logger->log($e->receiver, $this->get_time(), 'chat_received', array(
                                                                                  'sender_id' => $e->sender->id,
                                                                             ));
    }

    function on_user_view_mutual_friends($e)
    {
        $this->logger->log($e->user, $this->get_time(), 'user_view_mutual_friends', array(
                                                                                         'target_id' => $e->target->id,
                                                                                    ));
    }

    function on_user_edit_profile($e)
    {
        $this->logger->log($e->user, $this->get_time(), 'user_edit_profile');
    }

    private function get_time()
    {
        return college()->get_time();
    }

}
