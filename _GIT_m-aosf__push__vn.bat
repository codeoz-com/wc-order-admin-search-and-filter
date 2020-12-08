
:: Show summary of this GIT repository compared to remote

@echo off
echo ========= GIT Push Submodule ============
@echo on
cd m-aosf
git push -v --tags origin release/v1.0:release/v1.0
::git push -v --tags --all
::git push -v --all
pause
cd ..