ECHO OFF
setlocal ENABLEDELAYEDEXPANSION

CLS

php -v 1>NUL 2>NUL
IF NOT %ERRORLEVEL% == 0 GOTO noPHP

php examples\bench.php

ECHO.
ECHO.
ECHO  Examples rendered in the "temp\" folder.
ECHO.
GOTO end

:noPHP
ECHO.
ECHO     The PHP binaries can't be found!
ECHO.
ECHO     Examples rendering has been aborded.

:end
PAUSE >NUL