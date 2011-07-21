<?php

$config['imagerepository']['active_group'] = ENVIRONMENT;

$config['imagerepository']['default'] = array(
  'source' => 'filesystem',
  'path' => 'pics',
);

$config['imagerepository']['test'] = array(
  'source' => 'filesystem',
  'path' => 'testpics',
);

$config['imagerepository']['development'] = array(
  'source' => 'filesystem',
  'path' => 'pics',
);

$config['imagerepository']['production'] = array(
  'source' => 's3',
  'bucket' => 'whowentout',
);

$config['imagerepository']['phpfog'] = array(
  'source' => 's3',
  'bucket' => 'whowentoutdev',
);
