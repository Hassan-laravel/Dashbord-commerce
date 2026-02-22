<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL'), '/') . '/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

// 'gcs' => [
//     'driver' => 'gcs',
//     // نستخدم دالة storage_path لضمان الوصول للمجلد الصحيح
//     'key_file_path' => storage_path('app/' . env('GOOGLE_CLOUD_KEY_FILE', 'google-auth.json')),
//     'project_id'    => env('GOOGLE_CLOUD_PROJECT_ID'),
//     'bucket'        => env('GOOGLE_CLOUD_STORAGE_BUCKET'),
//     'path_prefix'   => env('GOOGLE_CLOUD_STORAGE_PATH_PREFIX', null),
//     'visibility'    => 'public',
// ],
        'gcs' => [
            'driver' => 'gcs',
            'project_id' => env('GCS_PROJECT_ID', 'laravel-gcs-project'),
    'key_file' => [
                "type" => "service_account",
                "project_id" => "laravel-gcs-project",
                "private_key_id" => "d1da4acb0b0e20480523f253379ad1b7be4d3609",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCfbIrF2+dzaibG\nf6uMAxD/XFlvuiIzRsef2XNO7jJYRnFcabXZXP+2IAEydxHo3IgApVnsUb/t3qID\nLtLyUuClh4DC2CtZecD+yMrtHMpOtUDXBo0ZQ7UIqNn4C/HQcFfmvaH4lrm+K14i\nEhNqIKgXG678OmxhHM50CT2cnLmD4w1YVb7kzoIlZyyyOvlzQx7PUTdxC2meEvEn\n5klHc1tr0yvyRRFbtX+eLZ6jijF60cnQH7bd0oSlnRmiAZ2L8FO01SdjtlKbeRbS\ntgB8Cv1HhMSc5K/DLPTMvSeLm+RS8iEDjaLR5ZHTm4JzWGYkk3ZFsH0zx9AYAYl6\n6yn3G/f3AgMBAAECggEABH4znwXmjPsl4pxAgm6nsPqyTpLJWfaZs6iWNlhCNPiv\nQzJ7fIfBcSmPvxNZ/t0QPCxsz1sydIq8uCg+q7OoPyIFf/hFqHzk8olIJsyT6wny\nrNyzZ03gMUFI+1Oi2gQAhjE7+lyNGY3xVjZ6M5h+BEC0eslOuqHsM5r7EIneNJhL\nGUFqr1LUlQ33kB+UigODp/R7rcpg+c0jwFeN3WTm5CmSA9qUCudF07rkWCns/6UB\n2NZZje9EFF2t2TSlsLsgn7jkAv5sbFCvxvgMZjqwpk6zN+PMHfOvDZBhhYOxou4r\nP9VO+NNvtfH0RWnBAymaci+SXFm87/1V0mW9IQy8wQKBgQDgyNmaKAhgGrxPLLBH\nHa/Z0DFvxwZOL6hFF+LVUr2P11FzWoEbpC1ZL9DFanVN6pKG1KlnGjR5ZOllvRou\nbg4/lJXM/GPATJiYDaZ2oSgAaOIsS8+KwNSJVZ1NHMepk3x4CGtXFaVepoUKHpgq\n8LYLBVZbjLnINwNnXpNEWqK14QKBgQC1kBtEpvEc7eaEhqF5bLbvsMtIY3aEVDRm\n1TQS8NyQhriVVC75zul8pFF5BlC+Mz5cpTlFm08Jx3UpuQln/tx8PN3ztvWC0h5w\nICWp+PN/kqHUQAhA5zTvzAcrh79rCKtGS5XHfUSzDc9WLwwgdrmTMsQ5x8Bfg4lR\nIl06viw41wKBgQC0lyibfQYdj90yDskgmW0qJOVS1CbwscESoXoPwIWjBm3dqxyG\nxIPaX1vu/vR3QLmvsTLYLmlyDeylXCOooaq40fr30N2jJOaDYpQWQqsMiTcMN2vq\nIbmfDDVwOmr+hgs9tCXotO9C961yz9mYxgK7H/KdYpXvkKMfbRALnnWSgQKBgHnx\ntO7SNWUZv9bI2dFFHEU2eAJBk4tjRuK+VcBW9702Tuk05mwv9ZAaiQIBJN/qaPsu\nmZ3PpzFJPr7sIY4wlgP3mZckDhd0aq8iWEmmBF1trbVx4Fk/MMXSQgqRnRYVd3u6\nLnoS/75HCze2V63CL/fWhAbOy70bCnJs4zMeIXN3AoGAfXEWVvTISpYMeyCg/FSB\ndQtI4x2Yi++AJ2l3W6s3TJ3KX3QD57P9B2zhy0yJLkLndKo9SU+sJw/aqMWxhezO\n61olBgXBJR5gxA3ySVzHx7G0aGM0MFzp14Rah2pvgOxYxYo3XP2qQ3eL3sM867bI\nsXCc83+v5dVSRfpLTcQ4+Ic=\n-----END PRIVATE KEY-----\n",
                "client_email" => "laravel-access@laravel-gcs-project.iam.gserviceaccount.com",
                "client_id" => "111150319775402435194",
                "auth_uri" => "https=>//accounts.google.com/o/oauth2/auth",
                "token_uri" => "https=>//oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https=>//www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https=>//www.googleapis.com/robot/v1/metadata/x509/laravel-access%40laravel-gcs-project.iam.gserviceaccount.com",
                "universe_domain" => "googleapis.com"
    ],

            // 'key_file' => [
            //     "type" => "service_account",
            //     "project_id" => "laravel-gcs-project",
            //     "private_key_id" => "35c02871708bb60ee8c3ca5656ae5da46ab6a703",
            //     "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDLHX6l6ti27/5G\nFAtnHsPf1zb4B5qW8XQWU7y9NMyrDSK8JyiFnUoMR5+gmxXH35xTplDm6oLzhCMG\nEHdJrnvLuvLFzw/9EojXcbJpQTrSto0uQ3fAV/tFKMX0lC+W+hUnneb7pPnlcEsm\nZcje4Bp7UXNviNcMnezvDtd4VLfnBnpdP1ZxnUYD8yJhfP/sbjeKlzhVJE6NWGUU\nbPjCMIMJlehbS6n3jiGC0rMm1g5RyDf2rOBz2Ie5GHsEXZz48PTUhdq7nIq4JXXc\nJKbYABZQTUhkh6xP8QH1wCoYIlk6AYorEyaJgl1VuzklEzf2rWu02GbA8RB6MXVS\nSXDrvTJRAgMBAAECggEAG8SG/WjLQssxgGQaIPlgPtg9wA/ODweoXqsI+PbgahwM\nHRvfWtDjgCVEieDZy+7igL8SfVcfGup8HUagADc13mbK7Mb/gRIJGicFhIUx5P+m\n8mWv1+BPTieiVGrzzJiYzscz/kASF89PvsqYePu7MxOyC9PxzlxCuXXWeDFgyNjn\nrVJ4cfcP4x2TnpW/tI8fpgsPNrKIyaBHy0rB0ivOTswnY4Ja48QponPEjPOraMg6\nprfGXoRV/4R/xtgQqWCXS4mLRuQbUIAijXpYDfHQQsHX1i6P0dO2OIYGwEvwrJiz\netWuFRAsl45RVhKaZNxP/rIhGWXHXIAwyDtuoMVdrwKBgQD9R7jHmqMcFI2eFDGW\nv31kviJ1SInZhsJ9MEhCsoY3zbykDBbkqBAAsC4QDPc4bCcpePwwEvwnmKd1SXyk\nnJzvSXT2Mkq8yheDgINJ9R97cILGWh1gWvHHdLwPLOpOVjYbZltSHhHkFtCLPj/8\nutvrwJ4PgJZ1Cndz+Gu9hEQULwKBgQDNS94G0dkfkoVed3U0cxP9qu8asIxbVYCX\ntpUOnG00kopUGwoDnEuke+mZGB8HX1/0crwLVQ3oCdrG6qvz56hHUI2Tj/OqKu5F\noXrjagDCD2Be7Hk9h+PXBTASOTBxZP27upWaBhVg3cyx61dlmB4btJBLWZZf4dwr\np9Xu1I0BfwKBgQDZH0O68MhEySXBzsPX293tn8TAT+fELu7JDLvVWV8PtXGyG58A\nc0YMoPtLzSGdH7HOrqVZvNymQQnE1LNiFdO+mXUHnINPJdtUYrWpj9VmJ0QkL5oh\nQLUla9/PDozpjKQjhvJgCeECa8BorXuC9tiSV/PnjC7utINESkBqHVmTBwKBgBAp\n8QlIbHf36VhOv4Opq+FFHB5V2Dc4vC81yPalilOhVhLZLiiqnaoNt46+P3MtJzjv\n765UvAuQ+xC+WVuS2cUDqqH7q4uyZaBF3o8ZQYYF/+h5ZfJaSK4dUnOc3RuQgAnP\n3weJgxVlYUCA7xioqXY0+Ud1fkl+Vv9473cmdROxAoGBAIR9qsP/Ko+Ukl9J7Z+k\nUw0a50VqCVsS43kzJbJh0mHgx4FmcAwT+FpyE/aI92Gd6dHRR+QIzL58t6tq0bBE\nnFd8gs8qEdEtHE/kIETo3tHmSfcRU+IJ/BmaIdC7ZbdI68H32Dzs/+J4mT/cInuv\nHeb+hThFLAYhDZ4ryuYLKVtM\n-----END PRIVATE KEY-----\n",
            //     "client_email" => "laravel-uploader-commerce@laravel-gcs-project.iam.gserviceaccount.com",
            //     "client_id" => "110901699909517084110",
            //     "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
            //     "token_uri" => "https://oauth2.googleapis.com/token",
            //     "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
            //     "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/laravel-uploader-commerce%40laravel-gcs-project.iam.gserviceaccount.com",
            //     "universe_domain" => "googleapis.com"
            // ],

            'bucket' => env('GCS_BUCKET', 'commerce-laravel'),
            'path_prefix' => env('GCS_PATH_PREFIX', ''),
            'storage_api_uri' => env('GCS_STORAGE_API_URI', null),
            'visibility' => 'public',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
