CREATE DATABASE  IF NOT EXISTS `urlstorage` CHARACTER SET UTF8;

CREATE USER django@localhost IDENTIFIED BY 'shiaisthebest';
CREATE USER xss@localhost IDENTIFIED BY 'shiaisthebest';

GRANT ALL PRIVILEGES ON urlstorage.* TO django@localhost;
GRANT ALL PRIVILEGES ON urlstorage.urlstorage_feedback TO xss@localhost;

FLUSH PRIVILEGES;

