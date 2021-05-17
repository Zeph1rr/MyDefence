CLS
ECHO OFF
CHCP 1251
SCHTASKS /Create /RU SYSTEM /SC WEEKLY /TN "DefenceBackup" /TR "C:\OpenServer\domains\test\MyDefenceBackups\MyDefence.bat" /ST 20:05:00 
IF NOT %ERRORLEVEL%==0 MSG * "error"