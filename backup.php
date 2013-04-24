<?php

error_reporting(-1);
include 'paths.php';

require path('sys').'core.php';


$bak = DB::backup();

$file = createZIP($bak);
sendBackup($file);


