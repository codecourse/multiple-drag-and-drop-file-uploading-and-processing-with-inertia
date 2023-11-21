<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoUpdateController extends Controller
{
    public function __invoke(Request $request, Video $video)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'max:1000',
        ]);

        $video->update($request->only('title', 'description'));

        return back();
    }
}
