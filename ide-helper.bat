@echo off

php artisan ide-helper:meta
php artisan ide-helper:models -W
php artisan ide-helper:generate
