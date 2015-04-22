<?php
$cmd = "./protected/yiic migrate";
$output = stream_get_contents(popen($cmd, 'r'));
echo $output;