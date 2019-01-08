#!/bin/sh
echo "[+] Creating flag user and flag table."
mysql -h 127.0.0.1 -uroot -p <<'SQL'
CREATE DATABASE  IF NOT EXISTS `flag` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `flag`;

DROP TABLE IF EXISTS `flag`;
CREATE TABLE `flag` (
  `flag` VARCHAR(100)
);


CREATE USER 'm4st3r_ov3rl0rd'@'localhost';
GRANT USAGE ON *.* TO 'm4st3r_ov3rl0rd'@'localhost';
GRANT SELECT ON `flag`.* TO 'm4st3r_ov3rl0rd'@'localhost';
SQL

echo -n "[+] Please input the flag:"
read flag

mysql -h 127.0.0.1 -uroot -p <<SQL
INSERT INTO flag.flag VALUES ('$flag');
SQL

echo "[+] Flag was succesfully backed up to mysql!"
