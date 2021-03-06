<?php

namespace AppBundle\Procuration;

use AppBundle\Entity\Adherent;
use AppBundle\Entity\ProcurationProxy;
use AppBundle\Entity\ProcurationRequest;
use AppBundle\Mailjet\Message\ProcurationProxyCancelledMessage;
use AppBundle\Mailjet\Message\ProcurationProxyFoundMessage;
use AppBundle\Mailjet\Message\ProcurationProxyReminderMessage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProcurationProxyMessageFactory
{
    private $urlGenerator;
    private $replyToEmailAddress;

    public function __construct(UrlGeneratorInterface $urlGenerator, string $replyToEmailAddress)
    {
        $this->urlGenerator = $urlGenerator;
        $this->replyToEmailAddress = $replyToEmailAddress;
    }

    public function createProxyCancelledMessage(ProcurationRequest $request, ProcurationProxy $proxy, ?Adherent $procurationManager): ProcurationProxyCancelledMessage
    {
        $message = ProcurationProxyCancelledMessage::create($request, $proxy, $procurationManager);
        $message->setReplyTo($this->replyToEmailAddress);

        return $message;
    }

    public function createProxyFoundMessage(Adherent $procurationManager, ProcurationRequest $request, ProcurationProxy $proxy): ProcurationProxyFoundMessage
    {
        $url = $this->urlGenerator->generate('app_procuration_my_request', [
            'id' => $request->getId(),
            'token' => $request->generatePrivateToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $message = ProcurationProxyFoundMessage::create($procurationManager, $request, $proxy, $url);
        $message->setReplyTo($this->replyToEmailAddress);

        return $message;
    }

    public function createProxyReminderMessage(ProcurationRequest $request): ProcurationProxyReminderMessage
    {
        $url = str_replace(
            'http://localhost',
            'https://en-marche.fr',
            $this->urlGenerator->generate('app_procuration_my_request', [
                'id' => $request->getId(),
                'token' => $request->generatePrivateToken(),
            ], UrlGeneratorInterface::ABSOLUTE_URL)
        );

        $message = ProcurationProxyReminderMessage::create($request, $url);
        $message->setReplyTo($this->replyToEmailAddress);

        return $message;
    }
}
