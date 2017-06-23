<?php
/**
 *   @file control/edit-config.php
 *   @brief change the config file
 *   @description User Interface to change config file
 *
 *   @author dgonzalez
 *   @date Jan 2013
 *   @todo
 */

use SubjectsPlus\Control\Config;

//variables required in header and add header
$subcat = "admin";
$page_title = "Edición de configuración";
$use_jquery = array("ui_styles");

include("includes/header.php");


//new instance of config class
$lobjConfig = new Config();

//declare variable that stores configuration path
$lstrConfigFilePath = 'includes/config.php';

//set config file path
$lobjConfig->setConfigPath( $lstrConfigFilePath );

//echo out error message if the configuration file is not writable
if(!is_writable($lstrConfigFilePath))
{
	?>
	<p><?php echo _( "Lo siento, pero no puedo escribir en el archivo <code> config.php </code>. Por favor, cambie los permisos." ); ?></p>
	<?php

}else
{
	/* this is a declaration of an array that contains all the options in the
	*  configuration that will be presented to the user in the HTML form to be
	*  changed and saved. The way this array is declared to work with all the
	*  functions that use it is as follows: [0] User form label [1] Notes to display
	*  below input label [2] Type of declaration in config file [3] where to display
	*  this input on HTML form (left or right box) [4] input specification (for string
	*  type either small, medium, large or array type can be in form of ticks and boolean
	*  always is a select box [5] extra data : e.g. holds options for ticks and will only be used if
	*  array and ticks are specified or if additional data needed [6] tooltip that will display when
	*  hovering over '?' icon and if blank, no icon will appear
	*/
	$lobjConfigOptions = array(
							"omit_user_columns" => array( _( "Columnas a OMIT en formulario de usuario" ), _( "Marque los campos que desea OMIT en el formulario de usuario." ), "array", "right", "ticks", array( _( 'fname' ), _( 'lname' ), _( 'title' ),
							_( 'department' ), _( 'position_number' ), _( 'classification' ), _( 'priority' ), _( 'supervisor' ), _( 'tel' ), _( 'fax' ), _( 'intercom' ), _( 'room_number' ), _( 'user_type' ),
							_( 'personal_information' ), _( 'emergency_contact' ) ), _( "El 'Formulario de usuario' controla quién puede acceder a SubjectsPlus y qué permisos tienen. Algunos campos son necesarios para que JobsPlus funcione; Estos pueden desactivarse de forma segura si no desea capturar o mostrar esta información." ) ),

							"require_user_columns" => array( _( "Columnas a requerir en formulario de usuario" ), _( "Marque los campos que desea hacer necesarios en el formulario de usuario" ), "array", "right", "ticks",
							array( _( 'title' ), _( 'position_number' ), _( 'classification' ), _( 'priority' ), _( 'tel' ), _( 'fax' ), _( 'intercom' ), _( 'room_number' ), _( 'address' ), _( 'city' ),
							_( 'state' ), _( 'zip' ), _( 'home_phone' ), _( 'cell_phone' ), _( 'lat_long' ), _( 'emergency_contact' ) ), _( "Estos campos pueden ser requeridos, es decir, el formulario no puede ser enviado hasta que se hayan completado." ) ),

							"guide_container_width" => array( _( "Ancho del contenedor guía" ), _( "Asigne un ancho en píxeles para sus guías. Por ejemplo, 960px." ), "array", "right", "small", "", _("Por ejemplo, 960px o 1160px.") ),

							"google_analytics_ua" => array( _('Código de Google Analytics UA'), _('Agregue su código de seguimiento de Google Analytics UA.'), "string", "right", "medium", "", ""),

							"default_subject_specialist_heading" => array( _( "Predeterminado Subject Specialist Pluslet Heading" ), _( "Un encabezado global para el Sujeto Especialista Pluslet" ), "string", "right", "large", "" , _( "Por ejemplo, bibliotecarios o bibliotecarios de enlace." ) ),

							"headshot_thumb_width" => array( _( "Foto" ), _( "Ancho para la miniatura del headshot del personal. Nota: Si cambia este valor, afectará a las imágenes de headshot previamente subidas." ), "string", "right", "small", "" , _( "Por ejemplo: 70, 100." ) ),

							"headshot_large_width" => array( _( "Foto del personal Grande" ), _( "Ancho de la cabeza del personal grande. Nota: Si cambia este valor, afectará los tiros a la cabeza cargados anteriormente." ), "string", "right", "small", "" , _( "Por ejemplo: 225, 300." ) ),

							"guide_types" => array( _( "Tipos de guía" ), _( "Estas son las diferentes maneras en que puede organizar sus guías." ), "array", "right", "large", "", _("Puede agregar nuevos tipos en cualquier momento. Si cambia un tipo existente, deberá actualizar todos los elementos de la tabla de objetos manualmente / consulta SQL.") ),

			        "use_shibboleth" => array( _( "Utilizar Shibboleth" ), _( "Esta opción controla si Shibboleth se utilizará para iniciar sesión." ), "boolean", "right", "small", "", _("Esto verifica el correo electrónico del personal con las variables de servidor devueltas de Shibboleth.") ),
				
							"shibboleth_logout" => array( _( "Shibboleth Cerrar sesión URL" ), _( "Esta es la URL que registra al usuario de una sesión Shibboleth." ), "string", "right", "large", "", _("Esta es una URL que registra al usuario de una sesión Shibboleth.") ),
				
							"all_ctags" => array( _( "Registro de etiquetas" ), _( "Estas son las etiquetas que una ubicación de registro determinada puede asociarse con ella." ), "array", "right", "textarea", "", _("Las etiquetas de registro son una forma de cortar y cortar el conjunto total de registros. Si agrega una nueva etiqueta, deberá agregar un nuevo código para tratar los elementos con esta etiqueta. La adición de una etiqueta por sí sola no hará nada, excepto hacer que la etiqueta aparezca en algunos lugares.") ),

							"all_vtags" => array( _( "Etiquetas de video" ), _( "Estas son las etiquetas que un video determinado puede asociar con él. Estos se utilizan para el módulo de videos." ), "array", "right", "large", "", "" ),

							"all_tbtags" => array( _( "Etiqueta del sitio de Talkback" ), _( "Estas son las etiquetas asociadas a las entradas de TalkBack. El valor predeterminado es enviar por correo electrónico todos los comentarios a correo electrónico del administrador. Edite sólo si desea cambiarlo o agregar una sucursal." ),
							"aarray", "right", "large", "", _( "p.ej. Para hacer que una rama principal envíe al correo electrónico de administración y la sucursal1 se envíe a un correo electrónico especificado -> 'main =, branch1 = example @ branch1.edu'" ) ),

							"all_cattags" => array( _( "Tags del tema de Talkback" ), _( "Estas son las etiquetas que un talkback dado puede haber asociado con él." ), "array", "right", "textarea", "", _("Las etiquetas de Talkback son una forma de cortar y cortar en cuadritos el conjunto total de talkbacks. Si agrega una nueva etiqueta, deberá agregar un nuevo código para tratar los elementos con esta etiqueta. La adición de una etiqueta por sí sola no hará nada, excepto hacer que la etiqueta aparezca en algunos lugares.") ),

							"titlebar_styles" => array( _( "Estilos de barra de título" ), _( "Estos son los estilos que se pueden emitir a barras de título pluslet." ), "aarray", "right", "large", "", _("Esto permite que las barras de título tengan estilos personalizados con claves personalizadas para que el creador de la guía de temas realice.") ),

							"pluslets_activated" => array( _( "Pluslet Activado" ), _( "Qué Pluslets están activados." ), "array", "right", "ticks", array('Basic', 'LinkList', 'PrimoSearch', 'Heading','Card', 'HTML5Video','SubjectSpecialist', 'BookList', _('4'),'Feed','SocialMedia', 'Chat', 'WorldCat', 'Catalog','ArticlesPlus','GoogleBooks','GoogleScholar','GoogleSearch', 'Related','TOC', _('2'), _('1'), 'GuideSearch', 'GuideSelect',  'NewDBs', 'NewGuides','CollectionList','GuideList','Experts', _('3'), _('5')), _("") ),

							"use_disciplines" => array( _( "Use SerSol Provided Disciplines" ), _( "Incluye integración de disciplinas de Serials Solutions." ), "boolean", "right", "small", "" ,
							_( "Si desea incluir sus guías SP en los resultados de Serials Solutions, es decir, tiene Invocación, necesita usar sus disciplinas." ) ),

							"api_enabled" => array( _( "Habilitar el servicio de API (la clave api es '" ) . "$api_key')", _( "Si está desactivada, la API devolverá los resultados vacíos" ), "boolean", "right", "small", "" , _( "Ir " ) . "<a href=\"../api/\" target=\"_blank\">" . _( "esta página" ) . "</a>" . _( " Para una explicación de cómo funciona el API." ) ),

							"user_bio_update" => array( _( "Habilitar a los usuarios para editar Bio" ), "", "boolean", "right", "small", "" , "" ),

							"user_photo_update" => array( _( "Habilitar a los usuarios para editar foto de Headshot" ), "", "boolean", "right", "small", "" , "" ),

							"target_blank" => array( _( "Mostrar enlaces de base de datos en una nueva pestaña" ), _("Afecta la visualización en databases.php y dentro de las guías de asignaturas"), "boolean", "right", "small", "" , "" ),

							"guide_headers" => array( _( "Conmutador de cabecera" ), _("Tener más de una opción de encabezado para una guía"), "array", "right", "large", "" , _("Introduzca una lista de encabezados separados por comas. El nombre de encabezado que coloque aquí corresponderá a un archivo de encabezado en el servidor. Por ejemplo, 'chc' apunta a los temas / includes / header_chc.php") ),

							"subjects_theme" => array( _( "Usar un tema" ), _("Utilice un tema secundario para sustituir el tema predeterminado. Introduzca el nombre del directorio que ha creado en temas / temas /"), "string", "right", "small", "" , "" ), 

							"css_override" => array( _( "Usar tu propio CSS" ), _("No debe confundirse con 'Usar un tema'. Esto apuntará a su propio CSS en lugar del predeterminado."), "string", "right", "small", "" , "" ), 

							"guide_index_page" => array( _( "Usar una guía como página de índice" ), _("Si desea que una de sus guías sea la página de bienvenida en / subjects /, escriba la forma abreviada"), "string", "right", "medium", "" , "" ), 

							"collection_thumbnail" => array( _( "Imagen por defecto de la colección" ), _("Establezca la imagen que se muestra de forma predeterminada en una página de recopilación. Debe poner esta imagen en / assets / images / guide_thumbs /"), "string", "right", "medium", "" , "" ), 

							"mod_rewrite" => array( _( "Utilizar reescrituras de URL" ), _( "Hacer vínculos más bonitos." ), "boolean", "right", "small", "" , "" ),

							"BaseURL" => array( _( "URL base de la instalación de SubjectPlus" ), _( "p.ej. 'Http://www.yoursite.edu/library/sp/.' ¡Asegúrese de incluir la barra inclinada! <strong> Si se cambia, deberá volver a iniciar sesión. </strong>" ), "string", "left", "large", "" ,"" ),

							"resource_name" => array( _( "Nombre de este recurso" ), _( "p.ej. SubjectsPlus, Guías de Investigación" ), "string", "left-bottom", "medium", "" ,"" ),

							"institution_name" => array( _( "Nombre de la Institución" ), _( "Nombre de su universidad / institución" ), "string", "left-bottom", "medium", "" ,"" ),

							"administrator" => array( _( "Nombre del administrador de la biblioteca" ), _( "Nombre del administrador de SubjectPlus" ), "string", "left-bottom", "medium", "" , _("Esto aparecerá en el pie de página de SP.") ),

							"administrator_email" => array( _( "Dirección de correo electrónico del administrador de la biblioteca" ), _( "Dirección de correo electrónico del administrador de SubjectsPlus" ), "string", "left-bottom", "medium", "" , _("Esto aparecerá en pie de página y también se utilizará como correo electrónico predeterminado para las presentaciones de TalkBack.") ),

							"email_key" => array( _( "Clave de correo electrónico" ), _( "Finalización de las direcciones de correo electrónico del campus, incluyendo @ signo" ), "string", "left-bottom", "medium", "" ,_( "Esto permite una entrada más sencilla." ) ),

							"tel_prefix" => array( _( "Prefijo del Télefono" ), _( "Prefijo de prefijo al número de teléfono para el personal. Usualmente código de área." ), "string", "left-bottom", "small", "" , _( "Le permite poner una versión corta (no prefijada) del número de teléfono en páginas donde el espacio es limitado." ) ),

							"hname" => array( _( "Nombre de host de MySQL" ), _( "Este es el ip o url a su base de datos MySQL." ), "string", "left", "medium", "" , _( "Ayuda?" ) ),
	  
	  					"db_port" => array( _( "Puerto MySQL" ), _( "Este es el puerto que usa su base de datos MySQL." ), "string", "left", "medium", "" , _( "" ) ),

							"uname" => array( _( "Nombre de usuario de MySQL" ), _( "Este es el nombre de usuario de su base de datos MySQL" ), "string", "left", "medium", "" ,"" ),

							"pword" => array( _( "Contraseña MySQL" ), _( "Esta es la contraseña para su usuario de MySQL." ), "pword", "left", "medium", "" ,"" ),

							"dbName_SPlus" => array( _( "Base de datos MySQL SubjectsPlus" ), _( "Nombre de la base de datos SubjectsPlus" ), "string", "left", "" , _( "Ayuda?" ) ),

							"upload_whitelist" => array( _( "Lista de extensiones de archivos admitidas" ), _( "Esta opción contiene la lista separada por coma de extensiones de archivo aceptadas para cargas de archivos a través de CKEditor." ), "array", "right", "large", "" , _("Si un archivo no está en esta lista, no debe cargarse. Las subidas de archivos sólo se producen a través del backend de administración, usando CKEditor, pero esto es para impedir que un usuario haga algo, uh, tonto.") ),

							"proxyURL" => array( _( "Proxy URL" ), _( "Cadena que se debe añadir antes de usar un servidor proxy" ), "string", "right-bottom", "large", "" , _( "En la pestaña Registros de SP, si marca un elemento como 'restringido', la cadena de proxy se agregará previamente." ) ),

							"open_string" => array( _( "Cadena abierta" ), _( "Se utiliza para crear un enlace a un elemento de su catálogo. El término de búsqueda de su contenido está intercalado entre estas dos cadenas" ), "string", "right-bottom", "medium", "" , "" ),

							"close_string" => array( _( "Cadena Cerrada" ), _( "Si no necesita cerrar cadena, deje en blanco." ), "string", "right-bottom", "medium", "" , "" ),

							"open_string_kw" => array( _( "Palabra clave de cadena abierta" ), _( "Como arriba, para la búsqueda por palabra clave." ), "string", "right-bottom", "medium", "" , "" ),

							"close_string_kw" => array( _( "Cerrar palabra clave de cadena" ), _( "Si necesario." ), "string", "right-bottom", "medium", "" , "" ),

							"open_string_cn" => array( _( "Número de llamada de cadena abierta" ), _( "Como anteriormente, para la búsqueda de número de llamada." ), "string", "right-bottom", "medium", "" , "" ),

							"close_string_cn" => array( _( "Número de llamada de Cadena Cerrada " ), _( "Si necesario" ), "string", "right-bottom", "medium", "" , "" ),

							"open_string_bib" => array( _( "Open String Bib" ), _( "Se utiliza para crear un enlace a un elemento de su catálogo. Su término de búsqueda Bib está intercalado entre estas dos cadenas" ), "string", "right-bottom", "medium", "" , "" ),

							"CKBasePath" => array( _( "Base Path para CKEditor" ), _( "Ruta de acceso a los archivos de CKEditor agregados a la URL de base" ), "string", "left", "medium", "" , _( "CKEditor se utiliza para generar los cuadros de entrada de datos WYSIWYG. Se incluye con Temas Plus en la carpeta sp / root. Si mueve CKEditor a otra ubicación, deberá cambiar esta ruta." ) ),

							"syndetics_client_code" => array( _( "Código de Cliente Syndetics" ), _( "Esta opción contiene el código de cliente necesario para utilizar Syndetics" ), "string", "right", "medium", "" , ""),

							"google_books_api_key" => array( _( "Clave de la API de Google Libros" ), _( "Esta opción contiene la clave de API necesaria para utilizar la clave de la API de Google Libros en la lista de libros Pluslet" ), "string", "right", "large", "" , ""),

							"booklist_primo_domain" => array( _( "Lista de libros Pluslet Primo Domain" ), _( "Esta opción contiene el dominio Primo para la lista de libros Pluslet" ), "string", "right", "large", "" , ""),

							"booklist_primo_institution_code" => array( _( "Lista de libros Pluslet Primo Código de la institución" ), _( "Esta opción contiene el código de la institución Primo para Book List Pluslet" ), "string", "right", "large", "" , ""),

							"booklist_primo_vid" => array( _( "Ver Lista de libros Pluslet Primo " ), _( "Esta opción contiene la vista Primo para la lista de libros Pluslet" ), "string", "right", "large", "" , ""),

							"booklist_primo_api_key" => array( _( "Lista de libros PlusletPrimo API Key" ), _( "Esta opción contiene la clave Primo API para la lista de libros Pluslet" ), "string", "right", "large", "" , "")

	);

	//set config options
	$lobjConfig->setConfigOptions( $lobjConfigOptions );

	//if posted form
	if( isset( $_POST['submit_edit_config'] ) )
	{
		//get POST variables based on options array
		$lobjConfig->setNewConfigValues();

		//check if new values are acceptable
		$lstrMessage = $lobjConfig->checkDBConnection( );

		if( $lstrMessage != '' )
		{
			//display error message on top of page
			$lobjConfig->displayMessage( _( $lstrMessage ) );

			//display edit HTML form with new values
			$lobjConfig->displayEditConfigForm( 'new' );

		}else
		{
			//if no error to connect to database, write to config file with new values
			$lobjReturn = $lobjConfig->writeConfigFile();

			//if error did not return
			if( $lobjReturn )
			{
				//if salt changed, log current person out and back in
				if( $lobjConfig->getChangeSalt() )
				$_SESSION[ 'checkit' ] = md5($_SESSION['email']) . $lobjReturn[1];

				//display message
				$lobjConfig->displayMessage( _( "Hágase tu voluntad." ) );

				//if the base URL of SubjectsPlus changes, log them out and relocate to new BaseURL
				if( $lobjConfig->isNewBaseURL() )
				{
					// Unset all of the session variables.
					$_SESSION = array();

					// Finally, destroy the session.
					session_destroy();

					//echo out javascript to relocate user
					echo "<script type=\"text/javascript\">window.location = '{$lobjConfig->getNewBaseURL()}control/logout.php';</script>";
				}

				//if the mod_rewrite option changed
				if( $lobjConfig->isNewModRewrite() )
				{
					//write the approriate .htaccess file to given path
					$lobjConfig->wrtieModRewriteFile( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'subjects' . DIRECTORY_SEPARATOR . '.htaccess' );
				}

			}else
			{
				//error message
				$lobjConfig->displayMessage( _( "Algo salió mal y no se pudo guardar las configuraciones." ) );
			}

			//display edit HTML form with new values
			$lobjConfig->displayEditConfigForm( 'new' );
		}
	}else if (isset( $_POST['clear_cache_btn'] )){
		//display message
		$lobjConfig->displayMessage( _( "Hágase tu voluntad." ) );
		$lobjConfig->displayEditConfigForm();

		$prefix = explode('control', dirname(__FILE__));
		array_map('unlink', glob( "$prefix[0]/assets/cache/*.jpg"));
		array_map('unlink', glob( "$prefix[0]/assets/cache/*.bookmetadata"));
	}else {
		//no message and HTML form with original config values
		$lobjConfig->displayMessage( '' );
		$lobjConfig->displayEditConfigForm( );
	}


}

