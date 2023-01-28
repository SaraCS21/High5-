# High5!

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT) [![CodeFactor](https://www.codefactor.io/repository/github/saracs21/high5-/badge)](https://www.codefactor.io/repository/github/saracs21/high5-)

## Tabla de contenidos

- [High5!](#high5)
  - [Tabla de contenidos](#tabla-de-contenidos)
  - [Tecnologías aplicadas](#tecnologías-aplicadas)
  - [Requerimientos](#requerimientos)
  - [Instalación](#instalación)
  - [Uso](#uso)
  - [Contacto](#contacto)

<div style="display:flex;align-items:center;justify-content:space-between;width=100%;margin-bottom:2rem;">
    <h2>El proyecto</h2>
    <img src="https://i.imgur.com/XFhOu86.png">
</div>
<a id="proyecto"></a>

El proyecto se trata de un foro, donde tanto usuarios como administradores podrán crear diferentes posts y comentar en estos.

## Tecnologías aplicadas
<a id="tecnologias"></a>

- Front-end

    - ![](https://i.imgur.com/b94t1MP.png)
    - ![](https://i.imgur.com/ItJlcfb.png)
    - ![](https://i.imgur.com/707KDQ7.png)
    - ![](https://i.imgur.com/GmJkc6Q.png)
- Gestión de la Base de datos
    
    - ![](https://i.imgur.com/iD5UNCT.png)
    - ![](https://i.imgur.com/mQAwNfH.png)
    - ![](https://i.imgur.com/nt5MfST.png)
- Back-end

    - ![](https://i.imgur.com/5nOhMsa.png)
    - ![](https://i.imgur.com/eTEnddN.png)
    - ![](https://i.imgur.com/jagrocW.png)
- Control de versiones 

    - ![](https://i.imgur.com/OVEFEj6.png)
    - ![](https://i.imgur.com/gRzGqDh.png)
- Despliegue

    - ![](https://i.imgur.com/PVb1u3s.png)

## Requerimientos
<a id="requerimientos"></a>

- PHP 7.4.10 o superior
- MariaDB 10.4.14
- Apache/2.4.46 
- Bootstrap 5
- Composer 2.5.1

## Instalación
<a id="instalacion"></a>

Primeramente debemos clonar el repositorio para tenerlo de manera local:

```bash=
$ git clone https://github.com/SaraCS21/High5-.git
```
Debemos tener en cuenta que nuestra aplicación hace uso de variables de entorno, así que para poder usar nuestra Base de Datos de manera local tendremos que crear un fichero <span style="color:#6f11eb">`.env`</span> en el directorio raíz, siguiendo la siguiente estructura:

```js=
DB_HOST=host_que_queramos
DB_USER=user_que_queramos
DB_PASS=password_que_queramos
DB_DB=Foro 
```

Una vez lo tengamos listo, tendremos que instalar las dependencias para que nuestra aplicación pueda funcionar, para ello tendremos que ejecutar <span style="color:#6f11eb">`composer`</span>:

```bash=
$ composer install
```

Tras eso, ejecutaremos el <span style="color:#6f11eb">`index.php`</span>, este instalará de manera automática la base de datos del programa, además de inicializar una serie de parámetros por defecto para no ver la web vacía al comienzo.

Podemos ejecutar nuestra aplicación de la siguiente manera, estableciendo como puerto el que nosotros queramos:

```bash=
$ php -S localhost:300
```

Con todo esto ya estaríamos listos para usar nuestra aplicación.

--- 

En caso de que queramos crear la documentación, tan solo tendremos que ejecutar el siguiente comando en la carpeta de nuestro proyecto:

```bash=
$ docker run --rm -v "$(pwd):/data" "phpdoc/phpdoc:3" --title High5! --ignore "vendor/"
```

## Uso
<a id="uso"></a>

La aplicación trata de un foro en el que diferentes personas podrán registrarse e iniciar sesión para, a continuación, acceder a una pantalla con todos los diferentes posts creados.

![](https://i.imgur.com/Hmt46XR.png)

Los usuarios podrán realizar comentarios a los posts, darles like y, cuando son de su propiedad, editarlos y eliminarlos.

![](https://i.imgur.com/yOR99Nn.png)

Por otro lado, los administradores de la aplicación tendrán control total sobre los posts y los usuarios, pudiendo modificar ciertos valores y eliminarlos. También podrán bloquear usuarios, prohibiéndole el acceso a la web.

![](https://i.imgur.com/OClp3u3.png)

## Contacto
<a id="contacto"></a>

Sara del Pino Cabrera Sánchez - saracs15.scssn@gmail.com

[Enlace a la web](https://)

[![](https://i.imgur.com/F0jmP7u.png)](https://www.linkedin.com/in/sarascs/)[![](https://i.imgur.com/xICWHyo.png)](https://github.com/SaraCS21)