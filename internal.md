This doc contains informal internal to the project

# Apache

## Restart

```
systemctl restart httpd
```

## Web Sockets

The Ratchet server can't open a web socket in secure mode (wss). So the architecture is:
1. httpdThe scrptMinesweeper.js opens the wss://minesweeper.fr/wss secured web socket
1. httpd exposes the secured web socket under the /wss path
1. It redirects the web socket flow to the WS/server.php
1. The WS/server.php exposes a non secured (ws) web socket on the 8080 port.

To do this, the Virtual Host 443 contains this configuration:

```
ProxyPass "/wss" "ws://localhost:8080"
```

# PHP

## Install php

```
dnf install php-8.0.30
```

```
dnf install php-cli-8.0.30
```

```
dnf install php-mysqlnd-8.0.30 -y
```

## php conf

Configuration file for the web: `/etc/php.ini`
Configuration file for the command line: `/etc/php.d/*`

## Start of the web socket server

The command line on unix is:

```
nohup php /home/minesweeper/public_html/WS/server.php &
```

The previous command logs all output of the server.php into the nohup.log file. To trace whateven is logged into it:

```
tail -f nohup.out
```

# TLS

## Configure the TLS certificate

Go to the [cerbot](https://certbot.eff.org/instructions?ws=apache&os=ubuntufocal) site.

To reinstall the certificates:

```
sudo certbot --apache
```

or (to just generate the certificate) :

```
sudo certbot certonly --apache
```


The certificates are stored in `/etc/letsencrypt/`



