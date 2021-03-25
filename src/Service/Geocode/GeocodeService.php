<?php


namespace App\Service\Geocode;


use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use OpenCage\Geocoder\Geocoder as Geocoder;

class GeocodeService
{
    private $user;
    private $params;

    public function __construct(User $user, ParameterBagInterface $params)
    {
        $this->user = $user;
        $this->params = $params;

    }
    public function transformAddressInCoordinates()
    {
        $geocoder = new Geocoder($this->params->get('api_geocode_key'));
        $result = $geocoder->geocode($this->user->getAddress());

    }

}
