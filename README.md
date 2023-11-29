In this project I migrated from the XAMMP to docker container (ourmainapp)!!!

-   docker pull mysql
-   docker-compose up -d
-   docker exec -it ourmainapp-mysql-1 mysql -u root -p
    -   MariaDB [(none)]> CREATE DATABASE ourlaravelapp;
    -   Query OK, 1 row affected (0.000 sec)

To run this app you need `php run dev`
