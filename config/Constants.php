<?php
defined("INACTIVE") or define("INACTIVE", 0);
defined("ACTIVE") or define("ACTIVE", 1);
defined("REJECTED") or define("REJECTED", 2);
defined("DELETED") or define("DELETED", 3);
defined("SOLD") or define("SOLD", 4);


defined("DATE_FORMAT") or define("DATE_FORMAT", 'd M Y');
defined("TIME_FORMAT") or define("TIME_FORMAT", 'g:i a');
defined("SQL_DATE_FORMAT") or define("SQL_DATE_FORMAT", 'Y-m-d');
defined("SIMPLE_DATE_FORMAT") or define("SIMPLE_DATE_FORMAT", 'm/d/Y');
defined("DATE_TIME_FORMAT") or define("DATE_TIME_FORMAT", 'd M Y,H:i A');



//status code
defined("ACCESS_DENIED") or define("ACCESS_DENIED", 403); //permission
defined("FATAL_ERROR") or define("FATAL_ERROR", 500);
defined('SERVER_ERROR') or define('SERVER_ERROR', 500);
defined('AUTH_ERROR') or define('AUTH_ERROR', 401);
defined('BLOCK_ERROR') or define('BLOCK_ERROR', 406);
defined("PARAM_MISSING") or define("PARAM_MISSING", 418);
defined("EXCEPTION_500") or define("EXCEPTION_500", 500);
defined('TOKEN_EXPIRED') or define('TOKEN_EXPIRED', 401);
defined('NOT_EXIST') or define('NOT_EXIST', 402);
defined('LINK_EXPIRED') or define('LINK_EXPIRED', 415);
defined('INCORRECT_PARAM') or define('INCORRECT_PARAM', 405);
defined('EXISTS') or define('EXISTS', 403);
defined('INVALID_IMAGE_EXTENSION') or define('INVALID_IMAGE_EXTENSION', 406);
defined('INVALID') or define('INVALID', 406);
defined('SUCCESS') or define('SUCCESS', 200);
defined('REQUIRED') or define('REQUIRED', 201);

defined('FAIL') or define('FAIL', 500);
defined('FAILED') or define('FAILED', 400); //invalid
defined('UNPROCESSABLE_ENTITY') or define('UNPROCESSABLE_ENTITY', 422); //validation error

defined('ITEM_PER_PAGE') or define('ITEM_PER_PAGE', 10);
defined('HOME_PER_PAGE_ITEM') or define('HOME_PER_PAGE_ITEM', 5);


//  Defining referral points for referring one user
defined('REFERRAL_POINTS') or define('REFERRAL_POINTS', 10);







