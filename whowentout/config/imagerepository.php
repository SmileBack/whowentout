<?php

$config['imagerepository']['active_group'] = ENVIRONMENT;

$config['imagerepository']['development'] = array(
  'source' => 'filesystem',
  'path' => 'pics',
);

$config['imagerepository']['whowasout'] = array(
  'source' => 's3',
  'bucket' => 'whowentoutdev',
);

$config['imagerepository']['whowasout'] = array(
  'source' => 's3',
  'bucket' => 'whowasoutpics',
);

$config['imagerepository']['default'] = array(
  'source' => 'filesystem',
  'path' => 'pics',
);

$config['imagerepository']['test'] = array(
  'source' => 'filesystem',
  'path' => 'testpics',
);

$config['imagerepository']['production'] = array(
  'source' => 's3',
  'bucket' => 'whowentout',
);
