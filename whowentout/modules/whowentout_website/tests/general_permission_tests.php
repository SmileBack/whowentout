<?php

class General_Permission_Tests extends TestGroup
{

    function setup()
    {
        parent::setup();
    }

    function seed_data()
    {
        $this->clear_database();

        $this->college = XCollege::create(array(
                                               'name' => 'GWU',
                                               'facebook_network_id' => '16777270',
                                               'facebook_school_id' => '108727889151725',
                                          ));

        $this->other_college = XCollege::create(array(
                                               'name' => 'UMD',
                                               'facebook_network_id' => '16777274',
                                               'facebook_school_id' => '113889395291269',
                                          ));

        $this->dan = XUser::create(array(
                                        'facebook_id' => '704222664',
                                        'first_name' => 'Dan',
                                        'last_name' => 'Berenholtz',
                                        'gender' => 'M',
                                        'college_id' => $this->college->id,
                                        'hometown_city' => 'Topeka',
                                        'hometown_state' => 'KS',
                                        'grad_year' => '2012',
                                        'last_edit' => date('Y-m-d H:i:s'),
                                   ));

        $ci =& get_instance();
        $ci->config->set_item('selected_college_id', $this->college->id);
    }

    function test_can_use_website_with_all_info()
    {
        $this->seed_data();
        $use_website_permission = new UseWebsitePermission();

        $can_use_website = $use_website_permission->check($this->dan);
        $this->assert_true($can_use_website, 'dan can use website with all of the information');
    }

    function test_cant_use_website_without_gender()
    {
        $this->seed_data();
        $use_website_permission = new UseWebsitePermission();

        $this->dan->gender = '';
        $can_use_website = $use_website_permission->check($this->dan);
        $this->assert_true(!$can_use_website, 'dan cant use website with a missing gender');
        $this->assert_true($use_website_permission->cant_because(UseWebsitePermission::GENDER_MISSING));

        $this->dan->gender = 'M';
        $can_use_website = $use_website_permission->check($this->dan);
        $this->assert_true($can_use_website, 'dan can use website after adding his gender back');
    }

    function test_cant_use_website_without_hometown()
    {
        $this->seed_data();
        $use_website_permission = new UseWebsitePermission();

        $this->dan->hometown_city = '';
        $can_use_website = $use_website_permission->check($this->dan);
        $this->assert_true(!$can_use_website, 'dan cant use website with a missing hometown city');
        $this->assert_true($use_website_permission->cant_because(UseWebsitePermission::HOMETOWN_MISSING));

        $this->dan->hometown_city = 'Topeka';
        $can_use_website = $use_website_permission->check($this->dan);
        $this->assert_true($can_use_website, 'dan CAN use website after adding a city back');

        $this->dan->hometown_state = '';
        $can_use_website = $use_website_permission->check($this->dan);
        $this->assert_true(!$can_use_website, 'dan cant use website with a missing hometown state');
        $this->assert_true($use_website_permission->cant_because(UseWebsitePermission::HOMETOWN_MISSING));
    }

    function test_user_cant_use_website_with_missing_grad_year()
    {
        $this->seed_data();
        $use_website_permission = new UseWebsitePermission();

        $this->dan->grad_year = '';
        $can_use_website = $use_website_permission->check($this->dan);
        $this->assert_true(!$can_use_website, 'dan cant use website with a missing grad year');
        $this->assert_true($use_website_permission->cant_because(UseWebsitePermission::GRAD_YEAR_MISSING));

        $this->dan->grad_year = 0;
        $can_use_website = $use_website_permission->check($this->dan);
        $this->assert_true(!$can_use_website, 'dan cant use website with a missing grad year');
        $this->assert_true($use_website_permission->cant_because(UseWebsitePermission::GRAD_YEAR_MISSING));
    }

    function test_use_cant_use_website_with_missing_network()
    {
        $this->seed_data();
        $use_website_permission = new UseWebsitePermission();

        $this->dan->college_id = $this->other_college->id;
        $can_use_website = $use_website_permission->check($this->dan);
        $this->assert_true(!$can_use_website, 'dan cant use website if hes in a different network');
        $this->assert_true($use_website_permission->cant_because(UseWebsitePermission::NETWORK_INFO_MISSING));

        $this->dan->college_id = NULL;
        $can_use_website = $use_website_permission->check($this->dan);
        $this->assert_true(!$can_use_website, 'dan cant use website if hes not in any network');
        $this->assert_true($use_website_permission->cant_because(UseWebsitePermission::NETWORK_INFO_MISSING));

        $this->dan->college_id = $this->college->id;
        $can_use_website = $use_website_permission->check($this->dan);
        $this->assert_true($can_use_website, 'dan CAN use the website with the correct network');
    }

}
