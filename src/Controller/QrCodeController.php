<?php

namespace App\Controller;

use App\Service\QrCodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class QrCodeController extends AbstractController
{
    private $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    #[Route('/generate', methods: ['POST'])]
    public function story(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'message' => 'Erro ao decodificar seu JSON',
            ], 500);
        }

        $qrcode = $this->qrCodeService->create($data);

        $qrcode->setImage(base64_encode($qrcode->getImage()));

        if ($qrcode) {
            return $this->json([
                'message' => 'Qr Code gerado com sucesso',
                'object' => $qrcode
            ], 201);
        } else {
            return $this->json([
                'message' => 'Erro ao gerar seu QrCode',
            ], 500);
        }
    }

    #[Route('/find', methods: ['GET'])]
    public function findAll(): JsonResponse
    {
        $data = $this->qrCodeService->getAll();
        foreach ($data as $dt) {
            $stream = $dt->getImage();
            if (is_resource($stream)) {
                $contents = stream_get_contents($stream);
            } else {
                $contents = $stream;
            }
            $dt->setImage(base64_encode($contents));
        }

        return $this->json($data, 200);
    }

    #[Route('/find/{id}', methods: ['GET'])]
    public function findOne(int $id)
    {
        $data = $this->qrCodeService->getById($id);
        $stream = $data->getImage();
        if (is_resource($stream)) {
            $contents = stream_get_contents($stream);
        } else {
            $contents = $stream;
        }

        $data->setImage(base64_encode($contents));

        if (!$data) {
            return $this->json(['message' => 'QrCode não encotrado'], 404);
        }

        return $this->json($data, 200);
    }
}
