<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Package driver
    |--------------------------------------------------------------------------
    |
    | The package supports different drivers for translation management.
    |
    | Supported: "file", "database"
    |
    */
    'driver' => 'file',

    /*
    |--------------------------------------------------------------------------
    | Route group configuration
    |--------------------------------------------------------------------------
    |
    | The package ships with routes to handle language management. Update the
    | configuration here to configure the routes with your preferred group options.
    |
    */
    'route_group_config' => [
        'middleware' => ['web','auth'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Translation methods
    |--------------------------------------------------------------------------
    |
    | Update this array to tell the package which methods it should look for
    | when finding missing translations.
    |
    */
    'translation_methods' => ['trans', '__'],

    /*
    |--------------------------------------------------------------------------
    | Scan paths
    |--------------------------------------------------------------------------
    |
    | Update this array to tell the package which directories to scan when
    | looking for missing translations.
    |
    */
    'scan_paths' => [app_path(), resource_path()],

    /*
    |--------------------------------------------------------------------------
    | UI URL
    |--------------------------------------------------------------------------
    |
    | Define the URL used to access the language management too.
    |
    */
    'ui_url' => 'languages',

    /*
    |--------------------------------------------------------------------------
    | Database settings
    |--------------------------------------------------------------------------
    |
    | Define the settings for the database driver here.
    |
    */
    'database' => [

        'connection' => '',

        'languages_table' => 'languages',

        'translations_table' => 'translations',
    ],

    /* scan_mode = file , directory, copyFile, copyDirectory */
    'scan_groups'=> [
        'system-lang'=>[
            '0'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'shared',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '1'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Helpers',
                'type'=>'helper',
                'scan_mode'=>'directory'
            ],         
            '2'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'HomeController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '3'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'System'.DIRECTORY_SEPARATOR.'CheckUpdateController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '4'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'System'.DIRECTORY_SEPARATOR.'CronJobController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '5'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'System'.DIRECTORY_SEPARATOR.'NativeAPIController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '6'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'System'.DIRECTORY_SEPARATOR.'SettingsController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '7'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'System'.DIRECTORY_SEPARATOR.'SocialAppsController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '8'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'auth',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '9'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'check-update',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '10'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'settings',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '11'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'social-apps',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '12'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'native-api',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '12'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'cron-job',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '13'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'design',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '14'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Services'.DIRECTORY_SEPARATOR.'Custom',
                'type'=>'services',
                'scan_mode'=>'directory'
            ],
            '15'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Services'.DIRECTORY_SEPARATOR.'Payment',
                'type'=>'services',
                'scan_mode'=>'directory'
            ]

        ],
        'dashboard-lang'=>[
            '0'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'DashboardController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '1'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'dashboard.blade.php',
                'type'=>'view',
                'scan_mode'=>'file'
            ],
        ],       
        'member-lang'=>[
            '0'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'Member.php',
                'type'=>'controller',
                'scan_mode'=>'file'
             ],
            '1'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'PaymentController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
             ],
             '3'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'member',
                'type'=>'view',
                'scan_mode'=>'directory'
             ]
        ],
        'subscription-lang'=>[
            '0'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'SubscriptionController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
             ],
             '1'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'subscription',
                'type'=>'view',
                'scan_mode'=>'directory'
             ],
             '2'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'user',
                'type'=>'view',
                'scan_mode'=>'directory'
             ]
        ],
        'landing'=>[
            '0'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'Landing.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '1'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'landing',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '1'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'landing'.DIRECTORY_SEPARATOR.'policy',
                'type'=>'view',
                'scan_mode'=>'directory'
            ]
        ],        
        'seo-tools-lang'=>[
            '0'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'SEO_tools'.DIRECTORY_SEPARATOR.'AnalysisToolsController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '1'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'SEO_tools'.DIRECTORY_SEPARATOR.'CodeMinifierController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '2'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'SEO_tools'.DIRECTORY_SEPARATOR.'DomainAnalysisController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '3'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'SEO_tools'.DIRECTORY_SEPARATOR.'KeywordAnalysisController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '4'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'SEO_tools'.DIRECTORY_SEPARATOR.'IpAnalysisController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '5'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'SEO_tools'.DIRECTORY_SEPARATOR.'KeywordTrackingController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '6'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'SEO_tools'.DIRECTORY_SEPARATOR.'RankIndexController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '7'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'SEO_tools'.DIRECTORY_SEPARATOR.'SecurityToolsController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '8'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'SEO_tools'.DIRECTORY_SEPARATOR.'UrlShortnerController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '9'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'SEO_tools'.DIRECTORY_SEPARATOR.'UtilitiesController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '10'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'SEO_tools'.DIRECTORY_SEPARATOR.'VisitorController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '11'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'SEO_tools'.DIRECTORY_SEPARATOR.'WebsiteController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '12'=> [
                'path'=>app_path().DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'SEO_tools'.DIRECTORY_SEPARATOR.'LinkAnalysisController.php',
                'type'=>'controller',
                'scan_mode'=>'file'
            ],
            '13'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'code-minifier',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '14'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'keyword-tracking',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '15'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'security-tools',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '16'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'url-shortner',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '17'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'utilities',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '18'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'analysis-tools'.DIRECTORY_SEPARATOR.'visitor',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '19'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'analysis-tools'.DIRECTORY_SEPARATOR.'rank-analysis',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '20'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'analysis-tools'.DIRECTORY_SEPARATOR.'rank-analysis',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '21'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'analysis-tools'.DIRECTORY_SEPARATOR.'link-analysis',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '22'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'analysis-tools'.DIRECTORY_SEPARATOR.'keyword-analysis',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '23'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'analysis-tools'.DIRECTORY_SEPARATOR.'ip-analysis',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '24'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'analysis-tools'.DIRECTORY_SEPARATOR.'domain-analysis',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '25'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'analysis-tools'.DIRECTORY_SEPARATOR.'website',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '26'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'analysis-tools'.DIRECTORY_SEPARATOR.'website'.DIRECTORY_SEPARATOR.'report',
                'type'=>'view',
                'scan_mode'=>'directory'
            ],
            '26'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'analysis-tools'.DIRECTORY_SEPARATOR.'index.blade.php',
                'type'=>'view',
                'scan_mode'=>'file'
            ],
            '27'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'analysis-tools'.DIRECTORY_SEPARATOR.'social-analysis.blade.php',
                'type'=>'view',
                'scan_mode'=>'file'
            ],
            '28'=> [
                'path'=>resource_path('views').DIRECTORY_SEPARATOR.'seo-tools'.DIRECTORY_SEPARATOR.'analysis-tools'.DIRECTORY_SEPARATOR.'social-network-analysis-index.blade.php',
                'type'=>'view',
                'scan_mode'=>'file'
            ]


        ],        

    ]
];
