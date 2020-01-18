<?php
    //author: pankajgupta@ameyo.com aka baba @ 9560838554
    define('SERVER_PROTOCOL', 'http');
    define('SERVER_HOST', '127.0.0.1');
    define('SERVER_PORT', '8888');
    define('CRMDB_HOST', '127.0.0.1');
    define('POSTGRES_USER', 'postgres');
    define('CRMDB_NAME', 'equitasvrm');
    define('AMEYODB_HOST', '127.0.0.1');
    define('AMEYODB_NAME', 'ameyodb');
    define('LOG_PATH_PGSQL', '/dacx/var/ameyo/dacxdata/custom_log/knowledge_base/pgsql');
    define('LOG_PATH_API', '/dacx/var/ameyo/dacxdata/custom_log/knowledge_base/api');
    define('SERVER_BASE_URL', SERVER_PROTOCOL.'://'.SERVER_HOST.':'.SERVER_PORT);
    define('DOWNLOAD_VOICEMAIL_API_URL', SERVER_BASE_URL.'/ameyowebaccess/command?command=playVoiceMail');
    define('MANUAL_DIAL_API_URL', SERVER_BASE_URL.'/ameyorestapi/voice/manualDialCustomer');
    define('UPDATE_VOICEMAIL_API_URL', SERVER_BASE_URL.'/ameyorestapi/cc/updateAllVoicemails');
    define('USER_ID', $_GET['userId']);
    define('SESSION_ID', $_GET['sessionId']);
    define('CAMPAIGN_ID', $_GET['campaignId']);
    define('CRT_OBJECT_ID', $_GET['crtObjectId']);
    //define('USER_CRT_OBJECT_ID', explode(',', str_replace(']', '', str_replace('[', '', $_GET['userCrtObjectIds'])))[0]);
    //echo USER_CRT_OBJECT_ID;die;

    $userCrtObjectId_array = explode(',', str_replace(']', '', str_replace('[', '', $_GET['userCrtObjectIds'])));
    define('USER_CRT_OBJECT_ID', $userCrtObjectId_array[0]);

    define('DUMMYDB_HOST', '127.0.0.1');
    define('DUMMYDB_NAME', 'dummyequitasvrmdb');
?>
