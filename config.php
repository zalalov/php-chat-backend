<?php

/**
 * Configuration
 */
return [
    "db" => [
        "host" => "localhost",
        "user" => "user",
        "password" => "password",
        "database" => "db"
    ],
    "routes" => [
        "/api/message/history" => [],
        "/api/message/new" => [],
        "/api/message/send" => [
            "params" => [
                "user_id" => "/[0-9]+/"
            ]
        ],
    ]
];