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
     * @OA\Get(
     *     path="/news/",
     *     summary="Get all news with paginate",
     *     tags={"News"},
     *     description="Get all news with paginate",
     *     @OA\Parameter(
     *         name="page",
     *         in="path",
     *         description="page number",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="path",
     *         description="sort by asc/desc",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="News fields",
     *         required=false,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="some troubles",
     *     )
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

    /**
     * @OA\Get(
     *     path="/news/post/{id}",
     *     summary="Get news post by id",
     *     tags={"News"},
     *     description="Get news post by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="News id",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="News post is not found",
     *     )
     * )
     */

    public function showOne(int $id)
    {

        $post = DB::table('news')
            ->find($id);

        return view('post', ['post' => $post]);
    }
}
