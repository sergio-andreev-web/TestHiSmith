<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
use App\Models\ParserLog;

class ParseRSS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:rss';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсинг RSS фида РБК';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rss_url = 'https://rssexport.rbc.ru/rbcnews/news/30/full.rss';
        $rss = new SimpleXMLElement($rss_url, null, true);

        $startTime = microtime(true);

        $this->line('parse:rss start');

        foreach ($rss->channel->item as $item) {
            $title = (string)$item->title;
            $description = (string)$item->description;
            $pub_date = (string)$item->pubDate;
            $pub_date = Carbon::parse($pub_date)->format('Y-m-d H:i:s');
            $author = (string)$item->author ?? null;
            $image = (string)$item->enclosure['url'] ?? null;
            $image_name = null;
            $this->line($title);


            //проверка, есть ли имг
            //есть кейсы, где вместо главного имга к посту поставляется видео.
            //берем к нему постер - первый встречающийся имг в новости
            if ($image) {
                $this->line($image);
                foreach ($item->enclosure as $enclosure) {
                    $this->line($enclosure['url']);

                    if ($enclosure['type'] == 'image/jpeg' || $enclosure['type'] == 'image/png') {
                        $image_contents = file_get_contents($enclosure['url']);
                        $image_info = getimagesize($enclosure['url']);
                        $image_mime = $image_info['mime'];

                        if ($image_mime == 'image/jpeg') {
                            $image_name = uniqid() . '.jpg';
                        } elseif ($image_mime == 'image/png') {
                            $image_name = uniqid() . '.png';
                        }

                        break;
                    }
                }
            }

            //проверяем, есть ли новость с таким тайтлом в базе
            //если нет - добавляем запись и сохраняем имг, если есть
            if (!DB::table('news')->where('title', $title)->exists()) {
                DB::table('news')->insert([
                    'title' => $title,
                    'description' => $description,
                    'published_at' => $pub_date,
                    'author' => $author,
                    'image' => $image_name ?? null,
                ]);

                if ($image_name) {
                    Storage::disk('public')->put("images/" . $image_name, $image_contents);
                }
            }


            $this->line('');
        }

        $this->line('parse:rss finish');

        $endTime = microtime(true);

        $client = new Client();
        $response = $client->get($rss_url);

        $execution_time = ($endTime - $startTime) * 1000;

        $logData = [
            'datetime' => now(),
            'method' => 'GET',
            'url' => $rss_url,
            'status_code' => $response->getStatusCode(),
            'response_body' => $response->getBody(),
            'execution_time' => $execution_time,
        ];

        //пишем логи в лог файл storage/logs/rss.log
        //настройки прописаны в файле config/logging.php
        Log::channel('rss_log')->debug(json_encode($logData));

        //пишем логи в базу
        $log = new ParserLog();
        $log->date_time = now();
        $log->request_method = 'GET';
        $log->request_url = $rss_url;
        $log->response_code = $response->getStatusCode();
        $log->response_body = $response->getBody();
        $log->execution_time = $execution_time;
        $log->save();
    }
}
