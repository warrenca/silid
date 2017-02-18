CREATE DATABASE silid;
CREATE USER 'silid_user'@'%' IDENTIFIED BY 'silid@701search';
GRANT ALL ON silid.* TO 'silid_user'@'%';
FLUSH PRIVILEGES;
