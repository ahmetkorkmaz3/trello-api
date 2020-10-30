<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TeamRequestController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {

        return response()->json(auth()->user()->teamRequests);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TeamRequest  $teamRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeamRequest $teamRequest)
    {
        $teamRequest->update(['status' => $request->status]);
        $team = Team::find($teamRequest->team->id);
        $team->users()->attach($teamRequest->user->id);
        return response()->noContent(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeamRequest  $teamRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeamRequest $teamRequest)
    {
        //
    }
}
