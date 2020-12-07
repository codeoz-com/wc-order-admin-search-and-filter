
:: Add "coz-wplib" submodule

@echo off
echo ========= GIT Push Submodule ============
@echo on

:: OPTION 1
:: ENABLE THIS to add specific version/release of "coz-wplib"
::git submodule add -b release/v1.0 -f https://github.com/codeoz-com/coz-wplib.git coz-wplib1
::git submodule update --init coz-wplib1

:: OPTION 2
:: ENABLE THIS to add current(master) repository to "coz-wplib0"
git submodule add -f https://github.com/codeoz-com/coz-wplib.git coz-wplib0
git submodule update --init coz-wplib0

pause
cd ..