<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Client;
use Zend\Http\Request;
use Album\Model\Album;   
use Album\Form\AlbumForm;  
use Zend\ServiceManager\ServiceLocatorInterface;


class AlbumController extends AbstractActionController 
{

	protected $albumTable;

// Cette fonction va ce connecter a l'api d'OpenweatherMap
	public function indexAction($arg = "")
	{

		// Connexion a l'api en lui précisant quel paramétre on à besoin
		$client = new Client();
		$client->setUri('http://api.openweathermap.org/data/2.5/forecast');
		$client->setMethod('GET');
		// Cette condition est la ville par default quand on arrive sur /album
		if(empty($_GET['q'])){
			$_GET['q'] = "paris";
		}
		// Cette condition va rechercher une ville grâce au nom écrit
		if(intval($_GET['q']) === 0){
			$client->setParameterGet(array(
				'APPID' => 'f2c246eeae237fa30976e8b726af9942',
				"q" => $_GET['q'],
				'units' => "metric"
				));
		}
		// Sinon on va recherche une ville grace au code postal
		else{
			$client->setParameterGet(array(
				'APPID' => 'f2c246eeae237fa30976e8b726af9942',
				"zip" => $_GET['q']. ",fr",
				'units' => "Metric"
				));
		}

		$response = $client->send();
		// la réponse est en format json onla décode pour l'avoir en Tableau
		$realResponse = json_decode($response->getContent(), true);

		/*
		Cette condition indique si la ville n'existe pas 
		elle recherchera par default la ville de paris 
		et indiquera dans la view que la ville n'a pas était trouvé
		*/
		if ($realResponse["cod"] === "404") {
			$_GET['q'] = "paris";
			return $this->indexAction("Ville non trouver");
		}
		$_GET["message"] = "";


		// On envoi le résultat dans la view
		return new ViewModel(array(
			'responses' => $realResponse,
			'message'=> $arg
			));
	}
}