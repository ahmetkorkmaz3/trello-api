<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\BoardRequest;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class BoardRequestController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(auth()->user()->boardRequests);
    }

    /**
     * @param Request $request
     * @param BoardRequest $boardRequest
     * @return Response
     */
    public function update(Request $request, BoardRequest $boardRequest)
    {
        $boardRequest->update(['status' => $request->status]);
        $board = Board::find($boardRequest->board->id);
        $board->users()->attach($boardRequest->user->id);
        return response()->noContent(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BoardRequest  $boardRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(BoardRequest $boardRequest)
    {
        //
    }
}
