## About myshop

## build using reactjs, vite, inertia, primereact, Spatie permissions, Sanctum, TailWind

Small Demo app.
- Assuming a default installation using php8.2+ nginx & mysql server installed on the same server.  
- clone to working dir and setup as usual
- chmod +x perm_fix.sh and run sudo ./perm_fix.sh
 (this should fix file \ directory permissions in linux)
- copy the .env.example to .env and change vars according your system
- php artisan key:generate
- php artisan optimize
- npm install
- npm run build (this builds your assets using vite)
- run migration and seed

This demo uses Spatie permission for role\permission management.
The user seeder creates three users 1 for admin, 1 for supplier and one for customer

- admin@myshop.com
- supplier@myshop.com
- customer@myshop.com

### Same password for all users
- password: 1234

### This project utilises sanctum
Meaning that if you have issues logging in, please check the "statefull" env var 
The domain used for development is local.myshop.com (on wsl ubuntu) so 
the statefull domain added to the sanctum config is .myshop.com

### nginx config
a simple non https virtual server config for local.myshop.com please adapt paths accordingly
it is for php-fpm 8.2/8.3, so you might have to change that to your php version. 
Try to keep it 8.2 or above.

# important
Don't forget to update your hosts file(s)
if on wsl also update the host file in windows\system32\drivers\etc\hosts

## It is ugly.. Yea I know.
As I mentioned before, I only started with react 5 days ago, bite me!
