<?php

class XDateTime_Tests extends TestGroup
{

    private $tz;
    private $dtFormat = 'Y-m-d H:i:s';

    function setup()
    {
        parent::setup();

        $this->tz = new DateTimeZone('America/New_York');
    }

    function test_tomorrow()
    {
        $dt = new XDateTime('2011-10-20 10:30:00', $this->tz);
        $this->assert_equal($dt->getDay(1)->format($this->dtFormat), '2011-10-21 00:00:00', 'tomorrow');

        $dt = new XDateTime('2011-10-20 00:00:00', $this->tz);
        $this->assert_equal($dt->getDay(1)->format($this->dtFormat), '2011-10-21 00:00:00', 'tomorrow');
    }

    function test_yesterday()
    {
        $dt = new XDateTime('2011-10-20 10:30:00', $this->tz);
        $this->assert_equal($dt->getDay(-1)->format($this->dtFormat), '2011-10-19 00:00:00', 'yesterday');

        $dt = new XDateTime('2011-10-20 00:00:00', $this->tz);
        $this->assert_equal($dt->getDay(-1)->format($this->dtFormat), '2011-10-19 00:00:00', 'yesterday');
    }

    function test_today()
    {
        $dt = new XDateTime('2011-10-20 10:30:00', $this->tz);
        $this->assert_equal($dt->getDay(0)->format($this->dtFormat), '2011-10-20 00:00:00', 'today');

        $dt = new XDateTime('2011-10-20 00:00:00', $this->tz);
        $this->assert_equal($dt->getDay(0)->format($this->dtFormat), '2011-10-20 00:00:00', 'today');
    }

    function test_is_party_day()
    {
        $thursday = new XDateTime('2011-10-20 00:00:00', $this->tz);
        $this->assert_true($thursday->isPartyDay(), 'thursday is a party day');

        $sunday = new XDateTime('2011-10-23 00:00:00', $this->tz);
        $this->assert_true(!$sunday->isPartyDay(), 'sunday is not a party day');
    }

    function test_is_checkin_day()
    {
        $thursday = new XDateTime('2011-10-20 00:00:00', $this->tz);
        $this->assert_true(!$thursday->isCheckinDay(), 'thursday IS NOT a checkin day');

        $friday = new XDateTime('2011-10-21 00:00:00', $this->tz);
        $this->assert_true($friday->isCheckinDay(), 'friday IS a checkin day');

        $sunday = new XDateTime('2011-10-23 00:00:00', $this->tz);
        $this->assert_true($sunday->isCheckinDay(), 'sunday IS a checkin day');

        $monday = new XDateTime('2011-10-24 00:00:00', $this->tz);
        $this->assert_true(!$monday->isCheckinDay(), 'monday IS NOT a checkin day');
    }
    
    function test_next_party_day()
    {
        $saturday = new XDateTime('2011-10-22 00:00:00', $this->tz);
        $sunday = new XDateTime('2011-10-23 00:00:00', $this->tz);
        $wednesday = new XDateTime('2011-10-26 00:00:00', $this->tz);
        $thursday = new XDateTime('2011-10-27 00:00:00', $this->tz);

        $next_party_day = $saturday->getPartyDay(1);
        $this->assert_equal($next_party_day->format($this->dtFormat), '2011-10-27 00:00:00', 'party day after saturday is thursday');

        $next_party_day = $wednesday->getPartyDay(1);
        $this->assert_equal($next_party_day->format($this->dtFormat), '2011-10-27 00:00:00', 'party day after wednesday is thursday');

        $next_party_day = $sunday->getPartyDay(1);
        $this->assert_equal($next_party_day->format($this->dtFormat), '2011-10-27 00:00:00', 'party day after sunday is thursday');

        $next_party_day = $thursday->getPartyDay(1);
        $this->assert_equal($next_party_day->format($this->dtFormat), '2011-10-28 00:00:00', 'party day after thursday is friday');
    }

    function test_nth_party_day()
    {
        $saturday = new XDateTime('2011-10-22 00:00:00', $this->tz);

        $second_party_day = $saturday->getPartyDay(2);
        $this->assert_equal($second_party_day->format($this->dtFormat), '2011-10-28 00:00:00', 'second party day after saturday is friday');

        $third_party_day = $saturday->getPartyDay(3);
        $this->assert_equal($third_party_day->format($this->dtFormat), '2011-10-29 00:00:00', 'third party day after saturday is saturday');
    }

}
