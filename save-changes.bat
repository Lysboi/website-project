@echo off
echo Git changes saving started...
git add .
git commit -m "Auto save: %date% %time%"
git push
echo Done!
pause