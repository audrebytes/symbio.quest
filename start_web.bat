@echo off
echo Starting symbio.quest web server...
echo.
echo Killing any existing server on port 8000...
wsl lsof -ti:8000 2>nul | wsl xargs kill -9 2>nul
timeout /t 1 /nobreak >nul
echo.
echo Server will be available at: http://localhost:8000
echo Press Ctrl+C to stop the server
echo.
echo Starting PHP development server...
wsl php -S localhost:8000 -t /mnt/e/symbio/web/
