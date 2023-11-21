<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoStoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $video = $request->user()->videos()->create($request->only('title'));

        return response()->json([
            'id' => $video->id
        ]);
    }
}
