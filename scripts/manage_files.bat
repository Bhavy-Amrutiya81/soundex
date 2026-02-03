@echo off
cls
title Soundex File Management System

echo ========================================
echo    Soundex Project File Manager
echo ========================================
echo.

:menu
echo Select an option:
echo 1. Organize files into proper directories
echo 2. Generate file manifest
echo 3. Clean up temporary files
echo 4. Show current file structure
echo 5. Backup project files
echo 6. Restore from backup
echo 7. Exit
echo.

set /p choice="Enter your choice (1-7): "

if "%choice%"=="1" goto organize
if "%choice%"=="2" goto manifest
if "%choice%"=="3" goto cleanup
if "%choice%"=="4" goto structure
if "%choice%"=="5" goto backup
if "%choice%"=="6" goto restore
if "%choice%"=="7" goto exit

echo Invalid choice. Please try again.
echo.
goto menu

:organize
echo.
echo Organizing files...
php utilities\file_manager.php organize
echo Organization complete!
echo.
pause
goto menu

:manifest
echo.
echo Generating file manifest...
php utilities\file_manager.php manifest
echo Manifest generated successfully!
echo.
pause
goto menu

:cleanup
echo.
echo Cleaning up temporary files...
php utilities\file_manager.php cleanup
echo Cleanup complete!
echo.
pause
goto menu

:structure
echo.
echo Current file structure:
php utilities\file_manager.php structure
echo.
pause
goto menu

:backup
echo.
echo Creating backup...
if not exist "backups" mkdir backups
set datetime=%date:~-4%-%date:~4,2%-%date:~7,2%_%time:~0,2%-%time:~3,2%
set backupname=backups\soundex_backup_%datetime:.=%.zip
powershell Compress-Archive -Path . -DestinationPath "%backupname%" -Force
echo Backup created: %backupname%
echo.
pause
goto menu

:restore
echo.
echo Available backups:
dir backups\*.zip
echo.
set /p backupfile="Enter backup filename: "
if exist "backups\%backupfile%" (
    echo Restoring from backup...
    powershell Expand-Archive -Path "backups\%backupfile%" -DestinationPath . -Force
    echo Restore complete!
) else (
    echo Backup file not found!
)
echo.
pause
goto menu

:exit
echo.
echo Goodbye!
exit /b 0