<?php

namespace Lt\Regitra\Controllers;

use DateTime;
use Exception;
use Lt\Regitra\Data\Client;
use Lt\Regitra\Exceptions\ValidationException;
use Lt\Regitra\Storage\ClientRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ClientController
{
    private ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function add(Request $request, Response $response, $args): void
    {
        $body = $request->getBody()->getContents();

        $data = json_decode($body, true);

        $client = self::arrayToClient($data);


        $count = $this->clientRepository->add($client);

        $insertedData = self::clientToArray($client);

        $response->getBody()->write(json_encode($insertedData));
    }

    public function clear(Request $request, Response $response, $args): void
    {
        $this->clientRepository->clear();
        $response->getBody()->write('cleared');
    }

    public function getAll(Request $request, Response $response, $args): void
    {
        $clients = $this->clientRepository->getAll();

        $clientsArray = array_map(fn(Client $client) => self::clientToArray($client), $clients);
        $response->getBody()->write( json_encode($clientsArray));
    }


    public static function arrayToClient(array $data): Client
    {
        if( $data['name'] == null ) {
            throw new ValidationException('Vardas neturi būti tuščias');
        }

        if( $data['family_name'] == null ) {
            throw new ValidationException('Pavardė neturi būti tuščia');
        }

        if ($data['birth_date'] == null ) {
            throw new ValidationException('Nenurodyta gimimo data');
        }

        try {
            $date = new DateTime($data['birth_date']);
        } catch (Exception $e) {
            throw new ValidationException('Neteisinga gimimo data '.$data['birth_date']);
        }

        return (new Client())
            ->setName($data['name'])
            ->setFamilyName($data['family_name'])
            ->setBirthDate($date);
    }

    public static function clientToArray(Client $client): array
    {
        return [
            'name' => $client->getName(),
            'family_name' => $client->getFamilyName(),
            'birth_date' => $client->getBirthDate()->format('Y-m-d'),
        ];
    }
}