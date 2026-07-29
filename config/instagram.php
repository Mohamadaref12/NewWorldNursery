<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Meta / Instagram Graph API
    |--------------------------------------------------------------------------
    |
    | Create an app at https://developers.facebook.com and add the Instagram
    | Graph API product. Use Facebook Login with a Professional IG account
    | linked to a Facebook Page.
    |
    */
    'app_id' => env('INSTAGRAM_APP_ID'),
    'app_secret' => env('INSTAGRAM_APP_SECRET'),
    'graph_version' => env('INSTAGRAM_GRAPH_VERSION', 'v21.0'),
    'redirect_uri' => env('INSTAGRAM_REDIRECT_URI', env('APP_URL').'/admin/instagram/callback'),
    'scopes' => [
        'instagram_basic',
        'pages_show_list',
        'pages_read_engagement',
    ],
    'default_sync_limit' => (int) env('INSTAGRAM_SYNC_LIMIT', 12),
];
