<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProgramController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ProgramResource::collection(
            Program::query()->active()->get()
        );
    }

    public function show(Program $program): ProgramResource
    {
        abort_unless($program->is_active, 404);

        return new ProgramResource($program);
    }
}
