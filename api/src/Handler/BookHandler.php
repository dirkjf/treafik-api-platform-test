<?php

declare(strict_types=1);

namespace App\Handler;

use ApiPlatform\Api\IriConverterInterface;
use ApiPlatform\Api\UrlGeneratorInterface;
use ApiPlatform\JsonLd\Serializer\ItemNormalizer;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use App\Entity\Book;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
final class BookHandler
{
    public function __construct(
        private readonly IriConverterInterface $iriConverter,
        private readonly SerializerInterface $serializer,
        private readonly ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        private readonly HttpClientInterface $client,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(Book $book): void
    {
        try {
            $response = $this->client->request('GET', 'https://api.imgflip.com/get_memes');
        } catch (TransportExceptionInterface $transportException) {
            $this->logger->error('Cannot call Imgflip API.', [
                'error' => $transportException->getMessage(),
            ]);

            return;
        }

        try {
            $contents = $response->toArray();
        } catch (DecodingExceptionInterface $exception) {
            $this->logger->error('Invalid JSON from Imgflip API.', [
                'error' => $exception->getMessage(),
            ]);

            return;
        }

        $imageUrl = $contents['data']['memes'][\mt_rand(0, 99)]['url'];
        $imageContent = (string) \file_get_contents($imageUrl);

        // Set Book.cover image in base64
        $book->cover = \sprintf(
            'data:image/%s;base64,%s',
            \pathinfo((string) $imageUrl, PATHINFO_EXTENSION),
            \base64_encode($imageContent)
        );
    }
}
