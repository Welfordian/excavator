<?php

if (!file_exists(base_path() . '/.env')) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $env = implode('', array_map(
            function ($v, $k) { return sprintf("%s%s\n", $k, $v); },
            $_POST,
            array_keys($_POST)
        ));

        $env.= "BROADCAST_DRIVER=log\nCACHE_DRIVER=file\nQUEUE_CONNECTION=sync\nSESSION_DRIVER=file\nSESSION_LIFETIME=120\n\nREDIS_HOST=127.0.0.1\nREDIS_PASSWORD=null\nREDIS_PORT=6379\n\nMAIL_DRIVER=smtp\nMAIL_HOST=smtp.mailtrap.io\nMAIL_PORT=2525\nMAIL_USERNAME=null\nMAIL_PASSWORD=null\nMAIL_ENCRYPTION=null\n\nPUSHER_APP_ID=\nPUSHER_APP_KEY=\nPUSHER_APP_SECRET=\nPUSHER_APP_CLUSTER=mt1\n\nMIX_PUSHER_APP_KEY=\"\${PUSHER_APP_KEY}\"\nMIX_PUSHER_APP_CLUSTER=\"\${PUSHER_APP_CLUSTER}\"\n";


        file_put_contents(base_path() . '/.env', $env);

        \Illuminate\Support\Facades\Artisan::call('key:generate');

        header('Location: '.$_SERVER['HTTP_ORIGIN']);

        exit;
    } else {
        echo file_get_contents(base_path() . '/resources/views/setup/index.html');

        die();
    }
}
