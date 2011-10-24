<?php

/**
 * @return DateTime
 */
function actual_time()
{
    return new DateTime('now', new DateTimeZone('UTC'));
    return make_gmt($dt);
}
