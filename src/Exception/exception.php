<?php

set_error_handler([App\Exception\ExceptionHandler::class, 'convertWarningsAndNoticesToException']);
set_exception_handler([App\Exception\ExceptionHandler::class, 'handle']);
