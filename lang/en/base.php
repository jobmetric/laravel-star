<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base Star Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during Star for
    | various messages that we need to display to the user.
    |
    */

    "exceptions" => [
        "invalid_star_actor" => "Unable to identify the star actor. Either a related model (starred_by) or a device ID must be provided.",
        "max_rate" => "Rate must be less than or equal to :max_rate, :rate given",
        "min_rate" => "Rate must be greater than or equal to :min_rate, :rate given",
    ],

    "entity_names" => [
        "star" => "Star",
    ],

    'events' => [
        'star_added' => [
            'title' => 'Star Added',
            'description' => 'This event is triggered when a star rating is added.',
        ],

        'star_removed' => [
            'title' => 'Star Removed',
            'description' => 'This event is triggered when a star rating is removed.',
        ],

        'star_removing' => [
            'title' => 'Star Removing',
            'description' => 'This event is triggered when a star rating is being removed.',
        ],

        'star_updated' => [
            'title' => 'Star Updated',
            'description' => 'This event is triggered when a star rating is updated.',
        ],

        'star_updating' => [
            'title' => 'Star Updating',
            'description' => 'This event is triggered when a star rating is being updated.',
        ],
    ],

];
