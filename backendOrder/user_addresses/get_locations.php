<?php
/**
 * User App - Get Locations (Cities & Neighborhoods)
 * GET: /user_addresses/get_locations.php
 * No auth required — public endpoint
 * 
 * Returns the list of cities grouped by delivery zone,
 * each with their neighborhoods.
 */

include "../config/constants.php";
include "../core/functions.php";

handleCorsHeaders();

if (!isRequestMethod('GET') && !isRequestMethod('POST')) {
    sendResponse(STATUS_FAIL, null, "Invalid request method");
}

// Static location data for Gaza Strip
$locations = [
    [
        'zone' => 'north',
        'zone_name_ar' => 'الشمال',
        'cities' => [
            [
                'name' => 'مدينة غزة',
                'neighborhoods' => [
                    'حي الرمال',
                    'حي الشجاعية',
                    'حي الزيتون',
                    'حي التفاح',
                    'حي الدرج',
                    'حي الصبرة',
                    'حي الشيخ رضوان',
                    'حي النصر',
                    'حي تل الهوا',
                    'حي الشيخ عجلين',
                    'مخيم الشاطئ',
                ]
            ]
        ]
    ],
    [
        'zone' => 'central',
        'zone_name_ar' => 'الوسطى',
        'cities' => [
            [
                'name' => 'مدينة دير البلح',
                'neighborhoods' => [
                    'منطقة حكر الجامع',
                    'منطقة المشاعلة',
                    'منطقة طريق النخل',
                ]
            ],
            [
                'name' => 'مخيم النصيرات',
                'neighborhoods' => [
                    'مخيم 1',
                    'مخيم 2',
                    'مخيم 5',
                    'منطقة الحساينة',
                ]
            ],
            [
                'name' => 'مخيم البريج',
                'neighborhoods' => [
                    'بلوك 1',
                    'بلوك 2',
                    'بلوك 3',
                    'بلوك 4',
                    'بلوك 5',
                    'بلوك 6',
                    'بلوك 7',
                    'بلوك 8',
                    'بلوك 9',
                    'بلوك 10',
                    'بلوك 11',
                    'بلوك 12',
                ]
            ],
            [
                'name' => 'مخيم المغازي',
                'neighborhoods' => [
                    'منطقة وسط المخيم',
                ]
            ]
        ]
    ],
    [
        'zone' => 'south',
        'zone_name_ar' => 'الجنوب',
        'cities' => [
            [
                'name' => 'مدينة خانيونس',
                'neighborhoods' => [
                    'حي الأمل',
                    'حي الكتيبة',
                    'حي الشيخ ناصر',
                    'جورة العقاد',
                    'منطقة السطر',
                    'منطقة معن',
                    'منطقة المواسي',
                    'مخيم خانيونس',
                ]
            ]
        ]
    ]
];

sendResponse(STATUS_SUCCESS, ['locations' => $locations], "Locations retrieved");
?>
