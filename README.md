Wink Server
===========

```
openssl genrsa -out app/jwt/private.pem -aes256 4096
openssl rsa -pubout -in app/jwt/private.pem -out app/jwt/public.pem
```
