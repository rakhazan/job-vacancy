<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurposeJob;
use App\Models\Uploaded;
use Illuminate\Support\Facades\Storage;

class UploadedController extends Controller
{

    public function index(Request $request)
    {
        // $data = PurposeJob::where('job_vacancy_id', $request['j'])->where('candidate_detail_id', auth()->user()->id)->first();
        $data = Uploaded::where('user_id', auth()->user()->id)->get();
        return view('job-vacancy.uploadfile')->with(['data' => $data, 'jobId' => $request['j']]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'filename' => 'file|mimes:pdf',
            'upload_at' => 'required|date',
        ]);


        if ($request->file('filename')) {
            $validated['filename'] = $request->file('filename')->store('attachment');
        }
        $validated['user_id'] = auth()->user()->id;

        Uploaded::create($validated);

        return redirect()->route('job-vacancy.data', ['j' => $request['jobId']])->with(['success' => 'Successfully upload file']);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:uploadeds,id',
        ]);

        $upload = Uploaded::find($validated['id']);
        Storage::delete($upload->filename);
        Uploaded::destroy($validated['id']);
        PurposeJob::where('file_attach', $upload->filename)->update(['file_attach' => null]);

        return redirect()->route('job-vacancy.upload-file')->with('success', 'Successfully delete file');
    }
}
