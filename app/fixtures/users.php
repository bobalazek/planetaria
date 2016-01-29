<?php

return array(
    array(
        'username' => 'borut',
        'email' => 'bobalazek124@gmail.com',
        'plainPassword' => 'test',
        'profile' => array(
            'firstName' => 'Borut',
            'lastName' => 'Balazek',
            'gender' => 'male',
            'birthdate' => '03-09-1992',
            'avatarImage' => '007.png',
        ),
        'roles' => array(
            'ROLE_SUPER_ADMIN',
            'ROLE_ADMIN',
        ),
        'country' => array(
            'name' => 'Corcoland',
            'slug' => 'corcoland',
            'description' => 'The land of unlimited freedom!',
        ),
        'town' => array(
            'name' => 'Corcoa',
            'slug' => 'corcoa',
            'description' => 'The capital of Corcoland.',
        ),
        'startingCoordinates' => array(5, 5),
    ),
    array(
        'username' => 'ana',
        'email' => 'anakociper124@gmail.com',
        'plainPassword' => 'test',
        'profile' => array(
            'firstName' => 'Ana',
            'lastName' => 'Kociper',
            'gender' => 'female',
            'birthdate' => '14-07-1993',
        ),
        'roles' => array(
            'ROLE_SUPER_ADMIN',
            'ROLE_ADMIN',
        ),
        'country' => array(
            'name' => 'Ananastan',
            'slug' => 'ananastan',
            'description' => 'The country of ananases.',
        ),
        'town' => array(
            'name' => 'Ananasland',
            'slug' => 'ananasland',
            'description' => 'The town of ananases.',
        ),
        'startingCoordinates' => array(5, 0),
    ),
);
