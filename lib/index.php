<?php

// Kickstart the framework
$f3=require('fatfree-master/lib/base.php');

$f3->set('DEBUG',1);
echo"salut le monde";
// if ((float)PCRE_VERSION<7.9)
	// trigger_error('PCRE version is out of date');
         //$f3->set ('UI','ui/');
// Load configuration
// $f3->config('config.ini');


 		
// database MySql
$f3->set ('DB',
new DB\ SQL('mysql:host=localhost;dbname=blog', 'root', 'user'));
  $f3->run();
  // Accueil de l'administrateur
 $f3 -> route ( 'GET / admin' ,
   function ( $f3 ) {
   	  if ( !$f3 -> get ('SESSION.user' )) $f3 -> reroute ( '/' ) ;
   	   // affecter un mappeur à la table utilisateur
 $user = new DB\SQL\ Mappeur ( $f3 -> get ('DB') , 'utilisateur' ) ;
 // indique à Auth les champs de base de données à utiliser
 $auth = new \ Auth ( $utilisateur ,
   array ( 'id' => 'nom' , 'pw' => 'mot de passe' ) ) ;
 // ce qui suit affichera une page d'erreur HTTP 401 non autorisée si elle échoue
 $auth -> basic () ;
   }
 )
 // afficher vue page d'accueil
 $f3 -> route ( 'GET /' ,
   function ( $f3 ) {
     $f3 -> set ( 'html_title' , 'Home Page' ) ;
     $article = new DB \ SQL \ Mappeur ( $ f3 -> get ( 'DB' ) , 'article' ) ;
     $f3 -> set ( 'list' , $ article -> find ( ) ) ;
   $f3 -> set ( 'content' , 'blog_home.html' ) ;
   echo Template :: instance ( ) -> render ( 'layout.html' ) ;
   }) 
 // Admin Ajouter
 $f3 -> route ( 'GET / admin / add' ,
   function ( $f3 ) {
   	  $f3 -> set ( 'html_title' , 'My Blog Create' ) ;
     $f3 -> set ( 'content' , 'admin_edit.html' ) ;
     echo template :: instance ( ) -> render ( 'layout.html' ) ;
   }
 )
 // Admin Modifier
 $f3 -> route ( 'GET / admin / edit / @ id' ,
   function ( $f3 ) {
   	 $f3 -> set ( 'html_title' , 'My Blog Edit' ) ;
     $id = $f3 -> get ( 'PARAMS.id' ) ;
     $article = new DB\ SQL\ Mappeur ( $f3 -> get ( 'DB' ) , 'article' ) ;
     $article -> load ( array ( 'id =?' , $id ) ) ;
     $article -> copyTo ( 'POST' ) ;
     $f3 -> set ( 'content' , 'admin_edit.html' ) ;
     echo template :: instance ( ) -> render ( 'layout.html' ) ;
   }
 )
 // Admin Add et Edit traitent tous les deux des Posts Form
 // n'utilise pas de fonction lambda ici
 $f3 -> route ( 'POST / admin / edit / @ id' , 'éditer' ) ;
 $f3 -> route ( 'POST / admin / add' , 'edit' ) ;
 function edit ( $f3 ) {
 	$id = $f3 -> get ( 'PARAMS.id' ) ;
   // crée un objet article
   $article = new DB \ SQL \ Mappeur ( $f3 -> get ( 'DB' ) , 'article' ) ;
   // si nous ne le chargeons pas, le Mapper fera un insert au lieu de update quand nous utiliserons la commande save
   if ( $id ) $article -> load ( array ( 'id =?' , $id ) ) ;
   // écraser avec les valeurs qui viennent d'être envoyées
   $article -> copyFrom ( 'POST' ) ;
   // crée un horodatage au format MySQL
   $article -> timestamp = date ( "Ymd H: i: s" ) ;
   $article -> save ( ) ;
   // Retour à la page d'accueil de l'administrateur, la nouvelle entrée de blog devrait maintenant être là
   $f3 -> reroute ( '/ admin' ) ;
 })
 // Admin Supprimer
 $f3 -> route ( 'GET / admin / delete / @ id' ,
   function ( $f3 ) {
   	 $id = $f3 -> get ( 'PARAMS.id' ) ;
     $article = new DB \ SQL \ Mappeur ( $f3 -> get ( 'DB' ) , 'article' ) ;
     $article -> load ( array ( 'id =?' , $id ) ) ;
     $article -> effacer ( ) ;
     $f3 -> reroute ( '/ admin' ) ;
   }
 ) 
   // page d'acceuil
 $f3->route('GET /',
 	function ($f3){
 		$f3->set('html_title','Home Page');
   $article = new DB \SQL \ Mappeur ( $f3 -> get ( 'DB' ) , 'article' ) ;
 $articles = $article -> find ( ) ;
 $f3 -> set ( 'list' , $articles ) ; 
  $f3 -> set ( 'content' , 'blog_home.html' ) ;
   echo Template :: instance ( ) -> render ( 'layout.html' ) ; 
$f3 -> route ( 'GET / view / @ id' ,
     function ( $f3 ) {
     $id = $f3 -> get ( 'PARAMS.id' ) ;
     // crée un objet Mapper et recherche l'identifiant
     $article = new DB \ SQL \ Mappeur ( $f3 -> get ( 'DB' ) , 'article' ) ;
     $article -> load ( array ( 'id =?' , $id ) ) ;
     // définir des variables de structure
     $f3 -> set ( 'html_title' , $article -> titre ) ;
     $article -> copyTo ( 'POST' ) ;
     // servir la vue
     $f3 -> set ( 'content' , 'blog_detail.html' ) ;
     echo Template :: instance ( ) -> render ( 'layout.html' ) ;
   }
 ) 

  ?>
