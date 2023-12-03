<?php

return [
    // 'ttl' => 43800, // in minute
    'ttl' => null,
    
    'required_claims' => [
        'iss',
        'iat',
        // 'exp',
        'nbf',
        'sub',
        'jti',
    ],
];