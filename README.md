![](https://i.imgur.com/kVrDpjW.png)


## Tabla de contenidos

- [Requerimentos](#requerimientos)
- [Instalación](#instalacion)
- [Uso](#uso)

## Requerimientos

- PHP 7.4.10 o superior
- MariaDB 10.4.14
- Apache/2.4.46 
- Bootstrap 5
- Composer 2.5.1

## Instalación

Primeramente debemos clonar el repositorio para tenerlo de manera local:

```
$ git clone https://github.com/SaraCS21/High5-.git
```
Debemos tener en cuenta que nuestra aplicación hace uso de variables de entorno, así que para poder usar nuestra Base de Datos de manera local tendremos que crear un fichero <span style="color:#6f11eb">`.env`</span> en el directorio raíz, siguiendo la siguiente estructura:

```
DB_HOST=host_que_queramos
DB_USER=user_que_queramos
DB_PASS=password_que_queramos
DB_DB=Foro 
```

Una vez lo tengamos listo, tendremos que instalar las dependencias para que nuestra aplicación pueda funcionar, para ello tendremos que ejecutar <span style="color:#6f11eb">`composer`</span>:

```
$ composer install
```

Tras eso, ejecutaremos el <span style="color:#6f11eb">`index.php`</span>, este instalará de manera automática la base de datos del programa, además de inicializar una serie de parámetros por defecto para no ver la web vacía al comienzo.

Podemos ejecutar nuestra aplicación de la siguiente manera, estableciendo como puerto el que nosotros queramos:

```
$ php -S localhost:300
```

Con todo esto ya estaríamos listos para usar nuestra aplicación.

## Uso

La aplicación trata de un foro en el que diferentes personas podrán registrarse e iniciar sesión para, a continuación, acceder a una pantalla con todos los diferentes posts creados.

![](https://i.imgur.com/Hmt46XR.png)

Los usuarios podrán realizar comentarios a los posts, darles like y, cuando son de su propiedad, editarlos y eliminarlos.

![](https://i.imgur.com/yOR99Nn.png)

Por otro lado, los administradores de la aplicación tendrán control total sobre los posts y los usuarios, pudiendo modificar ciertos valores y eliminarlos. También podrán bloquear usuarios, prohibiéndole el acceso a la web.

![](https://i.imgur.com/OClp3u3.png)
