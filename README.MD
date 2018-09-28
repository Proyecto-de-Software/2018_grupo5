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
| assertPermission()   | si el usuario no tiene permiso para la vista cierra la conexion, renderiza la plantilla forbidden.html. El nombre del permiso es generado automaticamente, no requiere parametros| 
| userHasPermissionForCurrentMethod()   | retorta true o false, si el usuario tiene el permiso para el metodo que invoco a esta funcion. El nombre del permiso es generado automaticamente   |  
| userHasPermission(permission_name) | retorna true o false, si el usuario tiene el permiso enviado por parametro.| 
| userIsAdmin() | retorna true o false | 
| jsonResponse(data[]) | inserta los headers de json , y retorna un objeto de tipo json | 
| redirect($url) | Redirecciona y cierra la conexion | 
| twig_reder(path_to_view, context[]) | |
| entityManager() | retorna la instancia de entityManager |
| getModel(modelo)  | Incluye el archivo necesario y retorna el repositorio del modelo |



# Concrete Controller
### convenciones
 - El nombre del archivo en CamelCase y terminar con Controller.php  
    - Ejemplos bien:
    ```
     UsuarioController.php 
     PacientesController.php
     ```
    - Ejemplos mal:     
    ``` 
    usuarioController.php 
    Usuario_Controller.php 
    Pacientes.php
     ```
 - Las clases deben extener de  Controller
 - **Unicamente** Los metodos accesibles por las urls deben ser publicos, el resto privados!!
 - Los metodos publicos reciben como parametro ...$args , ej : public function metodo(...$args){}
 
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



