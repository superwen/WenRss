<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rss;
use App\Models\RssEntry;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $tag = $request->input('tag');
        switch($tag) {
            case "tech":
                $tagTitle = "技术";
                break;
            case "shop":
                $tagTitle = "生活";
                break;
            default:
                $tag = null;
                $tagTitle = "全部";
        }
        $page = intval($request->input('page'));
        if($tag && in_array($tag, ['tech', 'shop'])) {
            $entries = RssEntry::where('tag', $tag)
                ->orderBy('published', 'desc')
                ->paginate(20);
        } else {
            $entries = RssEntry::orderBy('published', 'desc')
                ->paginate(20);
        }
        return view('rssList', [
            'nowtime' => time(),
            'tag' => $tag,
            'tagTitle' => $tagTitle,
            'entries' => $entries,
        ]);
    }

    public function entry(Request $request)
    {
        $nowtime = intval($request->input('nowtime'));
        $page = intval($request->input('page'));
        $tag = $request->input('tag');
        if($tag && in_array($tag, ['tech', 'shop'])) {
            $entries = RssEntry::where('published', '<', $nowtime)
                ->where('tag', $tag)
                ->orderBy('published', 'desc')
                ->paginate(20);
        } else {
            $entries = RssEntry::where('published', '<', $nowtime)
                ->orderBy('published', 'desc')
                ->paginate(20);
        }
        return view('entry', ['entries' => $entries]);
    }
}
