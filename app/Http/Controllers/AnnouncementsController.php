<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnnouncementsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $search = $request->input('search');

            $announcements = Announcement::where('title', 'like', '%' . $search . '%')->get();

            return view('pages.sitesSettings.announcements', compact('announcements'));
        }



        $announcements = Announcement::all();

        return view('pages.sitesSettings.announcements', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'announcement' => 'required',
            'date' => 'required',
        ]);

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->announcement,
            'date' => $request->date,
            'is_active' => 1
        ]);

        Cache::forget('translatedAnnouncementsru');
        Cache::forget('translatedAnnouncementsen');

       return back();
    }

    public function activate(Request $request) {

        $announcement = Announcement::where('id', $request->id)->first();
        if ($announcement->is_active === 1) {
            $announcement->is_active = 0;
            $announcement->save();

        } else {
            $announcement->is_active = 1;
            $announcement->save();
        }

        Cache::forget('translatedAnnouncementsru');
        Cache::forget('translatedAnnouncementsen');

        return back();

    }

    public function updateHtmx(Request $request) {

        $announcement = Announcement::where('id', $request->id)->first();
        $id = $request->id;

        return view('pages.htmx.htmxUpdateAnnouncement', compact('announcement', 'id'));
    }

    public function update(Request $request) {

        $request->validate([
            'title' => 'required',
            'announcement' => 'required',
            'date' => 'required',
        ]);

        $announcement = Announcement::find($request->id);
        $announcement->title = $request->title;
        $announcement->content = $request->announcement;
        $announcement->date = $request->date;
        $announcement->save();

        Cache::forget('translatedAnnouncementsru');
        Cache::forget('translatedAnnouncementsen');

        return back();

    }

    public function delete(Request $request) {
        $announcement = Announcement::find($request->id);
        $announcement->delete();
        Cache::forget('translatedAnnouncementsru');
        Cache::forget('translatedAnnouncementsen');
        return back();
    }
}
