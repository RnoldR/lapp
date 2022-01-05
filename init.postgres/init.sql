CREATE ROLE flyspray WITH LOGIN PASSWORD '<put your flyspray passsword here>';
CREATE DATABASE flyspray OWNER flyspray;
GRANT ALL PRIVILEGES ON DATABASE flyspray TO flyspray;
