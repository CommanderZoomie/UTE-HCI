# EduEventos

Anthony Nicholas Carmen
CI: 3050691819
Ingeniería en las Ciencias de la Computación
Interacción Hombre - Máquina 
Ing. Diego Araujo – Universidad UTE
Caso Práctico 1 – 21 junio de 2025

CASO PRÁCTICO 1 
Interacción Hombre - Máquina
 

Universidad UTE - Educación en Línea
Ingeniería en las Ciencias de la Computación

Anthony Nicholas Carmen – CI: 3050691819
Semestre 5 – Interacción Hombre - Máquina
Ing. Diego Araujo

 10 de julio de 2025

ÍNDICE
1.	Introducción 
2.	Objetivo 
3.	Aplicación Corriendo y Hospedada en Internet
4.	Código Fuente y Repositorio
5.	Diseño de Navegación con Wireflow
6.	Diseño de las Pantallas de Usuario
7.	Notas Adicionales
8.	Conclusión
9.	Referencias y Evidencias
10.	Rúbrica para evaluar el caso Práctico




1.	INTRODUCCIÓN
El presente informe técnico documenta el desarrollo y la implementación de la aplicación web EduEventos, una plataforma diseñada para gestionar y presentar información sobre eventos educativos. Este documento abarca desde el diseño de la navegación y las interfaces de usuario, hasta la codificación, pruebas, y su despliegue en un entorno de hosting real. A lo largo del informe se presentan evidencias gráficas y textuales que contextualizan cada fase del proceso. También se detallan los desafíos enfrentados —principalmente con servicios de hosting gratuitos— y las soluciones adoptadas para garantizar el funcionamiento estable del sistema.

2.	 OBJETIVO 
El objetivo principal del proyecto es desarrollar una aplicación web funcional, visualmente clara y accesible que permita a los usuarios visualizar e interactuar con información de eventos, aplicando principios clave de Interacción Humano-Computadora (HCI). Paralelamente, se busca dejar un registro técnico claro y completo del proceso de desarrollo, desde el diseño visual, codificación, pruebas, publicación y las lecciones aprendidas.

3.	 APLICACIÓN CORRIENDO Y HOSPEDADA EN INTERNET
La aplicación EduEventos se encuentra actualmente operativa en un entorno web real. Se desarrollaron tres versiones progresivas del sitio, enfrentando y superando problemas relacionados con servicios de hosting gratuitos.
Página Web Versión 2 (No Funcional): http://edueventos.mywebcommunity.org/main/inicio.php
Notas: Esta versión presentó serios problemas de diseño desalineado y fallos en la carga de datos. Como resultado, fue descartada para evitar confusión y pérdida de funcionalidad.

  

Página Web Versión 3 (Funcional y Estable): https://edueventos.great-site.net/
Descripción: Esta versión contiene todas las funcionalidades implementadas correctamente, conserva el diseño original, y está validada con pruebas visuales.

4.	 CÓDIGO FUENTE Y REPOSITORIO
 
Todo el código fuente de EduEventos está disponible en GitHub. El repositorio está organizado modularmente y puede compilarse sin errores. Repositorio GitHub: https://github.com/CommanderZoomie/UTE-HCI
 
 
   
 

Estructura de Archivos:
	README.md: Documentación general.
	images: Recursos visuales del sitio.
	css: Hojas de estilo para el diseño.
	js: Lógica de interactividad cliente.
	settings/: Conexión a BD y configuración de la aplicación web.
	security: Lógica de autenticación y de sesiones.
	includes: Fragmentos PHP reutilizables (headers, footers, navbar, tema de la página).
	index.php: Entrada principal del sitio y redireccionar al main.
	main: Páginas centrales (inicio, ver eventos, contacto, login).
	platform: Módulos funcionales (dashboard, eventos, contactos, ubicaciones, logout).
	uploads: Carpeta para archivos subidos por parte del usuario/admin (como fotos).


5.	 DISEÑO DE NAVIGACIÓN CON WIREFLOW
 
