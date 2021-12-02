<?php

const COURSE_CONTENT_BASE_PATH = "course_content/";
const IMAGE_PREFIX_PATH = "/images";
const SOUND_PREFIX_PATH = "/sounds";
const PDF_PREFIX_PATH = "/pdfs";

const CATEGORY_ORIENTATION = 1;
const CATEGORY_REFRESHMENT = 2;

const COURSE_STATUS = [
    1 => 'INTERNAL',
    0 => 'EXTERNAL'
];

const COURSE_CONTENT_TYPE = [
    'PLAIN_TEXT' => 1,
    'LINK' => 2,
    'IMAGE' => 3,
    'VIDEO' => 4,
    'SOUND' => 5,
    'PDF' => 6
];

const COURSE_CONTENT_TYPE_KEY = [
    1 => 'Plain Text',
    2 => 'Link',
    3 => 'Image',
    4 => 'Video',
    5 => 'Sound',
    6 => 'PDF'
];

const CLASS_TYPE_KEY = [
    1 => 'Online',
    2 => 'OnClass'
];

const CLASS_TYPE = [
    'ONLINE' => 1,
    'ONCLASS' => 2
];

const QUESTION_TYPE = [
    'OPEN' =>[
        'value' => 1,
        'key' => 'open'
    ],
    'CLOSE' =>[
        'value' => 2,
        'key' => 'close'
    ],
    'MULTIPLE-CHOICE' =>[
        'value' => 3,
        'key' => 'multipleChoice'
    ]
];

//Use all action type of training include into this constant to avoid conflict type in history table
//value in this constant must be increase number
const TRAINING_CONSTANT_TYPE = [
    //Trainee status have been added to Enrollment (Training Event)
    'TRAINEE_STATUS_FROM_ADMIN' => 1,
    'TRAINEE_STATUS_FROM_REQUEST_JOIN' => 2,

    //Trainee status on request join Enrollment (Training Event)
    'TRAINEE_REQUEST_JOIN_STATUS_PENDING' => 3,//Mean trainee request join
    'TRAINEE_REQUEST_JOIN_STATUS_APPROVED' => 4,
    'TRAINEE_REQUEST_JOIN_STATUS_REJECTED' => 5,
    'TRAINEE_REQUEST_JOIN_STATUS_CANCEL' => 6,

    //Trainee status on progress in training from Enrollment (Training Event)
    'TRAINEE_PROGRESS_IN_TRAINING' => 7,
    'TRAINEE_PROGRESS_FINISH_TRAINING' => 8,
    'TRAINEE_PROGRESS_FINISH_EXAM' => 9,
    'TRAINEE_PROGRESS_IN_COMPLETE_TRAINING' => 10,
    'TRAINEE_PROGRESS_COMPLETED_TRAINING' => 11,
    'TRAINEE_PROGRESS_OVERDUE' => 12,
    'TRAINEE_PROGRESS_RE_TAKEN' => 13,

    //Enrollment (Training Event) Progress
    'ENROLLMENT_PROGRESS_PENDING' => 14,
    'ENROLLMENT_PROGRESS_ON_TRAINING' => 15,
    'ENROLLMENT_PROGRESS_COMPLETED' => 16,

    //Trainee History
    'TRAINEE_TAKE_TRAINING' => 100,
    'TRAINEE_TAKE_FINISH_TRAINING' => 101,
    'TRAINEE_TAKE_EXAM' => 102,

];