<style>
    li {
        list-style-type: none; /* Убираем маркеры */
    }

    ul {
        margin-left: 0; /* Отступ слева в браузере IE и Opera */
        padding-left: 0; /* Отступ слева в браузере Firefox, Safari, Chrome */
    }

    .pagination {
        display: flex;
    }

    .page-item {
        padding: 0.3rem 0.5rem;
    }

    .page-item.active {
        background: #4a5568;
        color: #fff;
    }

    .page-item a {
        text-decoration: none;
    }

    .news_body_item, .news_header {
        display: flex;
    }

    .news_header {
        margin-bottom: 2rem;
        font-size: 24px;
    }

    .news_header_item_title, .news_body_item_title {
        width: 80%;
    }

    .news_header_item_date, .news_body_item_date {
        width: 20%;
        text-align: center;
    }
</style>

<div class="news_space">
    <div class="news_header">
        <div class="news_header_item_title">Название</div>
        <div class="news_header_item_date">Дата публикации</div>
    </div>
    <div class="news_body">
        @foreach ($news as $item)
            <div class="news_body_item">
                <div class="news_body_item_title"><a href="news/post/{{ $item->id }}">{{ $item->title }}</a></div>
                <div class="news_body_item_date">{{ $item->published_at }}</div>
            </div>
        @endforeach
    </div>
</div>

{{ $news->appends(request()->except('page'))->links() }}
