<?php

http_response_code(200);
header("Content-type: application/json");
print_r(json_encode(array(
    "status" => "ok",
    "message" => "hello world"
)));
exit;