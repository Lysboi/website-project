@echo off
echo Git changes saving started...
git add .

git commit -m "Auto save: %date% %time%"
if %errorlevel% equ 0 (
    echo Changes committed successfully!
) else (
    if %errorlevel% equ 1 (
        echo No changes to commit
    ) else (
        echo Error during commit
    )
)

git push --set-upstream origin master
if %errorlevel% neq 0 (
    echo Error during push
    pause
    exit /b %errorlevel%
)

echo Done! All changes saved to GitHub
pause