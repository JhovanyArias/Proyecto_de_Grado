# Proyecto de Grado - Jhovany Andres Arias Rodriguez

![HTML](https://img.shields.io/badge/HTML-f06529?style=for-the-badge&logo=html5&logoColor=white&labelColor=e34c26) ![CSS](https://img.shields.io/badge/CSS-264de4?style=for-the-badge&logo=CSS3&logoColor=white&labelColor=2965f1) ![JavaScript](https://img.shields.io/badge/JavaScript-f0db4f?style=for-the-badge&logo=javascript&logoColor=white&labelColor=323330) ![php](https://img.shields.io/badge/php-5B5B5B?style=for-the-badge&logo=php&logoColor=black&labelColor=8993be)

## Herramienta educativa realizada en HTML, CSS, JavaScript y php orientada para niños en edad preescolar que ofrece actividades interactivas de conteo, adivina la imagen, ordena la frase y series lógicas enfocada en el aprendizaje a través del juego.

![](./media/SaberKids.png)

### Funcionalidades:

Creacion de Usuarios:

[Ver video](./media/Creacion%20de%20Usuarios.mp4)

Esta opcion solo será habilitada para el usuario que tenga de rol "Administrador"
Al momento de la creacion el usuario ingresará los valores de: Nombre - Nickname - Contraseña
Para los campos de: Tipo de Usuario y Relacion deberá elegir entre una lista, cabe mencionar que para el campo relacion la informacion que se mostrará depende de la opcion seleccionada en Tipo de Usuario (si el usuario es un estudiante se cargará el listado de Padres y si el usuario es un Padre de Familia se cargará el listado de estudiantes en el sistema)

Código:

- [Creacion de Usuarios](./new_user.php)

Creacion de Actividades:

[Ver video](./media/Creacion%20Actividades%201.mp4)
[Ver video](./media/Creacion%20Actividades%202.mp4)

Para esta seccion se manejaron 3 formas diferentes de crear las actividades
La primera es seleccionando una imagen del equipo y escoger la respuesta entre 4 opciones marcando con un checkbox la respuesta correcta, esta opcion se utilizó para las actividades de "Conteo" y "Adivina la Imagen", para la actividad de "Ordena la frase" el tutor digitará la frase (entre 4 y 5 palabras), el sistema tomará la frase, la dividirá, la desordenará y la guardará en la base de datos; para la ultima actividad llamada "Series Logicas" el tutor escogerá entre una serie de imagenes previamente cargadas en el sistema la serie lógica, posterior a esto hará el mismo procedimiento para las 3 respuestas posibles y marcará la opcion correcta con un checkbox

Código:

- [Creacion de Actividades](./actividades/creacion_actividades.php)

Desarrollo de Actividades:

[Ver video](./media/Desarrollo%20de%20Actividad.mp4)

En el perfil del estudiante se muestra un listado de las actividades que ha diseñado el tutor, el niño selecciona la actividad que quiera realizar dandole click al boton Jugar.
La actividad se muestra en pantalla y a medida que el niño va resolviendo cada mini-juego este se va ocultando ayudando a que el niño se concentre en la actividad que se le esta presentando.
A medida que el niño resuelve cada mini-juego se va registrando los intentos y los fallos en la base de datos y en la parte inferior hay un boton que envia estos contadores a la base de datos.
Al finalizar tiene un espacio para que realice un dibujo, junto a éste tiene un listado de imagenes en caso de que quiera replicar alguna de las opciones.

Código:

- [Perfil de Estudiante](./acceder_estudiante.php)
- [Actividad](./actividades/juegos.php)

Informes:

[Ver video](./media/Informes.mp4)

El sistema toma los datos almacenados al desarrollar las actividades y las muestra en una tabla.

Código:

- [Informes](./informes.php)

Mensajes:

[Ver video](./media/Mensajes.mp4)

Una funcionalidad importante del sistema era la comunicacion por lo que se implementó un sistema de mensajeria en el cual los tutores pueden enviar mensajes a los padres de familia de los niños.
Para esto el tutor accede al menu Mensajes y selecciona el nombre del padre de familia al que quiere enviar el mensaje, el sistema solo le muestra los padres de los niños que fueron asignados a él, esto con el fin de no sobrecargar el listado.

Código:

- [Mensajes](./mensajes.php)

Padre de Familia:

[Ver video](./media/Interfaz%20Padre%20de%20Familia.mp4)

En la interfaz del Padre de familia se mostrarán los mensajes enviados por el tutor asi como una tabla con los resultados de las actividades desarrolladas por el hijo de cada padre de familia.

Código:

- [00 - Hola Mundo](./Basic/00-helloworld.js)
- [01 - Variables](./Basic/01-variables.js)

Logros:

Los logros de la aplicacion estan creados por defecto y dependen de unos contadores asignados a cada estudiante y ligados a la cantidad de actividades desarrolladas.

![](./images/banner1.jpg)
![](./images/banner2.jpg)
![](./images/banner3.jpg)