Se elaboró un diagrama de navegación utilizando Wireflow para representar gráficamente la estructura funcional y jerárquica de la aplicación web EduEventos, con el objetivo de optimizar la experiencia de usuario mediante principios de Interacción Humano-Computadora (HCI). La navegación ha sido diseñada de forma intuitiva, permitiendo al usuario comenzar desde una pantalla de inicio clara, con acceso inmediato a secciones clave como “Ver Eventos”, “Contáctanos” e “Iniciar Sesión”.
Una vez autenticado, el usuario con rol administrativo puede acceder a módulos internos para gestionar eventos, contactos y ubicaciones. El diseño mantiene una arquitectura de información consistente, con patrones de navegación predecibles y una distribución lógica de los objetos en pantalla.
Desde el punto de vista visual, se ha aplicado una paleta de colores neutros con contraste adecuado para garantizar accesibilidad visual. La interfaz soporta tanto modo claro como modo oscuro, ajustándose a las preferencias del usuario para mejorar la legibilidad y reducir la fatiga visual. Los formularios emplean alineación vertical con campos bien espaciados y etiquetas claras, reforzando la eficiencia y reducción de errores durante la entrada de datos.
Los botones, íconos y componentes interactivos están ubicados en lugares convencionales, priorizando la visibilidad y la familiaridad. Se utilizan layouts basados en cuadrículas (grid-based design) para mantener el orden visual y la coherencia entre páginas, asegurando una experiencia uniforme en diferentes dispositivos y resoluciones (diseño responsive).
6.	DISEÑO DE LAS PANTALLAS DE USUARIO

Páginas Públicas (“main”)

 
	Pantalla: Inicio (inicio.php)
Esta es la página de aterrizaje principal de la plataforma EduEventos. Presenta una introducción visual, una descripción general de los beneficios del sistema y permite el acceso a otras secciones públicas como "Ver Eventos", "Contáctanos" e "Iniciar Sesión" a través de su barra de navegación.
Valor para el usuario: Proporciona una entrada clara y visualmente organizada, facilitando la navegación inicial y reduciendo el tiempo necesario para encontrar información relevante.
  

	Pantalla: Ver Eventos (ver_eventos.php)
Esta página muestra una lista pública de todos los eventos activos. Incluye detalles esenciales para cada evento como el Título, Fecha, Hora, Ubicación e información de Contacto. Los usuarios pueden ver estos detalles y, típicamente, acceder a información más específica sobre un evento.
Valor para el usuario: Mejora la exploración y planificación al mostrar información clave de manera clara, permitiendo que los usuarios tomen decisiones informadas rápidamente.  

	Pantalla: Contáctanos (contactanos.php)
Esta página presenta un formulario de contacto para que los usuarios envíen sus consultas. Incluye campos obligatorios como Nombre, Apellidos, Número de Teléfono, Correo Electrónico, País, Institución Educativa y Mensaje. La página también integra una visualización interactiva de Google Maps mostrando la ubicación física de la plataforma, y proporciona un mensaje de confirmación al enviar el formulario correctamente.
Valor para el usuario: Facilita la comunicación directa con la organización, con validaciones que garantizan una experiencia sin errores y un mapa que orienta visualmente.
   

	Pantalla: Login (login.php)
Esta es la página dedicada para que los usuarios inicien sesión en la plataforma. Proporciona campos para introducir un nombre de usuario y una contraseña, cuyas credenciales son validadas contra una base de datos (contraseñas almacenadas con hashing SHA256). También incluye opciones para la recuperación de contraseña o para contactar a un administrador.
Valor para el usuario: Ofrece un acceso seguro y confiable al sistema, protegiendo la información personal y brindando mecanismos para recuperar el acceso fácilmente.
  

Páginas con Sesión Iniciada (“platform”)
	Pantalla: Inicio (Dashboard)
Funciona como el panel de control principal tras un inicio de sesión exitoso. Da la bienvenida al usuario y describe la estructura del sistema de gestión, que se divide en tres secciones primarias: Eventos, Ubicaciones y Contactos. Ofrece acceso directo para gestionar cada una de estas categorías.
Valor para el usuario: Centraliza todas las funcionalidades clave, lo que mejora la eficiencia operativa y brinda una visión clara del sistema al usuario autenticado. 
 
	Pantalla: Gestión de Eventos
Esta página está dedicada a la administración de eventos. Los usuarios pueden agregar nuevos eventos, ver una lista completa de los eventos existentes en formato de tabla (mostrando ID, Título, Fecha, Hora, Ubicación y Contacto), y realizar acciones como ver detalles, editar o eliminar registros de eventos.
Valor para el usuario: Permite el control completo de los eventos en tiempo real, asegurando que la información esté siempre actualizada y alineada con las necesidades de la organización.
 
	Pantalla: Gestión de Ubicaciones
Descripción: Esta sección permite la gestión de las ubicaciones de los eventos. Los usuarios pueden agregar nuevas ubicaciones y ver una lista de todas las ubicaciones registradas. La tabla proporciona detalles como ID, Título, Dirección, Ciudad, País y un enlace a su ubicación en Google Maps, con opciones para modificar o eliminar entradas existentes.
Valor para el usuario: Agiliza la administración geográfica de eventos y mejora la claridad sobre dónde ocurren las actividades, apoyando la logística y planificación del equipo.
 
	Pantalla: Gestión de Contactos
