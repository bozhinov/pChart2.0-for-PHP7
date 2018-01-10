ECHO OFF
setlocal ENABLEDELAYEDEXPANSION

CLS
ECHO.
ECHO     ษอออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออป
ECHO     บ                                                                     บ
ECHO     บ  Processing all examples (this may takes 1-2 minutes)               บ
ECHO     บ                                                                     บ
ECHO     ศอออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออผ
ECHO.

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
ECHO Processing examples : >temp\errors.log

REM SET /P Var="   Progress : "<NUL

FOR %%f IN (examples\example*.*) DO (
   set t=%%f
   if !t:~-3! == php (
     SET /P Var=þ<NUL
     ECHO %%f >>temp\errors.log
     php -q "%%f" 2>>&1>>temp\errors.log
    )
)

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