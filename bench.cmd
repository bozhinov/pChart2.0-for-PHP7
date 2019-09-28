ECHO OFF
setlocal ENABLEDELAYEDEXPANSION

CLS

php -v 1>NUL 2>NUL
IF %ERRORLEVEL% == 0 GOTO getVersion
GOTO noPHP

:getVersion
FOR /F "tokens=1,2 delims= " %%G IN ('php -v') DO (
 IF %%G==PHP SET PHPVersion=%%H
 )

:render
ECHO     The PHP binaries (%PHPVersion%) have been located
ECHO.
ECHO Processing examples :

php examples\bench.php

ECHO.
ECHO.
ECHO     All the example have been rendered in the following folder :
ECHO.
ECHO       temp\
GOTO end

:noPHP

ECHO     The PHP binaries can't be found. We strongly advise you to put it in
ECHO     the system path variable.
ECHO.
ECHO     Examples rendering has been aborded.
:end
PAUSE >NUL