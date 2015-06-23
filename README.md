# test
1. link nginx conf:

    ln -s -t /etc/nginx/sites-enabled $(pwd)/install/todo.conf
2. reload nginx
3. create database tododb;
4. install tables:

    mysql -uroot -p --database tododb < install/tables.sql

5. register new user or use test@tess.tt : 123qwe to login