## About workwize

Small Demo app.
- Extract to working dir and setup as usual
- chmod +x perm_fix.sh and run sudo ./perm_fix.sh
 (this fixes file \ directory permissions in linux)
- edit the env file, set correct database credentials
- run migration and seed

This demo uses Spatie permission for role\permission management.
The user seeder creates three users 1 for developer, 1 for supplier and one for customer

- admin@workwize.com
- supplier@workwize.com
- customer@workwize.com

#### Same passwords for all users
- password: 1234

#### This project utilises sanctum
Meaning that if you have issues logging in, please check the "statefull" env var 
The domain used for development is local.workwize.com (on wsl ubuntu) so 
the statefull domain added to the sanctum config is .workwize.com

#### nginx config
a simple non https virtual server config for local.workwize.com please adapt paths accordingly
it is for php-fpm 8.3, so you might have to change that to your php version. 
Try to keep it 8.1 or above.



