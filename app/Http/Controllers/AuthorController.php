<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorListRequest;
use App\Repositories\AuthorRepository;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    /**
     * @var AuthorRepository
     */
    protected AuthorRepository $repository;

    public function __construct(AuthorRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list
     *
     * @param AuthorListRequest $request
     * @return JsonResponse
     */
    public function index(AuthorListRequest $request): JsonResponse
    {
        return response()->json($this->repository->getList($request->getDto()));
    }
}
