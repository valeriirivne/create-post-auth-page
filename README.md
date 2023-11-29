In this project I migrated from the XAMMP Apache distribution to docker platform (ourmainapp -the name of database inside my local computer)!!!

-   docker pull mysql
-   docker-compose up -d
-   docker exec -it ourmainapp-mysql-1 mysql -u root -p
    -   MariaDB [(none)]> CREATE DATABASE ourlaravelapp;
    -   Query OK, 1 row affected (0.000 sec)

To run this app you need `php run dev`;
On github I called this app: `create-post-auth-page` (https://github.com/valeriirivne/create-post-auth-page)
