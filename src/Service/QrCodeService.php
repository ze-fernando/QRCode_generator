<?php

namespace App\Service;

use App\Entity\QrCode as EntityQrCode;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use PhpParser\Node\Expr\List_;

class QrCodeService
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function create($data): EntityQrCode
    {
        $writer = new PngWriter();

        $qrCode = new QrCode(
            data: $data['url'],
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        $data['image'] = $writer->write($qrCode)->getString();

        $entity = new EntityQrCode();

        $entity->setTitle($data['title']);
        $entity->setUrl($data['url']);
        $entity->setImage($data['image']);

        $this->manager->persist($entity);
        $this->manager->flush();

        return $entity;
    }

    public function getAll(): array
    {
        return $this->manager->getRepository(EntityQrCode::class)->findAll();
    }

    public function getById(int $id): mixed
    {
        return $this->manager->getRepository(EntityQrCode::class)->find($id) ?? null;
    }
}
