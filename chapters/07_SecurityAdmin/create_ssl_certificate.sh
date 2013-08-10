mkdir /etc/httpd/ssl
cd  /etc/httpd/ssl
openssl req -new -x509 -days 3650 -sha1 -newkey rsa:1024 \ 
-nodes -keyout server.key -out server.crt \ 
-subj '/O=Company/OU=Department/CN=www.example.com' 

