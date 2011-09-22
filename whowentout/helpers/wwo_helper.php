<?php

function ci()
{
    return get_instance();
}

/**
 * @return XCollege
 */
function college()
{
    return XCollege::current();
}

/**
 * @return XPlace
 */
function place($place_id)
{
    return XPlace::get($place_id);
}

/**
 * @return XParty
 */
function party($party_id)
{
    return XParty::get($party_id);
}

/**
 * @return XSmile
 */
function smile($smile_id)
{
    return XSmile::get($smile_id);
}

/**
 * @return XSmileMatch
 */
function smilematch($smile_match_id)
{
    return XSmileMatch::get($smile_match_id);
}

function option_exists($name)
{
    return ci()->option->exists($name);
}

function get_option($name, $default = NULL)
{
    return ci()->option->get($name, $default);
}

function unset_option($name)
{
    ci()->option->delete($name);
}

function set_option($name, $value)
{
    ci()->option->set($name, $value);
}

/**
 * @return ImageRepository
 */
function images()
{
    static $images = NULL;

    if (!$images) {
        ci()->config->load('imagerepository');
        $config = ci()->config->item('imagerepository');
        $config = $config[$config['active_group']];
        
        if ($config['source'] == 'filesystem') {
            return new FilesystemImageRepository($config['path']);
        }
        elseif ($config['source'] == 's3') {
            return new S3ImageRepository($config['bucket']);
        }

    }

    return $images;
}

function post($key = NULL)
{
    if ($key) {
        return ci()->input->post($key);
    }
    else {
        $post = array();
        foreach ($_POST as $k => $v) {
            $post[$k] = ci()->input->post($k);
        }
        return $post;
    }
}

function set_message($message)
{
    ci()->session->set_userdata('message', $message);
}

function pull_message()
{
    $message = get_message();
    ci()->session->unset_userdata('message');
    return $message;
}

function get_message()
{
    return ci()->session->userdata('message');
}

function parties_dropdown($parties)
{
    $options = array();
    foreach ($parties as $party) {
        $options[$party->id] = $party->place->name;
    }

    if (empty($parties))
        $extra = 'class="empty"';
    else
        $extra = '';

    return form_dropdown('party_id', $options, '', $extra);
}

function places_dropdown($places)
{
    $options = array();
    foreach ($places as $place) {
        $options[$place->id] = $place->name;
    }
    return form_dropdown('place_id', $options);
}

function grad_year_dropdown($selected_year = NULL)
{
    $options = array('0' => '    ');
    for ($i = 1; $i <= 4; $i++) {
        $year = college()->today()->modify("+$i year")->format('Y');
        $options[$year] = $year;
    }
    return form_dropdown('grad_year', $options, $selected_year);
}

function load_view($view_name, $data = array())
{
    $CI =& get_instance();
    return $CI->load->view($view_name, $data, TRUE);
}

/* 
 * Loads the view of a section. The sections are located in views/sections
 * $section_name The name of the section. For example, 'my_info_view'.
 * $title The (optional) title of the section. If provided, a heading will
 * be shown for the section. If left out, nothing will be shown.
 * $data An array of any variables you would like to pass into the section.
 * This works just like when you pass data in with load_view.
 */
function load_section_view($section_name, $title = '', $data = array())
{
    return render('section', array(
                                  'id' => $section_name,
                                  'title' => $title,
                                  'vars' => $data,
                             ));
}

function get_hometown_city($hometown)
{
    return trim(string_before_last(',', $hometown));
}

function get_hometown_state($hometown)
{
    return trim(string_after_last(',', $hometown));
}

function get_state_abbreviation($full_state_name)
{
    require_once 'state_data.php';
    $data = _get_state_data();
    return isset($data[$full_state_name]) ? $data[$full_state_name] : NULL;
}

function state_dropdown($name = 'state', $selected = '')
{
    require_once 'state_data.php';
    $data = array_values(_get_state_data());
    $options = array('' => '');
    $options = array_merge($options, array_combine($data, $data));
    return form_dropdown($name, $options, $selected);
}

function where_friends_went_pie_chart_data(DateTime $date)
{
    if (!logged_in())
        return NULL;

    $data = array();

    foreach (current_user()->where_friends_went($date) as $party_id => $friend_ids) {
        $party = party($party_id);
        $data[] = array($party->place->name, count($friend_ids), $party->id);
    }

    return $data;
}

function get_reason_message($reason)
{
    $reasons = ci()->config->item('reasons');

    if (is_string($reason))
        return $reason;
    else
        return $reasons[$reason];
}

function update_facebook_friends($user, $force_update = FALSE)
{
    $access_token = get_option('admin_facebook_access_token');
    fb()->setAccessToken($access_token);

    $user = user($user);
    $user->update_friends_from_facebook($force_update);
}


function post_to_wall($user, $message, $access_token = NULL)
{
    if (!$access_token)
        fb()->setAccessToken($access_token);

    $user = user($user);
    $attachment = array(
        'message' => $message,
        'link' => "http://www.whowentout.com",
        'caption' => "Connecting people after a night out.",
    );
    fb()->api("/$user->facebook_id/feed", 'POST', $attachment);
}


/**
 *
 * @param string$filepath
 *   The path to the file in question.
 * @return bool
 *   Whether the file located at $filepath is a valid image.
 */
function is_valid_image($filepath)
{
    return getimagesize($filepath) !== FALSE;
}

function body_id()
{
    $uri = uri_string();
    if (!$uri)
        $uri = 'home';
    return preg_replace('/\//', '_', $uri) . '_page';
}

function curl_file_get_contents($url)
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $url);
    $contents = curl_exec($c);
    curl_close($c);

    if ($contents)
        return $contents;
    else
        return FALSE;
}

function first_name($full_name)
{
    $parts = preg_split('/\s+/', $full_name);
    return $parts[0];
}

function site_version($type = 'site')
{
    $ci =& get_instance();

    if ($type == 'site')
        return $ci->config->item('css_version') + $ci->config->item('js_version');
    else
        return $ci->config->item("{$type}_version");
}
