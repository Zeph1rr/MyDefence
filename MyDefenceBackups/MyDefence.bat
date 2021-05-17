REM ПРИМЕР СОЗДАНИЯ РЕЗЕРВНОЙ КОПИИ БАЗЫ ДАННЫХ
CLS
ECHO OFF
CHCP 1251

REM УСТАНОВКА ПЕРЕМЕННЫХ ОКРУЖЕНИЯ
SET PGBIN=C:\Program Files\PostgreSQL\13\bin
SET PGDATABASE=Moya_oborona
SET PGHOST=localhost
SET PGPORT=5432
SET PGUSER=postgres
SET PGPASSWORD=6458loik

REM СМЕНА ДИСКА И ПЕРЕХОД В ПАПКУ ИЗ КОТОРОЙ ЗАПУЩЕН ФАЙЛ
%~d0
cd %~dp0

REM ФОРМИРОВАНИЕ ИМЕНИ ФАЙЛА РЕЗЕРВНОЙ КОПИИ И ОТЧЕТА
SET DATETIME=%DATE:~6,4%-%DATE:~3,2%-%DATE:~0,2% %TIME:~0,2%-%TIME:~3,2%-%TIME:~6,2%
SET DUMPFILE=%PGDATABASE% %DATETIME%.backup
SET LOGFILE=%PGDATABASE% %DATETIME%.log
SET DUMPPATH="Backup\%DUMPFILE%"
SET LOGPATH="Backup\%LOGFILE%"

REM СОЗДАНИЕ КОПИИ
IF NOT EXIST Backup MD Backup
CALL "%PGBIN%/pg_dump.exe" --format=custom --verbose --file=%DUMPPATH% 2>%LOGPATH%

REM АНАЛИЗ КОДА ЗАВЕРШЕНИЯ
IF NOT %ERRORLEVEL%==0 GOTO Error
GOTO Successfull

REM ОБРАБОТКА ОШИБКИ
:Error
DEL %DAMPPATH%
MSG * "Ошибка при создании backup. См. backup.log"
ECHO %DATETIME% Ошибки при создании резервной копии %DUMPFILE%. См. отчет %LOGFILE%. >> backup.log
GOTO End

REM ОШИБКИ НЕТ
:Successfull
ECHO %DATETIME% Успешное создание backup %DUMPFILE% >> backup.log
GOTO End

:End