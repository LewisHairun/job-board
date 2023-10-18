<?php

namespace App\EventSubscriber;

use App\Entity\EventLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventLogSubscriber implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
{
        $dataForm = $event->getRequest()->request;
        $fileForm = $event->getRequest()->files;
        $data = [];
        $route = $event->getRequest()->attributes->get('_route');
        $routes_login = ["admin_login", "candidate_login"];


        if (!in_array($route, $routes_login)) {
            if ($dataForm && $dataForm->keys() && $dataForm->keys()[0]) {
                $key = $dataForm->keys()[0];
                $dataForm = (array) $dataForm;
                $values = $dataForm["\x00*\x00parameters"][$key];
    
                if (array_key_exists("_token", $values)) {
                    unset($values["_token"]);
                }
    
                if (array_key_exists("plainPassword", $values)) {
                    unset($values["plainPassword"]);
                }
    
                $data = array_merge($values);
            }
                
            if ($fileForm && $fileForm->keys() && $fileForm->keys()[0]) {
                $key = $fileForm->keys()[0];
                $fileForm = (array) $fileForm;
                $values = $fileForm["\x00*\x00parameters"][$key];
                
                if (array_key_exists("_token", $values)) {
                    unset($values["_token"]);
                }
    
                if (isset($values) && isset($values[0]) && $values[0]->getClientOriginalName() && array_values($values)[0]->getMimetype()) {
                    $mimeType = $this->checkExtension(array_values($values)[0]->getMimetype());
                    $filename = array_values($values)[0]->getClientOriginalName() ?? null;
    
                    if ($filename && $mimeType) {
                        $data[$mimeType] = $filename;
                    }
                }
            }
    
            $path = $event->getRequest()->getPathInfo();
            $log = (array) json_encode($data);
    
            $eventLog = new EventLog;
            $eventLog->setPath($path);
            $eventLog->setLog($log);
    
            $this->entityManager->persist($eventLog);
            $this->entityManager->flush();
        }
    }

    public function checkExtension(string $mimeType): string
    {
        return match ($mimeType) {
            "image/jpeg" => "image",
            "image/jpg" => "image",
            "image/png" => "image",
            "application/pdf" => "pdf"
        };
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}