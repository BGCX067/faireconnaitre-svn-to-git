<?php

class AddTextToImage
{
 // Format du copyright
 var $copyright = 'e Copyright %s. Tous droits reserves.';
 
 // Initialisation du tableau contenant les fichiers
 var $files = array();
 
 // Filtres
 var $ignore = array( '.', '..' );

 // Constructeur
 function AddTextToImage( $owner, $path = '.' )
 {
  // Formatte le copyright
  $this->copyright = sprintf( $this->copyright, $owner );
  // Recherche les fichiers image
  $this->CheckFiles( $path );
  
  // Pour chaque fichier ...
  foreach ( $this->files as $file )
  {
   // ... insere le copyright
   $this->WriteCopy( $file );
  }
  
  // Affiche un message de fin
  echo 'Copyright ins�r� !';
 }
 
 // Recherche les fichiers image
 function CheckFiles( $path )
 {
  // Ouvre le repertoire courant ...
  $dp = opendir( $path );
  // ... et le lit
  while ( ( $file = readdir( $dp ) ) !== false )
  {
   // Filtre les repertoires/fichiers contenu dans
   // le tableau $ignore
   if ( !in_array( $file, $this->ignore ) )
   {
    // Si le fichiers lu est un dossier, ...
    if ( is_dir( $path . '/' . $file ) )
    {
     // on l'ouvre en appelant de nouveau la
     // fonction Checkfiles()
     $this->CheckFiles( $path . '/' . $file );
    }
    // Sinon
    elseif
    (
     is_file( $path . '/' . $file )
      &&
     // Si le fichier est bien une image
     (
     substr( $file, strlen( $file ) - 3, 3 ) == 'png'
      ||
     substr( $file, strlen( $file ) - 3, 3 ) == 'gif'
      ||
     //!\\ Attention, je n'ai pas teste sur
     //!\\ le format JPEG !
     substr( $file, strlen( $file ) - 3, 3 ) == 'jpg'
     )
    )
    {
     // Onm l'indexe dans le tableau $files
     $this->files[] = $path . '/' . $file;
    }
   }
  }

  // On ferme le r�pertoire
  closedir( $dp );
 }
 
 // Ins�re le copyright dans une image
 function WriteCopy( $file )
 {
  // Ouvre le fichier image en mode "ajouter"
  $fp = fopen( $file, 'a' );
  // Ins�re le copyright
  fwrite( $fp, $this->copyright );
  // Ferme le fichier
  fclose( $fp );
 }
}

// Utilisation de la classe relativement simple, il vous
// suffit simplement de placer le script � la racine de
// site/dossier et de l'ex�cuter. Le 2�me param�tre est
// obtionnel.
// Notez qu'il suffit que d'une seule ligne.
//
// Prennez soin de faire des copies de vos images avant
// d'y ins�rer le copyright sinon en cas de r�utilisation
// du script, il serait ajout� une 2�me fois !
//$copy = new AddCopyright( 'Votre nom ou l\'adresse de votre site web', '.' );

?>