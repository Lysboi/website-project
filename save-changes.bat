@echo off
echo Git changes saving started...
git add .
IF %ERRORLEVEL% NEQ 0 (
    echo Error during git add
    pause
    exit /b %ERRORLEVEL%
)

git commit -m "Auto save: %date% %time%"
IF %ERRORLEVEL% NEQ 0 (
    echo Error during git commit
    pause
    exit /b %ERRORLEVEL%
)

git push
IF %ERRORLEVEL% NEQ 0 (
    echo Error during git push
    pause
    exit /b %ERRORLEVEL%
)

echo Done!
pause