Descripción: Esta página es para la gestión de contactos asociados con los eventos. Los usuarios pueden agregar nuevos contactos y ver una lista de todos los contactos existentes. La tabla de contactos incluye información como ID, Nombres, Apellidos, Institución Educativa, CI (Identificación), Número de Teléfono, Correo Electrónico y una foto de perfil, junto con acciones para editar o eliminar registros de contactos.
Valor para el usuario: Mejora la organización del equipo y la trazabilidad de las personas involucradas, facilitando futuras comunicaciones y coordinaciones.
 
	Pantalla: Logout (Cierre de Sesión)
Descripción: Esta página se muestra después de que un usuario cierra sesión exitosamente de la plataforma. Muestra un mensaje de confirmación indicando que la sesión ha sido cerrada correctamente y ofrece opciones convenientes para regresar a la página pública de "Inicio" o para "Iniciar Sesión" de nuevo.
Valor para el usuario: Refuerza la seguridad y la claridad del estado de sesión, ayudando al usuario a saber que su información ya no está activa en el sistema.
 
7.	NOTAS ADICIONALES
Durante el desarrollo, se detectaron múltiples limitaciones con proveedores de hosting gratuito.
La versión 2 sufrió alteraciones inesperadas en diseño y funcionalidades (incluso sin haber tocado el código), lo que llevó a descartarla.
La versión 3, publicada en InfinityFree, pasó todas las pruebas visuales y funcionales. Se tomaron capturas de cada sección como respaldo.
  
8.	CONCLUSIÓN
El desarrollo de EduEventos ha sido una experiencia sólida y formativa, permitiendo aplicar conocimientos técnicos de desarrollo web y principios de Interacción Humano-Computadora (HCI).
A pesar de los inconvenientes técnicos iniciales, se logró publicar una versión funcional que cumple con los objetivos académicos y de usabilidad propuestos.
La estructura modular del código, la navegación intuitiva y las pruebas con usuarios demuestran que se trata de un sistema robusto, adaptable y viable.
Este proyecto es una muestra clara de resiliencia, mejora continua y documentación responsable.

9.	 REFERENCIAS y EVIDENCIAS

	2025 – Ing. Diego Araujo – Unidad Didáctica 1 [.pdf]
	2025 – Ing. Diego Araujo – Unidad Didáctica 2 [.pdf]
	Repositorio GitHub: https://github.com/CommanderZoomie/UTE-HCI
	Wireflow: https://drive.google.com/file/d/1_-GoTf9jxMJ6GaCSaMRWXfFYvhIuaXl0/view
	Hosting Paneles:
	https://cp1.awardspace.net/
	https://dash.infinityfree.com/
	Página Web Versión 2: http://edueventos.mywebcommunity.org/main/inicio.php
	Página Web Versión 3: https://edueventos.great-site.net/
 
10.	 RÚBRICA PARA EVALUAR EL CASO PRÁCTICO
Categoría/ Puntaje	PON.	Muy bien 
(2.0)	Bien 
(1.5)	Regular 
(1.0)	Insuficiente 
(0.5)	Deficiente 
(0)	NOTA
Entregable 1	40%	Presenta el entregable correctamente	Presenta el entregable de forma adecuada	Presenta el entregable de forma regular	Presenta el entregable de forma insuficiente	No presenta el entregable	
Entregable 2	20%	Presenta el entregable correctamente	Presenta el entregable de forma adecuada	Presenta el entregable de forma regular	Presenta el entregable de forma insuficiente	No presenta el entregable	
Entregable 3.a	10%	Presenta el entregable correctamente	Presenta el entregable de forma adecuada	Presenta el entregable de forma regular	Presenta el entregable de forma insuficiente	No presenta el entregable	
 Entregable 3.b	10%	Presenta el entregable correctamente	Presenta el entregable de forma adecuada	Presenta el entregable de forma regular	Presenta el entregable de forma insuficiente	No presenta el entregable	 
 Entregable 3.c	10%	Presenta el entregable correctamente	Presenta el entregable de forma adecuada	Presenta el entregable de forma regular	Presenta el entregable de forma insuficiente	No presenta el entregable	
 Entregable 3.d	10%	Presenta el entregable correctamente	Presenta el entregable de forma adecuada	Presenta el entregable de forma regular	Presenta el entregable de forma insuficiente	No presenta el entregable	
Calificación:	/10