//SubjectsPlus footer
include("includes/footer.php");

//extra css to style tooltip feature
//javascript for 'not right?' functionality, array ticks functionality, required
//fields check functionality, and hover tooltip functionality
?>
<style type="text/css">
textarea
{
	resize: none;
}
</style>
<script type="text/javascript">

	//enable textbox two elements before 'this'
	function enableTextBox( lobjThis )
	{
		jQuery( lobjThis ).parent().prev().prev().attr('disabled', false);
	}

	jQuery(document).ready(function($)
	{
		///////////////
		/* ctags     */
		///////////////

		$("span[class*=ctag-]").livequery('click', function() {

			var all_tags = "";

			// change to other class
			if ($(this).attr("class") == "ctag-off") {
				$(this).attr("class", "ctag-on");
			} else {
				$(this).attr("class", "ctag-off");
			}

			//get name of c-tag which represents which tags to read and which input to change
			var lstrName = $(this).attr('name');

			// determine the new selected items
			$(this).parent().find(".ctag-on[name=\"" + lstrName + '"]').each(function(i) {
				var this_ctag = $(this).text();
				all_tags = all_tags + this_ctag + ",";

			});
			// strip off final pipe (,)
			all_tags = all_tags.replace( /[,]$/, "" );
			// set new value to hidden form field

			$('input[name="' + lstrName + '"]').val(all_tags);
		});

		////////////////
		// Check Submit
		// When the form has been submitted, check required fields
		////////////////

		$("#config_form").submit( function () {

			// If a required field is empty, set zonk to 1, and change the bg colour
			// of the offending field
			var alerter = 0;

			$("div.required_field").children('input').each(function() {
				// get contents of string, trim off whitespace
				var our_contents = $(this).val();
				var our_contents  = jQuery.trim(our_contents );

				if (our_contents  == '' && $(this).attr( 'name' ) != 'pword') {
					$(this).attr("style", "background-color:#FFDFDF");
					alerter = 1;
				} else {
					$(this).attr("style", "background-color:none");
				}

				return alerter;

			});

			// Popup warning if required fields not complete
			if (alerter == 1) {
				alert("<?php print _("You must complete all required form fields."); ?>");
				return false;
			}else
			{
				$('input').attr('disabled', false);
			}
		});
	});
</script>
