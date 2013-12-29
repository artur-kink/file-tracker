file-tracker
============

This is a project used to keep track of files across multiple systems.
The project is in two parts:
The client that runs on computers and reports what files it has. Written in c++ using boost for cross platform http requests and file system access.
The server that is used to manage the clients and see what files they have. Written in php and using mysql.
