<?php

    // Todos los posibles filtros de los posts
    return [
        "filter" => [
            "topViews" => [
                "key" => "numViews",
                "order" => "DESC"
            ],
            "lessViews" => [
                "key" => "numViews",
                "order" => "ASC"
            ],
            "topLikes" => [
                "key" => "likes",
                "order" => "DESC"
            ],
            "lessLikes" => [
                "key" => "likes",
                "order" => "ASC"
            ],
            "topComents" => [
                "key" => "coment",
                "order" => "DESC"
            ],
            "lessComents" => [
                "key" => "coment",
                "order" => "ASC"
            ],
        ]
    ];

?>