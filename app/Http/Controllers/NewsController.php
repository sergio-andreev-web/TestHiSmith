<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;
use App\Models\News;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{

    const RSS_URL = "https://rssexport.rbc.ru/rbcnews/news/30/full.rss";

    public function showPhpInfo()
    {

        try {
            DB::connection()->getPdo();
            echo 'Successful database connection';
        } catch (\Exception $e) {
            var_dump($e);
        }

        return phpinfo();
    }

    /**
     * @SWG\Get(
     *     path="/show_all_news",
     *     summary="Get list of all news",
     *     tags={"News"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     * )
     */

    public function showAll(Request $request)
    {
        $page = $request->input('page', 1);
        $sort = $request->input('sort', 'desc');
        $fields = explode(',', $request->input('fields', 'id,title,published_at'));

        $news = DB::table('news')
            ->select($fields)
            ->orderBy('published_at', $sort)
            ->paginate(10, ['*'], 'page', $page);

        return view('news', ['news' => $news]);
    }

    public function showOne(int $id)
    {

        $post = DB::table('news')
            ->find($id);

        return view('post', ['post' => $post]);
    }
}
