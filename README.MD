## Instalacion
```
  # activar mod_rewrite para apache
  sudo a2enmod rewrite


```
Para modificar las settings, usar settings.default.json como plantilla,
para crear otro archivo, en el mismo directorio, llamda "settings.json"
y sobrescribir los valores necesarios.

** Si se actulizan las urls, o explota algo de dispatcher, primero provar con borrar las cookies

# Controller
| Metodo        |  Descripcion             | 
| ------------- |:-------------:|
| assertPermission()   | si el usuario no tiene permiso para la vista cierra la conexion, renderiza la plantilla forbidden.html, el nombre del permiso es generado automaticamente, no requiere parametros| 
| userHasPermissionForCurrentMethod(permission_name)   | retorta true o false, si el usuario tiene el permiso     |  
| twig_reder(path_to_view, context[]) | |


# Concrete Controller
### convenciones
 - El nombre del archivo en CamelCase y terminar con Controller.php  
    - Ejemplos correctos:   
    ``` 
    UsuarioController.php 
    PacientesController.php
     ```
     - Ejemplos malos
     
    ``` 
    usuarioController.php 
    Usuario_Controller.php 
    Pacientes.php
     ```
 - Las clases deben extener de  Controller
 
 
##Doctrine
```
  
  # mapear entidades desde la DB
  vendor/bin/doctrine orm:convert-mapping --force --from-database annotation ./src/models
  
  # generar getters y setters
  vendor/bin/doctrine orm:generate-entities ./src/models/
  
  # mapear entidades hacia la DB - crear schema
  vendor/bin/doctrine orm:schema-tool:create
  
  # actualizar schema
  vendor/bin/doctrine orm:schema-tool:update
```


