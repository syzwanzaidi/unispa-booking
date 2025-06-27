<?php

return [

    'defaults' => [
        'guard' => 'web', // Default guard for regular users
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users', // Links to the 'users' provider below
        ],
        // Admin Guard: Uses session-based authentication for admins
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins', // This links to the 'admins' provider defined below
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent', // Uses Eloquent ORM for user retrieval
            'model' => App\Models\User::class, // Specifies the User model
        ],
        // Admin Provider: Uses Eloquent ORM for admin retrieval
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class, // Specifies the Admin model
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens', // Table for password reset tokens
            'expire' => 60,
            'throttle' => 60,
        ],
        // Optional: Password reset configuration for admins (can use the same table)
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
