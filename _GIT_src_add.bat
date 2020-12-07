
:: Add the main "src" submodule

@echo off
echo ========= GIT Add "src/" Submodule ============
@echo on

:: OPTION 1
:: ENABLE THIS to add specific version/release
git submodule add -b release/v1.0 -f https://github.com/codeoz-com/wc-order-admin-search-and-filter-module.git src
git submodule update --init src

:: OPTION 2
:: ENABLE THIS to add current(master) repository
::git submodule add -f https://github.com/codeoz-com/wc-order-admin-search-and-filter-module.git src
::git submodule update --init src

pause
cd ..