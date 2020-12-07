
:: Commit and push

@echo off
echo ========= GIT Commit and Push main Submodule ============
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
:: --set-upstream = For every branch that is up to date or successfully pushed, add upstream (tracking) reference, used by argument-less git-pull[1] and other commands.
:: --tags All refs under refs/tags are pushed, in addition to refspecs explicitly listed on the command line.
::git push origin -v --all
git push -v --tags --set-upstream origin release/v1.0:release/v1.0

pause
:Finished
cd ..
