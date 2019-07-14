## Instalacion
```
  # activar mod_rewrite para apache
  sudo a2enmod rewrite


```
Para modificar las settings, usar settings.default.json como plantilla,
para crear otro archivo, en el mismo directorio, llamda "settings.json"
y sobrescribir los valores necesarios.

** Si se actulizan las urls, o explota algo de dispatcher, primero provar con borrar las cookies

# SiMiLController
| Metodo        |  Descripcion             | 
| ------------- |:-------------:|
| include_defaults_twig_params   | agrega informacion adicional para tener disponible en las vistas | 
| camelCaseToSnake($string)  | convierte el string pasado por parametro|  
| jsonResponse(data[]) | inserta los headers de json , y retorna un objeto de tipo json | 
| redirect($url) | Redirecciona y cierra la conexion | 
| twig_render($path, $parameters) | renderiza la vista pasada por parametro y agrega informacion adicional para tener disponible en la vista |



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
 

## API:

### `/instituciones:`
##### Create:  
Method: POST
Path: api/instituciones/crear
```json
{
  "direccion" : "direccion",
  "nombre" : "nombre",
  "telefono" : "022144433",
  "idTipoInstitucion " : "2",
  "idRegionSanitaria" : 1,
  "coordenadas" : "-38.72,-65.99",
  "director" : "Marcos Director"
}
```

##### Update:
Method: POST
Path: api/instituciones/modificar/{ID}

```json
{
  "direccion" : "direccion",
  "nombre" : "nombre",
  "telefono" : "022144433",
  "idTipoInstitucion " : "1",
  "idRegionSanitaria" : "2",
  "coordenadas" : "-38.72,-65.99",
  "director" : "Marcos Director"
}
```

##### Usuarios para testear



