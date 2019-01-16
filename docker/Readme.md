###Starting new project
```bash
    git clone ...
    docker-compose up
    docker ps
    docker exec -it .. composer update 
    docker exec -it .. php artisan init
    docker exec -it .. php artisan migrate
```

### Setting up docker-compose for phpStorm

####Ubuntu

* [Setting up Docker](https://docs.docker.com/install/linux/docker-ce/ubuntu/#install-docker-ce-1)

* [Setting up Docker-compose](https://docs.docker.com/compose/install/)

* Running PhpStorm

Go `File->Settings->Build,Execution,Deployment->Docker`

Press `+`. Choose Unix socket, must be connected status.

Go `File->Settings->Languages&Frameworks->PHP` 
 
Press `...` at `CLI Interpreter`, Press `+`, Choose `From Docker Vagrant ...`, Choose Docker Compose, OK.

Go `File->Settings->Languages&Frameworks->PHP->Test Frameworks` 
 
Press `+`, choose `PHPUnit by remote interpreter` choose interpreter witch you specified 1 step above, 
show way to the autoload.php file, OK.

Go `File->Settings->Languages&Frameworks->PHP->Debug->DBGp Proxy` 
 
Set `Port` field to 9000.

Setting env.testing settings, for database connection: 

```bash
    docker ps
```

Use name of service with database as host ip in env.testing

##Troubleshooting

* Granting rights to docker, [Taken from here](https://askubuntu.com/questions/477551/how-can-i-use-docker-without-sudo)

Add the docker group if it doesn't already exist:

```bash
    sudo groupadd docker
```

Add the connected user "$USER" to the docker group. Change the user name to match your preferred user if you do not want to use your current user:

```bash
    sudo gpasswd -a $USER docker
```

You can use to check if you can run docker without sudo.

```bash
    docker run hello-world
```
