ECHO OFF
setlocal ENABLEDELAYEDEXPANSION

CLS
ECHO.
ECHO     ษอออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออป
ECHO     บ                                                             บ
ECHO     บ  Processing all examples (this may take a minute or two)    บ
ECHO     บ                                                             บ
ECHO     ศอออออออออออออออออออออออออออออออออออออออออออออออออออออออออออออผ
ECHO.

FOR /F "tokens=1,2 delims= " %%G IN ('php -v 2^>NUL') DO (
 IF %%G==PHP SET PHPVersion=%%H
 )

IF NOT DEFINED PHPVersion GOTO noPHP

ECHO     PHP binaries (%PHPVersion%) have been located
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
ECHO     Examples rendered in the "temp\" folder.
ECHO.
GOTO end

:noPHP
ECHO.
ECHO     The PHP binaries can't be found!
ECHO.
ECHO     Examples rendering has been aborded.
:end
PAUSE >NUL