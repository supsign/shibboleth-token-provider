<?php

return [
    'private' => env('JWT_SIG_PRIVATE_KEY'),
    'public' => env('JWT_SIG_PUBLIC_KEY'),
    'mstUrl' => env('MST_URL', 'http://mst.loc')
];
