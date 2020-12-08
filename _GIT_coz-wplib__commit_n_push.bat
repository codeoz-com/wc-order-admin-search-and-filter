:: Show summary of this GIT repository compared to remote

@echo off
echo ========= GIT Commit and Push Submodule ============
set /P msg="Enter Commit message: "
cd coz-wplib

:: === Commit ===
:: --all = Automatically stage files that have been modified and deleted, but new files you have not told Git about are not affected.
:: -q = Quite
:: -v = Verbose
::
:: This command will open a VIM editor to enter commit message
:: - Enter commit message
:: - Press ESC and ":wq" to save and editor
:: - Press ESC and ":q!" to exit WITHOUT saving
git commit -v --all -m "%msg%"

echo Press "y" to continue, or "n" to exit
choice /c ny /n
if %errorlevel%==1 goto Finished
::echo Press any key to continue with PUSH, or CTR-C to terminate
::pause >null

:: === Push ===
::git push origin -v --all
git push https://codeoz-com:Hospw100@github.com/codeoz-com/coz-wplib.git -v --all

pause
:Finished
cd ..