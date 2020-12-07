
:: Show summary of this GIT repository compared to remote

@echo off
echo ========= GIT Push Submodule ============
@echo on
cd coz-wplib
git push https://codeoz-com:Hospw100@github.com/codeoz-com/coz-wplib.git -v --all
::git push -v --all
pause
cd ..