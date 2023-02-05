<style>
    .post {

    }

    .post_title {
        font-size: 32px;
        width: 50%;
    }

    .post_desc {
        margin-bottom: 1rem;
    }

    .post_image {
        width: 400px;
    }

    .post_image img {
        width: 100%;
    }

    .post_info {
        display: flex;
        color: gray;
        font-size: 12px;
    }

    .post_author {

    }

    .post_date {

    }

</style>

<div class="post">
    <h1 class="post_title">{{$post->title}}</h1>
    @if ($post->image)
        <div class="post_image">
            <img src="{{ asset('storage/images/' . $post->image) }}" alt="{{ $post->title }}">
        </div>
    @endif
    <div class="post_desc">{{$post->description}}</div>
    <div class="post_info">
        <div class="post_date">{{$post->published_at}}</div>
        @if ($post->author)
            <div class="post_author">, {{$post->author}}</div>
        @endif
    </div>
</div>
